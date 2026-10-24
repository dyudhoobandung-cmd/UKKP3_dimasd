<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function userDashboard(): View
    {
        $user = auth()->user();

        return view('complaints.user-dashboard', [
            'counts' => [
                'total' => $user->complaints()->count(),
                'pending' => $user->complaints()->where('status', 'pending')->count(),
                'in_progress' => $user->complaints()->where('status', 'in_progress')->count(),
                'resolved' => $user->complaints()->where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('complaints.create');
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status_filter' => ['nullable', 'in:'.implode(',', array_keys(Complaint::STATUSES))],
        ]);
        $search = $validated['q'] ?? null;
        $statusFilter = $validated['status_filter'] ?? null;

        return view('complaints.index', [
            'complaints' => $request->user()->complaints()
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('subject', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                }))
                ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:'.implode(',', array_keys(Complaint::CATEGORIES))],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
        ]);

        $request->user()->complaints()->create($validated);

        return redirect()->route('user.complaints.index')->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function adminDashboard(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status_filter' => ['nullable', 'in:'.implode(',', array_keys(Complaint::STATUSES))],
            'category_filter' => ['nullable', 'in:'.implode(',', array_keys(Complaint::CATEGORIES))],
            'priority_filter' => ['nullable', 'in:'.implode(',', array_keys(Complaint::PRIORITIES))],
        ]);
        $search = $validated['q'] ?? null;
        $statusFilter = $validated['status_filter'] ?? null;
        $categoryFilter = $validated['category_filter'] ?? null;
        $priorityFilter = $validated['priority_filter'] ?? null;

        $complaints = Complaint::with('user')
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            }))
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->when($categoryFilter, fn ($query) => $query->where('category', $categoryFilter))
            ->when($priorityFilter, fn ($query) => $query->where('priority', $priorityFilter))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('complaints.admin-dashboard', [
            'complaints' => $complaints,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'categoryFilter' => $categoryFilter,
            'priorityFilter' => $priorityFilter,
            'counts' => [
                'total' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'in_progress' => Complaint::where('status', 'in_progress')->count(),
                'resolved' => Complaint::where('status', 'resolved')->count(),
            ],
        ]);
    }

    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Complaint::STATUSES))],
            'priority' => ['required', 'in:'.implode(',', array_keys(Complaint::PRIORITIES))],
            'admin_response' => ['nullable', 'string', 'max:5000'],
        ]);

        $complaint->update($validated);

        return redirect()->route('admin.dashboard', $request->only('q', 'status_filter', 'category_filter', 'priority_filter'))
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function destroy(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless($complaint->user_id === $request->user()->id, 404);
        abort_unless($complaint->status === 'pending', 403);

        $complaint->delete();

        return redirect()->route('user.complaints.index')
            ->with('success', 'Pengaduan yang masih menunggu berhasil dibatalkan.');
    }
}
