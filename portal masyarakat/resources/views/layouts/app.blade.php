<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111016">
    <title>@yield('title', 'Portal Masyarakat')</title>
    <style>
        :root {
            color-scheme: dark;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #eeedf2;
            background: #111016;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            --surface: #19181f;
            --surface-raised: #1e1c25;
            --surface-input: #141319;
            --border: #302d38;
            --muted: #96929f;
            --purple: #a783ee;
            --purple-hover: #b99bf3;
            --purple-soft: #292236;
        }
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; background: #111016; }
        button, input, select, textarea { font: inherit; }
        a { color: var(--purple); text-decoration: none; }
        a:hover { color: var(--purple-hover); }
        .app-shell { display: grid; min-height: 100vh; grid-template-columns: 252px minmax(0, 1fr); }
        .sidebar { position: sticky; top: 0; display: flex; height: 100vh; flex-direction: column; padding: 25px 17px 18px; border-right: 1px solid #292630; background: #15141a; }
        .brand-link { display: flex; align-items: center; gap: 12px; margin: 2px 6px 38px; color: #f5f3f8; }
        .brand-link:hover { color: #f5f3f8; text-decoration: none; }
        .brand-mark { display: grid; width: 39px; height: 39px; flex: 0 0 auto; place-items: center; border-radius: 12px; background: linear-gradient(145deg, #b28cf3, #7951c6); color: white; font-size: 19px; font-weight: 800; box-shadow: 0 5px 18px #8158cb30; }
        .brand-copy strong { display: block; font-size: 14px; letter-spacing: -.02em; }
        .brand-copy small { display: block; margin-top: 3px; color: #888391; font-size: 9px; font-weight: 700; letter-spacing: .13em; }
        .sidebar-label { padding: 0 11px; color: #777380; font-size: 10px; font-weight: 750; letter-spacing: .12em; }
        .side-nav { display: grid; gap: 5px; margin-top: 12px; }
        .side-link { display: flex; min-height: 43px; align-items: center; gap: 11px; padding: 0 11px; border: 1px solid transparent; border-radius: 9px; color: #aaa6b2; font-size: 13px; font-weight: 550; transition: background .15s ease, color .15s ease; }
        .side-link:hover { background: #211f27; color: #efedf4; text-decoration: none; }
        .side-link.active { border-color: #3a304a; background: #262131; color: #d2baff; }
        .nav-icon { width: 18px; height: 18px; flex: 0 0 auto; color: currentColor; }
        .sidebar-spacer { flex: 1; }
        .sidebar-bottom { padding-top: 15px; border-top: 1px solid #292630; }
        .profile { display: flex; align-items: center; gap: 10px; padding: 3px 6px 15px; }
        .avatar { display: grid; width: 34px; height: 34px; flex: 0 0 auto; place-items: center; border: 1px solid #514565; border-radius: 50%; background: #2a2436; color: #d7c4fa; font-size: 12px; font-weight: 750; }
        .profile-copy { min-width: 0; }
        .profile-copy strong { display: block; overflow: hidden; color: #e7e4ec; font-size: 12px; text-overflow: ellipsis; white-space: nowrap; }
        .profile-copy small { display: block; margin-top: 3px; color: #908b99; font-size: 11px; }
        .logout-form { margin: 0; }
        .logout-button { display: flex; width: 100%; height: 39px; align-items: center; gap: 10px; padding: 0 10px; border: 1px solid transparent; border-radius: 8px; background: transparent; color: #aaa6b2; text-align: left; cursor: pointer; }
        .logout-button:hover { border-color: #39313e; background: #211e25; color: #f1d1d1; }
        .main-content { width: min(100%, 1600px); min-width: 0; margin: 0 auto; padding: 39px clamp(22px, 4vw, 58px) 54px; }
        #overview, #new-complaint, #complaints { scroll-margin-top: 20px; }
        .guest-shell { width: min(100% - 32px, 440px); margin: 0 auto; padding: 52px 0; }
        .guest-brand { margin-bottom: 27px; text-align: center; }
        .guest-brand .brand-mark { width: 48px; height: 48px; margin: 0 auto 13px; border-radius: 15px; font-size: 22px; }
        .guest-brand h1 { margin: 0 0 5px; font-size: 24px; letter-spacing: -.045em; }
        .guest-brand p { margin: 0; color: var(--muted); font-size: 13px; }
        .guest-back { display: inline-flex; margin-bottom: 14px; color: #a7a2af; font-size: 11px; }
        .guest-back:hover { color: #d5c2f2; }
        .auth-submit { width: 100%; margin-top: 20px; }
        .card, .panel { border: 1px solid var(--border); border-radius: 13px; background: var(--surface); }
        .card { padding: 29px; box-shadow: 0 15px 40px #00000022; }
        .card h2 { margin: 0 0 7px; font-size: 21px; letter-spacing: -.035em; }
        .card .intro { margin: 0 0 22px; color: var(--muted); font-size: 13px; line-height: 1.6; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 26px; }
        .eyebrow { margin: 0 0 8px; color: var(--purple); font-size: 10px; font-weight: 750; letter-spacing: .12em; text-transform: uppercase; }
        .page-heading h1 { margin: 0; font-size: clamp(23px, 3vw, 29px); font-weight: 680; letter-spacing: -.05em; }
        .page-heading p { margin: 7px 0 0; color: var(--muted); font-size: 13px; line-height: 1.55; }
        .page-date { flex: 0 0 auto; color: #898592; font-size: 11px; }
        .heading-action { flex: 0 0 auto; }
        .choice-section { margin-top: 30px; }
        .section-heading { margin-bottom: 16px; }
        .section-heading .eyebrow { margin-bottom: 6px; }
        .section-heading h2 { margin: 0; font-size: 17px; font-weight: 650; letter-spacing: -.02em; }
        .section-heading > p:last-child { margin: 6px 0 0; color: var(--muted); font-size: 12px; line-height: 1.6; }
        .choice-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .choice-card { display: flex; min-height: 195px; flex-direction: column; align-items: flex-start; padding: 21px; border: 1px solid var(--border); border-radius: 11px; background: var(--surface); color: #eeedf2; transition: border-color .15s ease, background .15s ease, transform .15s ease; }
        .choice-card:hover { transform: translateY(-2px); border-color: #594770; background: #1d1a24; color: #f4f0fa; text-decoration: none; }
        .choice-icon { display: grid; width: 38px; height: 38px; place-items: center; border: 1px solid #4d3d63; border-radius: 9px; background: #292236; color: #c8aef1; }
        .choice-icon svg { width: 18px; height: 18px; }
        .choice-icon-muted { border-color: #393641; background: #24222a; color: #b0aab8; }
        .choice-title { margin-top: 17px; font-size: 14px; font-weight: 650; }
        .choice-description { max-width: 410px; margin-top: 7px; color: var(--muted); font-size: 11px; line-height: 1.6; }
        .choice-action { display: flex; width: 100%; align-items: center; justify-content: space-between; gap: 10px; margin-top: auto; padding-top: 18px; color: #c9b1f1; font-size: 11px; font-weight: 650; }
        .form-panel { max-width: 760px; }
        .field-hint { margin: 6px 0 0; color: #888391; font-size: 10px; line-height: 1.5; }
        .form-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px; padding-top: 17px; border-top: 1px solid #302d37; }
        .form-actions .button { min-width: 100px; }
        .empty-state { display: flex; min-height: 245px; flex-direction: column; align-items: center; justify-content: center; padding: 28px 12px; text-align: center; }
        .empty-state h2 { margin: 14px 0 0; font-size: 14px; }
        .empty-state p { max-width: 360px; margin: 7px 0 0; color: var(--muted); font-size: 11px; line-height: 1.6; }
        .empty-state .button { margin-top: 16px; }
        .users-table-wrap { overflow-x: auto; }
        .users-table { width: 100%; border-collapse: collapse; text-align: left; }
        .users-table th { padding: 11px 12px; border-bottom: 1px solid #38343f; color: #918c99; font-size: 10px; font-weight: 650; white-space: nowrap; }
        .users-table td { padding: 13px 12px; border-bottom: 1px solid #302d37; color: #d7d3dd; font-size: 11px; }
        .users-table tr:last-child td { border-bottom: 0; }
        .users-table .user-name { color: #eeebf2; font-weight: 620; }
        .role-badge { display: inline-flex; padding: 4px 8px; border: 1px solid #393641; border-radius: 99px; background: #24222a; color: #c4becd; font-size: 9px; font-weight: 650; }
        .role-badge-admin { border-color: #493b5c; background: #282235; color: #d1b9f5; }
        .users-toolbar { display: flex; align-items: end; gap: 9px; margin-bottom: 14px; }
        .users-toolbar .field { width: min(100%, 430px); margin: 0; }
        .users-toolbar .button { flex: 0 0 auto; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 13px; margin-bottom: 20px; }
        .stat { min-height: 101px; padding: 17px 18px; border: 1px solid var(--border); border-radius: 11px; background: var(--surface); }
        .stat span { color: #a39eaa; font-size: 12px; }
        .stat strong { display: block; margin-top: 12px; color: #f1eef5; font-size: 25px; font-weight: 680; letter-spacing: -.04em; }
        .dashboard-grid { display: grid; grid-template-columns: minmax(285px, .78fr) minmax(0, 1.55fr); align-items: start; gap: 16px; }
        .panel { min-width: 0; padding: 21px; }
        .panel-heading { margin-bottom: 19px; }
        .panel h2, .panel h3 { margin: 0; color: #f0edf4; font-size: 15px; font-weight: 650; letter-spacing: -.015em; }
        .panel .panel-intro { margin: 6px 0 0; color: var(--muted); font-size: 12px; line-height: 1.55; }
        .field { min-width: 0; margin-top: 15px; }
        label { display: block; margin-bottom: 6px; color: #d7d3dd; font-size: 11px; font-weight: 600; }
        input, textarea, select { width: 100%; min-height: 40px; padding: 9px 11px; border: 1px solid #38343f; border-radius: 7px; outline: none; background: var(--surface-input); color: #eeebf2; font-size: 12px; }
        textarea { min-height: 105px; resize: vertical; line-height: 1.55; }
        input::placeholder, textarea::placeholder { color: #77727f; }
        input:focus, textarea:focus, select:focus { border-color: #9673cf; box-shadow: 0 0 0 3px #9673cf20; }
        .error { margin: 6px 0 0; color: #ffaaa6; font-size: 11px; }
        .button { display: inline-flex; min-height: 40px; align-items: center; justify-content: center; padding: 0 15px; border: 1px solid transparent; border-radius: 7px; background: #825dc1; color: white; font-size: 12px; font-weight: 650; text-decoration: none; cursor: pointer; transition: background .15s ease, border-color .15s ease; }
        .button:hover { background: #966fd7; color: white; text-decoration: none; }
        .button-primary { width: 100%; margin-top: 18px; }
        .button-secondary { border-color: #403a49; background: #24212a; color: #d0cbd7; }
        .button-secondary:hover { border-color: #5a4b71; background: #302a3a; }
        .button-danger { border-color: #58353a; background: #302126; color: #f2b7bb; }
        .button-danger:hover { background: #48272e; }
        .button-small { min-height: 32px; margin-top: 10px; padding: 0 10px; font-size: 11px; }
        .filter { display: grid; grid-template-columns: minmax(140px, 1.5fr) minmax(120px, 1fr) auto; align-items: end; gap: 9px; margin-bottom: 12px; }
        .filter .field { margin: 0; }
        .filter-admin { grid-template-columns: minmax(190px, 1.6fr) repeat(3, minmax(115px, 1fr)) auto; }
        .filter-actions { display: flex; gap: 7px; }
        .alert { margin-bottom: 16px; padding: 12px 14px; border: 1px solid #49395e; border-radius: 8px; background: #241e30; color: #d5c1f5; font-size: 12px; }
        .complaint { padding: 16px 0; border-top: 1px solid #302d37; }
        .complaint:first-of-type { border-top: 0; padding-top: 5px; }
        .complaint-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 13px; }
        .complaint h3 { margin: 0 0 6px; color: #eeebf2; font-size: 13px; font-weight: 640; }
        .complaint-meta { color: #96919e; font-size: 10px; line-height: 1.7; }
        .complaint-text { margin: 10px 0; color: #c5c0cb; font-size: 12px; line-height: 1.65; white-space: pre-wrap; }
        .status, .priority { display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 99px; background: #382f22; color: #f0ce91; font-size: 10px; font-weight: 650; white-space: nowrap; }
        .status-in_progress { background: #222b3d; color: #a6c1f4; }
        .status-resolved { background: #23332d; color: #a7d6bc; }
        .status-rejected { background: #3b272a; color: #e7afb0; }
        .priority { margin-left: 4px; background: #292631; color: #c5becf; }
        .priority-high { background: #3b2828; color: #eab5a8; }
        .priority-low { background: #292631; color: #c4b8df; }
        .response { margin-top: 10px; padding: 11px 12px; border-left: 2px solid #9272c2; border-radius: 0 7px 7px 0; background: #211e27; color: #cac4d1; font-size: 11px; line-height: 1.55; }
        .response strong { display: block; margin-bottom: 4px; color: #d7c6f1; font-size: 10px; }
        .admin-update { display: grid; grid-template-columns: minmax(112px, .75fr) minmax(112px, .75fr) minmax(160px, 1.4fr) auto; align-items: end; gap: 9px; margin-top: 13px; }
        .admin-update .field { margin: 0; }
        .empty { padding: 30px 10px; color: var(--muted); text-align: center; font-size: 12px; }
        .pagination { margin-top: 15px; font-size: 12px; }
        .pagination nav > div:first-child { display: none; }
        .pagination nav > div:last-child { display: flex; justify-content: center; gap: 5px; }
        .pagination a, .pagination span { padding: 6px 10px; border: 1px solid #38343f; border-radius: 6px; background: var(--surface-input); color: #c7b1ea; }
        .badge { display: inline-flex; margin-left: 5px; padding: 4px 8px; border: 1px solid #493b5c; border-radius: 99px; background: #282235; color: #d1b9f5; font-size: 9px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .remember { display: flex; align-items: center; gap: 8px; margin-top: 15px; color: #c5c0cb; font-size: 12px; }
        .remember input { width: 15px; min-height: 15px; height: 15px; margin: 0; accent-color: #9673cf; }
        .switch { margin: 20px 0 0; color: var(--muted); text-align: center; font-size: 12px; }
        @media (max-width: 1100px) {
            .app-shell { grid-template-columns: 220px minmax(0, 1fr); }
            .main-content { padding: 32px 25px 42px; }
            .filter-admin { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .admin-update { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 760px) {
            .app-shell { grid-template-columns: 1fr; align-content: start; }
            .sidebar { position: sticky; z-index: 10; height: auto; padding: 12px 17px 10px; border-right: 0; border-bottom: 1px solid #292630; }
            .brand-link { margin: 0 4px 12px; }
            .sidebar-label, .sidebar-spacer, .profile { display: none; }
            .side-nav { display: flex; gap: 5px; overflow-x: auto; margin: 0; padding-bottom: 2px; }
            .side-link { min-height: 36px; flex: 0 0 auto; padding: 0 10px; font-size: 11px; }
            .nav-icon { width: 15px; height: 15px; }
            .sidebar-bottom { position: absolute; top: 12px; right: 17px; padding: 0; border: 0; }
            .logout-button { width: auto; height: 36px; padding: 0 10px; font-size: 11px; }
            .logout-button .nav-icon { display: none; }
            .main-content { padding: 27px 19px 38px; }
            #overview, #new-complaint, #complaints { scroll-margin-top: 125px; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-grid { grid-template-columns: 1fr; }
            .choice-grid { grid-template-columns: 1fr; }
            .choice-card { min-height: 175px; }
        }
        @media (max-width: 520px) {
            .main-content { padding: 22px 13px 30px; }
            .page-heading { align-items: flex-start; margin-bottom: 19px; }
            .page-date { display: none; }
            .heading-action { padding: 0 10px; font-size: 10px; }
            .stats { gap: 9px; margin-bottom: 13px; }
            .stat { min-height: 84px; padding: 13px; }
            .stat strong { margin-top: 8px; font-size: 22px; }
            .panel { padding: 16px; }
            .filter, .filter-admin, .admin-update { grid-template-columns: 1fr; }
            .filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .users-toolbar { align-items: stretch; flex-direction: column; }
            .users-toolbar .field { width: 100%; }
            .users-toolbar .button { width: 100%; }
            .complaint-head { flex-direction: column; }
            .guest-shell { padding-top: 35px; }
            .card { padding: 23px 19px; }
        }
    </style>
</head>
<body>
    @auth
        <div class="app-shell">
            <aside class="sidebar">
                <a class="brand-link" href="{{ route('dashboard') }}">
                    <span class="brand-mark" aria-hidden="true">P</span>
                    <span class="brand-copy">
                        <strong>Portal Masyarakat</strong>
                        <small>LAYANAN PUBLIK</small>
                    </span>
                </a>

                <div class="sidebar-label">MENU UTAMA</div>
                <nav class="side-nav" aria-label="Navigasi utama">
                    <a class="side-link {{ request()->routeIs('admin.dashboard', 'user.dashboard') ? 'active' : '' }}" href="{{ route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard') }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
                        <span>Ringkasan</span>
                    </a>
                    @if (auth()->user()->role === 'admin')
                        <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M5 4.5h14a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18V6A1.5 1.5 0 0 1 5 4.5Z"/><path d="M7.5 9h9M7.5 12.5h9M7.5 16h5"/></svg>
                            <span>Semua pengaduan</span>
                        </a>
                        <a class="side-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M2.8 19a6.2 6.2 0 0 1 12.4 0M16 5.2a3.5 3.5 0 0 1 0 6.8M17.2 13.2a5.6 5.6 0 0 1 4 5.3"/></svg>
                            <span>Semua pengguna</span>
                        </a>
                    @else
                        <a class="side-link {{ request()->routeIs('user.complaints.create') ? 'active' : '' }}" href="{{ route('user.complaints.create') }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 5v14M5 12h14"/><circle cx="12" cy="12" r="9"/></svg>
                            <span>Buat pengaduan</span>
                        </a>
                        <a class="side-link {{ request()->routeIs('user.complaints.index') ? 'active' : '' }}" href="{{ route('user.complaints.index') }}">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M5 4.5h14a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18V6A1.5 1.5 0 0 1 5 4.5Z"/><path d="M7.5 9h9M7.5 12.5h9M7.5 16h5"/></svg>
                            <span>Riwayat pengaduan</span>
                        </a>
                    @endif
                </nav>

                <div class="sidebar-spacer"></div>
                <div class="sidebar-bottom">
                    <div class="profile">
                        <span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="profile-copy">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Akun masyarakat' }}</small>
                        </span>
                    </div>
                    <form class="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout-button" type="submit">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M10 4.5H5.5A1.5 1.5 0 0 0 4 6v12a1.5 1.5 0 0 0 1.5 1.5H10"/><path d="M14 8l4 4-4 4M18 12H9"/></svg>
                            <span>Keluar dari akun</span>
                        </button>
                    </form>
                </div>
            </aside>
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    @else
        <main class="guest-shell">
            <header class="guest-brand">
                <span class="brand-mark" aria-hidden="true">P</span>
                <h1>Portal Masyarakat</h1>
                <p>Layanan digital untuk masyarakat</p>
            </header>
            @yield('content')
        </main>
    @endauth
</body>
</html>
