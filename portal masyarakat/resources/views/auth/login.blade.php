@extends('layouts.app')

@section('title', 'Masuk | Portal Masyarakat')

@section('content')
    <a class="guest-back" href="{{ route('home') }}">← Kembali ke halaman utama</a>
    <section class="card">
        <p class="eyebrow">PORTAL MASYARAKAT</p>
        <h2>Selamat datang kembali</h2>
        <p class="intro">Masuk ke akun Anda untuk mengakses layanan pengaduan.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="email">Alamat email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>
            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
            <button class="button auth-submit" type="submit">Masuk</button>
        </form>

        <p class="switch">Belum memiliki akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
    </section>
@endsection
