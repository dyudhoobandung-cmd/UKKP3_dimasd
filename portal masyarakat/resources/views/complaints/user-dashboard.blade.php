@extends('layouts.app')

@section('title', 'Dashboard | Portal Masyarakat')

@section('content')
    <header class="page-heading" id="overview">
        <div>
            <p class="eyebrow">DASHBOARD MASYARAKAT</p>
            <h1>Halo, {{ auth()->user()->name }}</h1>
            <p>Pilih layanan yang ingin Anda gunakan hari ini.</p>
        </div>
        <span class="page-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
    </header>

    @if (session('success'))
        <div class="alert" role="status">{{ session('success') }}</div>
    @endif

    <section class="stats" aria-label="Ringkasan pengaduan">
        <article class="stat"><span>Total laporan</span><strong>{{ $counts['total'] }}</strong></article>
        <article class="stat"><span>Menunggu</span><strong>{{ $counts['pending'] }}</strong></article>
        <article class="stat"><span>Diproses</span><strong>{{ $counts['in_progress'] }}</strong></article>
        <article class="stat"><span>Selesai</span><strong>{{ $counts['resolved'] }}</strong></article>
    </section>

    <section class="choice-section" aria-labelledby="service-choice-heading">
        <div class="section-heading">
            <p class="eyebrow">LAYANAN</p>
            <h2 id="service-choice-heading">Apa yang ingin Anda lakukan?</h2>
            <p>Pilih salah satu untuk melanjutkan. Daftar laporan tidak ditampilkan sampai Anda membukanya.</p>
        </div>

        <div class="choice-grid">
            <a class="choice-card" href="{{ route('user.complaints.create') }}">
                <span class="choice-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 5v14M5 12h14"/><circle cx="12" cy="12" r="9"/></svg>
                </span>
                <span class="choice-title">Buat laporan baru</span>
                <span class="choice-description">Sampaikan kendala atau masalah di lingkungan Anda agar dapat ditindaklanjuti.</span>
                <span class="choice-action">Mulai membuat laporan <span aria-hidden="true">→</span></span>
            </a>

            <a class="choice-card" href="{{ route('user.complaints.index') }}">
                <span class="choice-icon choice-icon-muted" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 4.5h14A1.5 1.5 0 0 1 20.5 6v12a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18V6A1.5 1.5 0 0 1 5 4.5Z"/><path d="M7.5 9h9M7.5 12.5h9M7.5 16h5"/></svg>
                </span>
                <span class="choice-title">Lihat laporan saya</span>
                <span class="choice-description">Periksa status, tanggapan admin, atau batalkan laporan yang masih menunggu.</span>
                <span class="choice-action">Buka daftar laporan <span aria-hidden="true">→</span></span>
            </a>
        </div>
    </section>
@endsection
