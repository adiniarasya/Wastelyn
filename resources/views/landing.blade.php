{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WasteLyn — Platform Pembentukan Kebiasaan Pengelolaan Sampah</title>
    <meta name="description" content="Platform pembentukan kebiasaan pengelolaan sampah rumah tangga berbasis gamifikasi dan AI. Waste Mission, Eco Habit Score, Smart Waste Network, Bero AI, Reward System.">

    {{-- Bootstrap 5 (sesuai proposal: Blade + Bootstrap Mazer) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Poppins (sesuai proposal hal. 23) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --wl-green:      #2E7D32;
            --wl-green-dark: #1B5E20;
            --wl-green-light:#4CAF50;
            --wl-tech:       #1A237E;
            --wl-ink:        #1a2330;
            --wl-muted:      #6b7a8c;
            --wl-line:       #e6e9ee;
        }

        * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
        html { scroll-behavior: smooth; }
        body { color: var(--wl-ink); -webkit-font-smoothing: antialiased; }

        /* ============ Tipografi (proposal 7.1.B) ============ */
        h1, .h1 { font-size: 28px; font-weight: 700; line-height: 1.25; }
        h2, .h2 { font-size: 22px; font-weight: 600; }
        h3, .h3 { font-size: 18px; font-weight: 600; }
        @media (min-width: 992px) {
            h1, .h1 { font-size: 44px; }
            h2, .h2 { font-size: 32px; }
            h3, .h3 { font-size: 20px; }
        }
        .text-caption { font-size: 12px; color: var(--wl-muted); }
        .text-body-sm { font-size: 14px; }

        /* ============ Warna brand ============ */
        .text-wl-green { color: var(--wl-green) !important; }
        .text-wl-tech  { color: var(--wl-tech) !important; }
        .text-wl-muted { color: var(--wl-muted) !important; }
        .bg-wl-green   { background: var(--wl-green) !important; }
        .bg-wl-green-dark { background: var(--wl-green-dark) !important; }
        .bg-wl-soft    { background: #f6faf6 !important; }
        .bg-wl-tech-soft { background: rgba(26, 35, 126, 0.06) !important; }
        .border-wl     { border-color: var(--wl-line) !important; }

        /* ============ Navbar ============ */
        .wl-navbar {
            background: rgba(255,255,255,0.92);
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid transparent;
            transition: border-color .25s ease, box-shadow .25s ease;
        }
        .wl-navbar.scrolled {
            border-bottom-color: var(--wl-line);
            box-shadow: 0 2px 12px rgba(15,23,32,0.04);
        }
        .wl-navbar .nav-link {
            color: var(--wl-ink);
            font-size: 14px;
            font-weight: 500;
            padding: .5rem .9rem !important;
            border-radius: 8px;
        }
        .wl-navbar .nav-link:hover { color: var(--wl-green); background: #f3f8f3; }

        /* ============ Tombol ============ */
        .btn-wl-primary {
            background: var(--wl-green);
            color: #fff;
            border: 0;
            font-weight: 600;
            border-radius: 10px;
            padding: .65rem 1.4rem;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }
        .btn-wl-primary:hover {
            background: var(--wl-green-dark);
            color: #fff;
            box-shadow: 0 8px 20px rgba(46,125,50,0.25);
        }
        .btn-wl-outline {
            background: transparent;
            color: var(--wl-ink);
            border: 1px solid var(--wl-line);
            font-weight: 600;
            border-radius: 10px;
            padding: .65rem 1.4rem;
            transition: all .2s ease;
        }
        .btn-wl-outline:hover {
            border-color: var(--wl-green);
            color: var(--wl-green);
            background: #f3f8f3;
        }

        /* ============ Card (proposal 7.1.C: shadow + radius 12px) ============ */
        .wl-card {
            background: #fff;
            border: 1px solid var(--wl-line);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15,23,32,0.03);
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }
        .wl-card:hover {
            border-color: rgba(46,125,50,0.35);
            box-shadow: 0 10px 24px rgba(46,125,50,0.08);
            transform: translateY(-3px);
        }

        /* ============ Hero ============ */
        .hero-section {
            position: relative;
            background: #f8fbf8;
            overflow: hidden;
        }
        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(to right, rgba(15,23,32,0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15,23,32,0.035) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 75% 65% at 50% 40%, #000 45%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 75% 65% at 50% 40%, #000 45%, transparent 100%);
            pointer-events: none;
        }

        /* ============ Badge level (proposal hal. 3) ============ */
        .wl-badge {
            display: inline-block;
            padding: .3rem .8rem;
            font-size: 12px;
            font-weight: 600;
            border-radius: 999px;
            line-height: 1;
        }
        .lv-newbie   { background: #E8F5E9; color: #2E7D32; }
        .lv-explorer { background: #C8E6C9; color: #1B5E20; }
        .lv-warrior  { background: #A5D6A7; color: #1B5E20; }
        .lv-master   { background: #2E7D32; color: #fff; }
        .lv-legend   { background: linear-gradient(135deg, #2E7D32, #4CAF50); color: #fff; }

        /* ============ Progress bar ============ */
        .wl-progress { height: 8px; background: #eef1f4; border-radius: 999px; overflow: hidden; }
        .wl-progress > span {
            display: block; height: 100%;
            background: linear-gradient(90deg, var(--wl-green), var(--wl-green-light));
            border-radius: 999px;
            transition: width .6s ease;
        }

        /* ============ Divider & section ============ */
        .section { padding: 72px 0; }
        @media (min-width: 992px) { .section { padding: 100px 0; } }
        .section-divider { border-top: 1px solid var(--wl-line); }

        .eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--wl-green);
        }

        /* ============ Footer ============ */
        .wl-footer a { color: var(--wl-muted); text-decoration: none; font-size: 14px; }
        .wl-footer a:hover { color: var(--wl-green); }
        .wl-footer h6 {
            font-size: 12px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #94a1b2;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .wl-social {
            width: 36px; height: 36px;
            display: inline-grid; place-items: center;
            border-radius: 8px;
            border: 1px solid var(--wl-line);
            color: var(--wl-muted);
            transition: all .2s ease;
        }
        .wl-social:hover {
            color: var(--wl-green);
            border-color: var(--wl-green);
            background: #f3f8f3;
        }

        /* ============ Kartu dashboard mockup (proposal hal. 41) ============ */
        .wl-mock-stat {
            text-align: center;
            padding: 1rem .5rem;
        }
        .wl-mock-stat .n {
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
            color: var(--wl-ink);
        }
        .wl-mock-stat .l {
            font-size: 12px;
            color: var(--wl-muted);
            margin-top: .35rem;
        }

        ::selection { background: var(--wl-green); color: #fff; }
    </style>
</head>
<body>

{{-- ==================== NAVBAR ==================== --}}
<nav id="navbar" class="navbar navbar-expand-lg fixed-top wl-navbar py-2">
    <div class="container" style="max-width: 1180px;">
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center gap-2 fw-bold">
            <span class="d-inline-grid bg-wl-green text-white rounded-2"
                  style="width:34px;height:34px;place-items:center;font-weight:800;">W</span>
            <span style="font-weight:800; letter-spacing:-.02em;">WasteLyn</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#wlNavMenu"
                aria-controls="wlNavMenu" aria-expanded="false" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="wlNavMenu">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="#fitur"  class="nav-link">Fitur</a></li>
                <li class="nav-item"><a href="#level"  class="nav-link">Level</a></li>
                <li class="nav-item"><a href="#cara"   class="nav-link">Cara Kerja</a></li>
                <li class="nav-item"><a href="#kontak" class="nav-link">Kontak</a></li>
            </ul>
            <div class="d-flex flex-column flex-lg-row gap-2">
                <a href="{{ route('login') }}" class="btn btn-wl-outline btn-sm px-3 py-2">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-wl-primary btn-sm px-3 py-2">Daftar Gratis</a>
            </div>
        </div>
    </div>
</nav>

{{-- ==================== HERO ==================== --}}
<section class="hero-section pt-5" style="padding-top: 130px !important; padding-bottom: 80px;">
    <div class="hero-grid"></div>

    <div class="container position-relative" style="max-width: 1180px;">
        <div class="row align-items-center g-5">

            {{-- Kiri --}}
            <div class="col-lg-7">
                <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill small fw-semibold"
                      style="background:#E8F5E9;color:var(--wl-green);">
                    <span class="d-inline-block rounded-circle bg-wl-green" style="width:6px;height:6px;"></span>
                    Platform Pembentukan Kebiasaan
                </span>

                <h1 class="mt-3 mb-3" style="letter-spacing:-.02em;">
                    Ubah sampah jadi <span class="text-wl-green">kebiasaan baik</span>,
                    bukan sekadar aksi sekali.
                </h1>

                <p class="mb-4" style="max-width: 560px; color: var(--wl-muted); line-height: 1.7;">
                    WasteLyn adalah platform pembentukan kebiasaan pengelolaan sampah rumah tangga
                    berbasis <strong class="text-wl-tech">gamifikasi</strong> dan
                    <strong class="text-wl-tech">kecerdasan buatan</strong>. Lima fitur utama yang saling
                    terintegrasi: Waste Mission, Eco Habit Score, Smart Waste Network, Bero AI, dan Reward System.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('register') }}" class="btn btn-wl-primary d-inline-flex align-items-center gap-2">
                        Mulai Gratis <i class="fas fa-arrow-right small"></i>
                    </a>
                    <a href="#fitur" class="btn btn-wl-outline d-inline-flex align-items-center gap-2">
                        <i class="fas fa-circle-play text-wl-green"></i> Lihat Fitur
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3 pt-3 border-top border-wl">
                    <div class="d-flex" style="margin-left:-6px;">
                        <span class="d-grid rounded-circle bg-wl-green text-white fw-bold"
                              style="width:34px;height:34px;place-items:center;font-size:12px;border:2px solid #fff;margin-left:-6px;">A</span>
                        <span class="d-grid rounded-circle fw-bold"
                              style="width:34px;height:34px;place-items:center;font-size:12px;background:#FFF3E0;color:#E65100;border:2px solid #fff;margin-left:-6px;">B</span>
                        <span class="d-grid rounded-circle fw-bold"
                              style="width:34px;height:34px;place-items:center;font-size:12px;background:#E3F2FD;color:#0D47A1;border:2px solid #fff;margin-left:-6px;">C</span>
                        <span class="d-grid rounded-circle fw-bold text-wl-muted"
                              style="width:34px;height:34px;place-items:center;font-size:12px;background:#eef1f4;border:2px solid #fff;margin-left:-6px;">+</span>
                    </div>
                    <div class="small">
                        <div class="fw-bold">1.200+ pengguna aktif</div>
                        <div class="text-wl-muted" style="font-size:12px;">di Kota Depok & sekitarnya</div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Kartu Dashboard (meniru mock-up proposal hal. 41) --}}
            <div class="col-lg-5">
                <div class="wl-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="eyebrow">Eco Habit Score</div>
                            <div class="h3 mt-1 mb-0 fw-bolder" style="font-size:28px;">850</div>
                            <div class="text-caption">/ 1000 XP</div>
                        </div>
                        <span class="wl-badge lv-warrior">
                            <i class="fas fa-seedling me-1"></i> Green Warrior
                        </span>
                    </div>

                    <div class="wl-progress mb-2"><span style="width:85%;"></span></div>
                    <div class="d-flex justify-content-between text-caption mb-3">
                        <span>Level 3 → Level 4</span>
                        <span class="fw-semibold" style="color:var(--wl-green);">85%</span>
                    </div>

                    <div class="row g-0 rounded-3 border border-wl mb-3">
                        <div class="col-3 wl-mock-stat border-end border-wl">
                            <div class="n">1250</div><div class="l">Poin</div>
                        </div>
                        <div class="col-3 wl-mock-stat border-end border-wl">
                            <div class="n">7</div><div class="l">Streak</div>
                        </div>
                        <div class="col-3 wl-mock-stat border-end border-wl">
                            <div class="n">15,5</div><div class="l">Kg</div>
                        </div>
                        <div class="col-3 wl-mock-stat">
                            <div class="n">8,2</div><div class="l">CO₂</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-wl-soft">
                        <span class="d-grid rounded-2 bg-white border border-wl text-wl-green"
                              style="width:38px;height:38px;place-items:center;">
                            <i class="fas fa-robot"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold" style="font-size:14px;">Bero AI</span>
                                <span class="wl-badge" style="background:var(--wl-tech);color:#fff;font-size:10px;">AI</span>
                            </div>
                            <div class="text-body-sm text-wl-muted">
                                "Botol saus termasuk plastik HDPE. Bisa didaur ulang jadi pot tanaman."
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ==================== TRUST / MITRA ==================== --}}
<div class="section-divider" style="background:#fafbfc;">
    <div class="container py-4" style="max-width: 1180px;">
        <p class="text-center text-caption fw-bold mb-3" style="letter-spacing:.15em; text-transform:uppercase;">
            Terhubung dengan ekosistem pengelolaan sampah
        </p>
        <div class="row align-items-center justify-content-center text-center g-3">
            <div class="col-6 col-md-3"><div class="fw-bold text-wl-muted">Bank Sampah Depok</div></div>
            <div class="col-6 col-md-3"><div class="fw-bold text-wl-muted">DLH Kota Depok</div></div>
            <div class="col-6 col-md-3"><div class="fw-bold text-wl-muted">Komunitas Peduli Lingkungan</div></div>
            <div class="col-6 col-md-3"><div class="fw-bold text-wl-muted">TPS 3R</div></div>
        </div>
    </div>
</div>

{{-- ==================== FITUR (5 Fitur Utama) ==================== --}}
<section id="fitur" class="section">
    <div class="container" style="max-width: 1180px;">
        <div class="row mb-5">
            <div class="col-lg-7">
                <div class="eyebrow">Fitur Utama</div>
                <h2 class="mt-2 mb-3" style="letter-spacing:-.02em;">
                    Lima fitur yang saling terintegrasi.
                </h2>
                <p class="text-wl-muted mb-0" style="line-height:1.7;">
                    Dirancang bukan hanya untuk transaksi setoran, tetapi untuk membentuk
                    kebiasaan pengelolaan sampah rumah tangga secara berkelanjutan.
                </p>
            </div>
        </div>

        @php
            $features = [
                ['fa-bullseye', 'Waste Mission',
                 'Misi lingkungan yang dirancang untuk membentuk kebiasaan baik secara bertahap dengan target dan batas waktu.'],
                ['fa-chart-line', 'Eco Habit Score',
                 'Sistem XP yang mencerminkan kebiasaan ramah lingkungan jangka panjang. Hanya naik, tidak pernah turun.'],
                ['fa-map-location-dot', 'Smart Waste Network',
                 'Menghubungkan pengguna dengan ekosistem pengelolaan sampah — cari bank sampah, ajukan penjemputan.'],
                ['fa-robot', 'Bero AI',
                 'Asisten cerdas untuk edukasi, klasifikasi jenis sampah, dan rekomendasi misi personal.'],
                ['fa-gift', 'Reward System',
                 'Tukarkan poin menjadi pulsa, voucher, bibit tanaman, atau merchandise eksklusif.'],
                ['fa-gem', 'Dual Reward System',
                 'XP untuk motivasi jangka panjang, Poin untuk hadiah instan. Kombinasi menjaga motivasi tetap seimbang.'],
            ];
        @endphp

        <div class="row g-3 g-lg-4">
            @foreach ($features as $f)
                <div class="col-md-6 col-lg-4">
                    <div class="wl-card h-100 p-4">
                        <span class="d-inline-grid rounded-3 mb-3 {{ $loop->index % 2 ? 'bg-wl-tech-soft text-wl-tech' : 'bg-wl-soft text-wl-green' }}"
                              style="width:44px;height:44px;place-items:center;">
                            <i class="fas {{ $f[0] }}"></i>
                        </span>
                        <h3 class="h3 mb-2" style="font-size:17px;">{{ $f[1] }}</h3>
                        <p class="text-body-sm text-wl-muted mb-0" style="line-height:1.65;">
                            {{ $f[2] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== LEVEL (Eco Habit Score) ==================== --}}
<section id="level" class="section bg-wl-soft section-divider" style="border-bottom:1px solid var(--wl-line);">
    <div class="container" style="max-width: 1180px;">
        <div class="row mb-5">
            <div class="col-lg-7">
                <div class="eyebrow">Eco Habit Score</div>
                <h2 class="mt-2 mb-3" style="letter-spacing:-.02em;">
                    Naik level, buktikan kebiasaanmu.
                </h2>
                <p class="text-wl-muted mb-0" style="line-height:1.7;">
                    XP permanen sebagai investasi psikologis — kamu tidak akan kehilangan
                    level yang sudah dicapai. Setiap aksi ramah lingkungan menambah XP.
                </p>
            </div>
        </div>

        @php
            $levels = [
                ['fa-seedling',    'Green Newbie',   '0 – 200 XP',    'lv-newbie'],
                ['fa-leaf',        'Green Explorer', '201 – 500 XP',  'lv-explorer'],
                ['fa-tree',        'Green Warrior',  '501 – 800 XP',  'lv-warrior'],
                ['fa-trophy',      'Green Master',   '801 – 1000 XP', 'lv-master'],
                ['fa-crown',       'Eco Legend',     '1000+ XP',      'lv-legend'],
            ];
        @endphp

        <div class="row g-3">
            @foreach ($levels as $l)
                <div class="col-6 col-md-4 col-lg">
                    <div class="wl-card text-center h-100 p-4">
                        <i class="fas {{ $l[0] }} fs-3 text-wl-green mb-3 d-block"></i>
                        <span class="wl-badge {{ $l[3] }} mb-2">{{ $l[1] }}</span>
                        <div class="text-caption">{{ $l[2] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Perolehan XP (proposal hal. 18) --}}
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="wl-card p-4">
                    <h3 class="h3 mb-3" style="font-size:16px;">
                        <i class="fas fa-plus-circle text-wl-green me-2"></i> Perolehan XP
                    </h3>
                    <div class="row g-3">
                        @php
                            $xpRules = [
                                ['Setor sampah', '+20 XP / kg'],
                                ['Menyelesaikan misi', '+10 XP'],
                                ['Konsisten 4 minggu', '+50 XP bonus'],
                                ['Membaca artikel EcoTips', '+5 XP'],
                                ['Mengikuti tantangan', '+15 XP'],
                            ];
                        @endphp
                        @foreach ($xpRules as $r)
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border border-wl">
                                    <span class="text-body-sm">{{ $r[0] }}</span>
                                    <span class="fw-bold text-wl-green text-body-sm">{{ $r[1] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== CARA KERJA ==================== --}}
<section id="cara" class="section">
    <div class="container" style="max-width: 1180px;">
        <div class="row mb-5">
            <div class="col-lg-7">
                <div class="eyebrow">Cara Kerja</div>
                <h2 class="mt-2 mb-3" style="letter-spacing:-.02em;">
                    Tiga langkah. Satu kebiasaan baru.
                </h2>
                <p class="text-wl-muted mb-0" style="line-height:1.7;">
                    Mengadopsi siklus <em>cue–routine–reward</em> dari riset pembentukan kebiasaan
                    (<em>The Power of Habit</em>, Duhigg) ke dalam alur aplikasi.
                </p>
            </div>
        </div>

        @php
            $steps = [
                ['01', 'Ikuti Misi', 'Pilih misi lingkungan yang tersedia sesuai level Eco Habit Score kamu. Mulai dari yang paling mudah.'],
                ['02', 'Kumpulkan XP & Poin', 'Setor sampah, selesaikan misi, dan konsisten setiap minggu. XP akan terus bertambah.'],
                ['03', 'Nikmati Hasil', 'Tukar poin di Reward Center, pantau progres di Eco Habit Score, dan rasakan dampaknya bagi lingkungan.'],
            ];
        @endphp

        <div class="row g-3 g-lg-4">
            @foreach ($steps as $s)
                <div class="col-md-4">
                    <div class="wl-card h-100 p-4">
                        <div style="font-size:38px;font-weight:800;color:#dcf0de;line-height:1;">{{ $s[0] }}</div>
                        <h3 class="h3 mt-2 mb-2" style="font-size:17px;">{{ $s[1] }}</h3>
                        <p class="text-body-sm text-wl-muted mb-0" style="line-height:1.65;">{{ $s[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <a href="{{ route('register') }}" class="btn btn-wl-primary d-inline-flex align-items-center gap-2">
                Gabung Sekarang <i class="fas fa-arrow-right small"></i>
            </a>
        </div>
    </div>
</section>

{{-- ==================== STATISTIK ==================== --}}
<section class="py-5" style="background: var(--wl-ink); color:#fff;">
    <div class="container" style="max-width: 1180px;">
        <div class="row text-center g-4">
            @php
                $stats = [
                    ['50+',   'Bank Sampah Mitra'],
                    ['1.2K',  'Pengguna Aktif'],
                    ['8.5K',  'Kg Sampah Didaur Ulang'],
                    ['95%',   'Kepuasan Pengguna'],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="col-6 col-md-3">
                    <div style="font-size:38px;font-weight:800;letter-spacing:-.02em;">{{ $s[0] }}</div>
                    <div class="small mt-1" style="color: rgba(255,255,255,.65);">{{ $s[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== CTA ==================== --}}
<section class="section">
    <div class="container" style="max-width: 1180px;">
        <div class="position-relative overflow-hidden rounded-4 p-4 p-lg-5" style="background: var(--wl-green);">
            <div class="position-absolute rounded-circle" style="width:260px;height:260px;top:-100px;right:-100px;background:rgba(255,255,255,.08);"></div>
            <div class="position-absolute rounded-circle" style="width:220px;height:220px;bottom:-100px;left:-80px;background:rgba(255,255,255,.08);"></div>

            <div class="row position-relative align-items-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-3" style="letter-spacing:-.02em;">
                        Siap memulai perubahan?
                    </h2>
                    <p class="mb-4" style="color: rgba(255,255,255,.85); max-width: 560px; line-height:1.7;">
                        Bergabunglah dengan masyarakat Kota Depok yang telah membentuk kebiasaan
                        mengelola sampah rumah tangga bersama WasteLyn.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('register') }}" class="btn bg-white text-wl-green fw-semibold px-4 py-2 rounded-3">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn text-white fw-semibold px-4 py-2 rounded-3"
                           style="border:1px solid rgba(255,255,255,.45);">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== FOOTER ==================== --}}
<footer id="kontak" class="wl-footer section-divider">
    <div class="container py-5" style="max-width: 1180px;">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-grid bg-wl-green text-white rounded-2"
                          style="width:34px;height:34px;place-items:center;font-weight:800;">W</span>
                    <span class="fw-bold" style="font-size:18px; letter-spacing:-.02em;">WasteLyn</span>
                </div>
                <p class="text-body-sm text-wl-muted mb-3" style="max-width: 320px; line-height: 1.7;">
                    Platform pembentukan kebiasaan pengelolaan sampah rumah tangga berbasis
                    gamifikasi dan kecerdasan buatan. Dikembangkan untuk Kota Depok.
                </p>
                <div class="d-flex gap-2">
                    @foreach (['instagram','youtube','tiktok','twitter'] as $icon)
                        <a href="#" class="wl-social"><i class="fab fa-{{ $icon }}"></i></a>
                    @endforeach
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6>Fitur</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                    <li><a href="#fitur">Waste Mission</a></li>
                    <li><a href="#fitur">Eco Habit Score</a></li>
                    <li><a href="#fitur">Smart Waste Network</a></li>
                    <li><a href="#fitur">Bero AI</a></li>
                    <li><a href="#fitur">Reward System</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6>Perusahaan</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Tim</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6>Kontak</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 text-body-sm mb-0">
                    <li><i class="fas fa-envelope text-wl-muted me-2"></i> si.wastelyn@gmail.com</li>
                    <li><i class="fas fa-phone text-wl-muted me-2"></i> +62 812 3456 7890</li>
                    <li><i class="fas fa-map-marker-alt text-wl-muted me-2"></i> Kota Depok, Jawa Barat</li>
                </ul>
                <div class="text-caption mt-3">
                    <i class="fas fa-school me-1"></i> SMK Informatika Utama — Tim UtamainMenang
                </div>
            </div>
        </div>

        <hr class="my-4 border-wl">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-caption">
            <span>&copy; {{ date('Y') }} WasteLyn. Seluruh hak cipta dilindungi.</span>
            <span>Dibuat dengan <span class="text-wl-green">♥</span> untuk lingkungan yang lebih baik.</span>
        </div>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Navbar scroll shadow
    (function () {
        const nav = document.getElementById('navbar');
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // Close mobile menu on link click
    document.querySelectorAll('#wlNavMenu a.nav-link').forEach(a => {
        a.addEventListener('click', () => {
            const el = document.getElementById('wlNavMenu');
            const c = bootstrap.Collapse.getInstance(el) || new bootstrap.Collapse(el, { toggle: false });
            c.hide();
        });
    });
</script>
</body>
</html>