@extends('layouts.app')

@section('title', 'Laporan Saya | Portal Masyarakat')

@section('content')
    <header class="page-heading">
        <div>
            <p class="eyebrow">RIWAYAT</p>
            <h1>Laporan saya</h1>
            <p>Pantau status dan tanggapan dari setiap laporan yang Anda kirim.</p>
        </div>
        <a class="button heading-action" href="{{ route('user.complaints.create') }}">+ Buat laporan</a>
    </header>

    @if (session('success'))
        <div class="alert" role="status">{{ session('success') }}</div>
    @endif

    <section class="panel">
        <form class="filter" method="GET" action="{{ route('user.complaints.index') }}">
            <div class="field">
                <label for="user-search">Cari laporan</label>
                <input id="user-search" name="q" type="search" value="{{ $search }}" placeholder="Judul atau isi laporan">
            </div>
            <div class="field">
                <label for="user-status-filter">Status</label>
                <select id="user-status-filter" name="status_filter">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Complaint::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button class="button" type="submit">Terapkan</button>
                @if ($search || $statusFilter)
                    <a class="button button-secondary" href="{{ route('user.complaints.index') }}">Reset</a>
                @endif
            </div>
        </form>

        @forelse ($complaints as $complaint)
            <article class="complaint">
                <div class="complaint-head">
                    <div>
                        <h3>{{ $complaint->subject }}</h3>
                        <div class="complaint-meta">
                            #{{ $complaint->id }} · {{ \App\Models\Complaint::CATEGORIES[$complaint->category] ?? $complaint->category }}
                            <span class="priority priority-{{ $complaint->priority }}">{{ \App\Models\Complaint::PRIORITIES[$complaint->priority] ?? $complaint->priority }}</span>
                            · {{ $complaint->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <span class="status status-{{ $complaint->status }}">{{ \App\Models\Complaint::STATUSES[$complaint->status] ?? $complaint->status }}</span>
                </div>
                <p class="complaint-text">{{ $complaint->description }}</p>
                @if ($complaint->admin_response)
                    <div class="response"><strong>Tanggapan admin</strong>{{ $complaint->admin_response }}</div>
                @endif
                @if ($complaint->status === 'pending')
                    <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" onsubmit="return confirm('Batalkan laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button class="button button-danger button-small" type="submit">Batalkan laporan</button>
                    </form>
                @endif
            </article>
        @empty
            <div class="empty-state">
                <span class="choice-icon choice-icon-muted" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 4.5h14A1.5 1.5 0 0 1 20.5 6v12a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18V6A1.5 1.5 0 0 1 5 4.5Z"/><path d="M7.5 9h9M7.5 12.5h9"/></svg>
                </span>
                <h2>{{ $search || $statusFilter ? 'Laporan tidak ditemukan' : 'Belum ada laporan' }}</h2>
                <p>{{ $search || $statusFilter ? 'Coba ubah kata kunci atau filter status.' : 'Laporan yang Anda kirim akan tampil di halaman ini.' }}</p>
                @unless ($search || $statusFilter)
                    <a class="button" href="{{ route('user.complaints.create') }}">Buat laporan pertama</a>
                @endunless
            </div>
        @endforelse

        @if ($complaints->hasPages())
            <div class="pagination">{{ $complaints->links() }}</div>
        @endif
    </section>
@endsection
