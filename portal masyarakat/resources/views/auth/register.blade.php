@extends('layouts.app')

@section('title', 'Daftar | Portal Masyarakat')

@section('content')
    <a class="guest-back" href="{{ route('home') }}">← Kembali ke halaman utama</a>
    <section class="card">
        <p class="eyebrow">PENDAFTARAN WARGA</p>
        <h2>Buat akun masyarakat</h2>
        <p class="intro">Daftar untuk mengirim laporan dan memantau tindak lanjutnya.</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="field">
                <label for="name">Nama lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="email">Alamat email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Konfirmasi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password">
            </div>
            <button class="button auth-submit" type="submit">Buat akun</button>
        </form>

        <p class="switch">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a></p>
    </section>
@endsection
