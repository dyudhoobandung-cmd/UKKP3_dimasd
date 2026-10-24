@extends('layouts.app')

@section('title', 'Dashboard Admin | Portal Masyarakat')

@section('content')
    <header class="page-heading" id="overview">
        <div>
            <p class="eyebrow">DASHBOARD ADMINISTRATOR</p>
            <h1>Ringkasan pengaduan</h1>
            <p>Selamat datang, {{ auth()->user()->name }}. Kelola laporan masyarakat dari satu tempat.</p>
        </div>
        <span class="page-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
    </header>

    @if (session('success'))
        <div class="alert" role="status">{{ session('success') }}</div>
    @endif

    <section class="stats" aria-label="Ringkasan pengaduan">
        <article class="stat"><span>Total pengaduan</span><strong>{{ $counts['total'] }}</strong></article>
        <article class="stat"><span>Menunggu</span><strong>{{ $counts['pending'] }}</strong></article>
        <article class="stat"><span>Diproses</span><strong>{{ $counts['in_progress'] }}</strong></article>
        <article class="stat"><span>Selesai</span><strong>{{ $counts['resolved'] }}</strong></article>
    </section>

    <section class="panel" id="complaints">
        <div class="panel-heading">
            <h2>Semua pengaduan</h2>
            <p class="panel-intro">Cari laporan, atur prioritas, dan kirim tanggapan kepada pelapor.</p>
        </div>

        <form class="filter filter-admin" method="GET" action="{{ route('admin.dashboard') }}">
            <div class="field">
                <label for="admin-search">Cari laporan/pelapor</label>
                <input id="admin-search" type="search" name="q" value="{{ $search }}" placeholder="Judul, isi, nama, atau email">
            </div>
            <div class="field">
                <label for="status-filter">Status</label>
                <select id="status-filter" name="status_filter">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Complaint::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="category-filter">Kategori</label>
                <select id="category-filter" name="category_filter">
                    <option value="">Semua kategori</option>
                    @foreach (\App\Models\Complaint::CATEGORIES as $value => $label)
                        <option value="{{ $value }}" @selected($categoryFilter === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="priority-filter">Prioritas</label>
                <select id="priority-filter" name="priority_filter">
                    <option value="">Semua prioritas</option>
                    @foreach (\App\Models\Complaint::PRIORITIES as $value => $label)
                        <option value="{{ $value }}" @selected($priorityFilter === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button class="button" type="submit">Terapkan</button>
                @if ($search || $statusFilter || $categoryFilter || $priorityFilter)
                    <a class="button button-secondary" href="{{ route('admin.dashboard') }}">Reset</a>
                @endif
            </div>
        </form>

        @forelse ($complaints as $complaint)
            <article class="complaint">
                <div class="complaint-head">
                    <div>
                        <h4>{{ $complaint->subject }}</h4>
                        <div class="complaint-meta">
                            #{{ $complaint->id }} · {{ $complaint->user->name }} ({{ $complaint->user->email }})
                            · {{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}
                            <span class="priority priority-{{ $complaint->priority }}">{{ \App\Models\Complaint::PRIORITIES[$complaint->priority] ?? $complaint->priority }}</span>
                            · {{ $complaint->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <span class="status status-{{ $complaint->status }}">{{ \App\Models\Complaint::STATUSES[$complaint->status] ?? $complaint->status }}</span>
                </div>
                <p class="complaint-text">{{ $complaint->description }}</p>
                <form class="admin-update" method="POST" action="{{ route('admin.complaints.update', ['complaint' => $complaint->id, 'q' => $search, 'status_filter' => $statusFilter, 'category_filter' => $categoryFilter, 'priority_filter' => $priorityFilter]) }}">
                    @csrf
                    @method('PATCH')
                    <div class="field">
                        <label for="status-{{ $complaint->id }}">Status</label>
                        <select id="status-{{ $complaint->id }}" name="status" required>
                            @foreach (\App\Models\Complaint::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $complaint->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="priority-{{ $complaint->id }}">Prioritas</label>
                        <select id="priority-{{ $complaint->id }}" name="priority" required>
                            @foreach (\App\Models\Complaint::PRIORITIES as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', $complaint->priority) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="response-{{ $complaint->id }}">Tanggapan admin</label>
                        <textarea id="response-{{ $complaint->id }}" name="admin_response" maxlength="5000" placeholder="Tulis tanggapan (opsional)">{{ old('admin_response', $complaint->admin_response) }}</textarea>
                    </div>
                    <button class="button" type="submit">Simpan</button>
                </form>
            </article>
        @empty
            <p class="empty">Belum ada pengaduan untuk ditampilkan.</p>
        @endforelse

        @if ($complaints->hasPages())
            <div class="pagination">{{ $complaints->links() }}</div>
        @endif
    </section>
@endsection
