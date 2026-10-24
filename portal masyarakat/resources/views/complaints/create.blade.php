@extends('layouts.app')

@section('title', 'Buat Laporan | Portal Masyarakat')

@section('content')
    <header class="page-heading">
        <div>
            <p class="eyebrow">LAYANAN PENGADUAN</p>
            <h1>Buat laporan baru</h1>
            <p>Isi detail berikut agar laporan dapat ditinjau dengan tepat.</p>
        </div>
        <a class="button button-secondary heading-action" href="{{ route('user.dashboard') }}">Kembali ke ringkasan</a>
    </header>

    <section class="panel form-panel">
        <div class="panel-heading">
            <h2>Informasi laporan</h2>
            <p class="panel-intro">Pastikan informasi yang Anda berikan jelas dan sesuai kondisi.</p>
        </div>
        <form method="POST" action="{{ route('complaints.store') }}">
            @csrf
            <div class="field">
                <label for="subject">Judul laporan</label>
                <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="150" placeholder="Contoh: Lampu jalan mati di Jalan Melati" required>
                @error('subject') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="category">Kategori</label>
                <select id="category" name="category" required>
                    <option value="">Pilih kategori</option>
                    @foreach (\App\Models\Complaint::CATEGORIES as $value => $label)
                        <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="description">Detail laporan</label>
                <textarea id="description" name="description" minlength="20" maxlength="5000" placeholder="Jelaskan lokasi, waktu, dan kondisi yang terjadi (minimal 20 karakter)." required>{{ old('description') }}</textarea>
                <p class="field-hint">Minimal 20 karakter. Hindari menyertakan informasi pribadi yang tidak diperlukan.</p>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-actions">
                <a class="button button-secondary" href="{{ route('user.dashboard') }}">Batal</a>
                <button class="button" type="submit">Kirim laporan</button>
            </div>
        </form>
    </section>
@endsection
