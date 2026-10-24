@extends('layouts.app')

@section('title', 'Semua Pengguna | Portal Masyarakat')

@section('content')
    <header class="page-heading">
        <div>
            <p class="eyebrow">MANAJEMEN AKUN</p>
            <h1>Semua pengguna</h1>
            <p>Lihat akun yang terdaftar dan jumlah laporan masing-masing pengguna.</p>
        </div>
        <span class="page-date">{{ $users->total() }} akun terdaftar</span>
    </header>

    <section class="panel">
        <form class="users-toolbar" method="GET" action="{{ route('admin.users.index') }}">
            <div class="field">
                <label for="user-search">Cari pengguna</label>
                <input id="user-search" name="q" type="search" value="{{ $search }}" placeholder="Nama atau alamat email">
            </div>
            <button class="button" type="submit">Cari</button>
            @if ($search)
                <a class="button button-secondary" href="{{ route('admin.users.index') }}">Reset</a>
            @endif
        </form>

        @if ($users->isEmpty())
            <div class="empty-state">
                <span class="choice-icon choice-icon-muted" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3.5"/><path d="M2.8 19a6.2 6.2 0 0 1 12.4 0M16 5.2a3.5 3.5 0 0 1 0 6.8M17.2 13.2a5.6 5.6 0 0 1 4 5.3"/></svg>
                </span>
                <h2>{{ $search ? 'Pengguna tidak ditemukan' : 'Belum ada pengguna' }}</h2>
                <p>{{ $search ? 'Coba gunakan nama atau alamat email yang berbeda.' : 'Akun yang terdaftar akan tampil di halaman ini.' }}</p>
            </div>
        @else
            <div class="users-table-wrap">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jumlah laporan</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="user-name">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="role-badge {{ $user->role === 'admin' ? 'role-badge-admin' : '' }}">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span></td>
                                <td>{{ $user->complaints_count }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="pagination">{{ $users->links() }}</div>
            @endif
        @endif
    </section>
@endsection
