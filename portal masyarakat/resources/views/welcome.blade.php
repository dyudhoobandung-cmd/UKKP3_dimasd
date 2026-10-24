<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111016">
    <title>Portal Masyarakat | Layanan Pengaduan</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #eeedf2;
            background: #111016;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            --muted: #a29daa;
            --purple: #a783ee;
            --border: #302d38;
            --surface: #19181f;
        }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: radial-gradient(ellipse at 50% -30%, #27202f 0, #111016 65%); }
        a { color: inherit; text-decoration: none; }
        .page { width: min(100% - 40px, 1100px); margin: 0 auto; }
        .header { display: flex; height: 82px; align-items: center; justify-content: space-between; border-bottom: 1px solid #292630; }
        .brand { display: flex; align-items: center; gap: 11px; }
        .brand-mark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 11px; background: linear-gradient(145deg, #b28cf3, #7951c6); color: white; font-size: 19px; font-weight: 800; }
        .brand-name { color: #f4f2f7; font-size: 14px; font-weight: 700; letter-spacing: -.02em; }
        .nav { display: flex; align-items: center; gap: 10px; }
        .nav-link { padding: 9px 12px; border-radius: 7px; color: #c4bfca; font-size: 12px; font-weight: 600; }
        .nav-link:hover { background: #211e27; color: white; }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 0 16px; border: 1px solid transparent; border-radius: 7px; background: #825dc1; color: white; font-size: 12px; font-weight: 650; }
        .button:hover { background: #966fd7; }
        .button-secondary { border-color: #403a49; background: transparent; color: #ded9e6; }
        .button-secondary:hover { background: #211e27; }
        .hero { display: grid; min-height: 510px; grid-template-columns: minmax(0, 1.1fr) minmax(300px, .8fr); align-items: center; gap: 65px; padding: 64px 0; }
        .eyebrow { margin: 0 0 15px; color: #c2a8ee; font-size: 10px; font-weight: 750; letter-spacing: .14em; text-transform: uppercase; }
        h1 { max-width: 620px; margin: 0; color: #f4f2f7; font-size: clamp(36px, 5vw, 54px); font-weight: 690; letter-spacing: -.055em; line-height: 1.1; }
        .description { max-width: 510px; margin: 19px 0 0; color: var(--muted); font-size: 14px; line-height: 1.8; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 26px; }
        .trust-note { display: flex; align-items: center; gap: 8px; margin-top: 25px; color: #898491; font-size: 11px; }
        .trust-dot { width: 7px; height: 7px; border-radius: 50%; background: #9c82cb; }
        .preview { padding: 20px; border: 1px solid #393440; border-radius: 13px; background: #19181fdd; box-shadow: 0 24px 70px #00000035; }
        .preview-head { display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; border-bottom: 1px solid #302d38; }
        .preview-head strong { font-size: 12px; }
        .preview-head span { color: #85808d; font-size: 10px; }
        .preview-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 9px; margin: 14px 0; }
        .preview-stat { padding: 12px; border: 1px solid #302d38; border-radius: 8px; background: #141319; }
        .preview-stat span { display: block; color: #9994a0; font-size: 9px; }
        .preview-stat strong { display: block; margin-top: 7px; font-size: 17px; }
        .preview-item { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 13px 0; border-top: 1px solid #302d38; }
        .preview-item strong { display: block; font-size: 11px; }
        .preview-item small { display: block; margin-top: 4px; color: #93909b; font-size: 9px; }
        .status { padding: 5px 8px; border-radius: 99px; background: #252f29; color: #b8d9c3; font-size: 9px; white-space: nowrap; }
        .status-progress { background: #29243a; color: #d2baff; }
        .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 13px; padding: 0 0 45px; }
        .feature { padding: 18px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); }
        .feature-number { color: var(--purple); font-size: 10px; font-weight: 750; letter-spacing: .08em; }
        .feature h2 { margin: 11px 0 6px; font-size: 13px; font-weight: 650; }
        .feature p { margin: 0; color: var(--muted); font-size: 11px; line-height: 1.65; }
        .footer { display: flex; justify-content: space-between; gap: 12px; padding: 18px 0 24px; border-top: 1px solid #292630; color: #807b88; font-size: 10px; }
        @media (max-width: 760px) {
            .hero { min-height: auto; grid-template-columns: 1fr; gap: 32px; padding: 56px 0 38px; }
            .preview { max-width: 500px; }
            .features { grid-template-columns: 1fr; padding-bottom: 30px; }
            .feature { padding: 15px; }
        }
        @media (max-width: 480px) {
            .page { width: min(100% - 28px, 1100px); }
            .header { height: 68px; }
            .nav { gap: 2px; }
            .nav-link { padding: 8px; font-size: 11px; }
            .nav .button { min-height: 36px; padding: 0 11px; font-size: 11px; }
            .hero { padding-top: 43px; }
            .description { font-size: 13px; }
            .preview { padding: 15px; }
            .preview-stats { gap: 6px; }
            .preview-stat { padding: 10px 8px; }
            .footer { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true">P</span>
                <span class="brand-name">Portal Masyarakat</span>
            </a>
            <nav class="nav" aria-label="Navigasi utama">
                <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                <a class="button" href="{{ route('register') }}">Buat akun</a>
            </nav>
        </header>

        <main>
            <section class="hero">
                <div>
                    <p class="eyebrow">LAYANAN PUBLIK DIGITAL</p>
                    <h1>Sampaikan aspirasi. Pantau perubahannya.</h1>
                    <p class="description">Laporkan masalah di lingkungan Anda dengan mudah. Pantau setiap perkembangan dan tanggapan secara transparan melalui satu portal.</p>
                    <div class="hero-actions">
                        <a class="button" href="{{ route('register') }}">Mulai buat pengaduan</a>
                        <a class="button button-secondary" href="{{ route('login') }}">Masuk ke akun</a>
                    </div>
                    <p class="trust-note"><span class="trust-dot" aria-hidden="true"></span>Pengaduan Anda dapat dipantau langsung dari dashboard.</p>
                </div>

                <aside class="preview" aria-label="Contoh ringkasan dashboard">
                    <div class="preview-head">
                        <strong>Ringkasan pengaduan</strong>
                        <span>Dashboard warga</span>
                    </div>
                    <div class="preview-stats">
                        <div class="preview-stat"><span>Total laporan</span><strong>08</strong></div>
                        <div class="preview-stat"><span>Diproses</span><strong>03</strong></div>
                        <div class="preview-stat"><span>Selesai</span><strong>05</strong></div>
                    </div>
                    <div class="preview-item">
                        <div><strong>Perbaikan lampu jalan</strong><small>Infrastruktur · 24 Okt 2026</small></div>
                        <span class="status status-progress">Diproses</span>
                    </div>
                    <div class="preview-item">
                        <div><strong>Pengangkutan sampah</strong><small>Lingkungan · 22 Okt 2026</small></div>
                        <span class="status">Selesai</span>
                    </div>
                </aside>
            </section>

            <section class="features" aria-label="Cara menggunakan portal">
                <article class="feature">
                    <span class="feature-number">01 / KIRIM</span>
                    <h2>Buat pengaduan</h2>
                    <p>Pilih kategori dan jelaskan kendala yang Anda temui di lingkungan.</p>
                </article>
                <article class="feature">
                    <span class="feature-number">02 / PANTAU</span>
                    <h2>Lihat perkembangan</h2>
                    <p>Pantau status pengaduan dan tanggapan melalui dashboard pribadi.</p>
                </article>
                <article class="feature">
                    <span class="feature-number">03 / TUNTAS</span>
                    <h2>Dapatkan tindak lanjut</h2>
                    <p>Admin memperbarui progres dan memberikan tanggapan pada laporan.</p>
                </article>
            </section>
        </main>

        <footer class="footer">
            <span>Portal Masyarakat</span>
            <span>Layanan pengaduan masyarakat dalam satu tempat.</span>
        </footer>
    </div>
</body>
</html>
