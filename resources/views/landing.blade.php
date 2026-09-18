<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo.jpg') }}">
    <title>WasteLyn — Platform Pembentukan Kebiasaan Pengelolaan Sampah</title>
    <meta name="description"
        content="Platform pembentukan kebiasaan pengelolaan sampah rumah tangga berbasis gamifikasi dan AI. Waste Mission, Eco Habit Score, Smart Waste Network, Bero AI, Reward System.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        :root {
            --wl-green: #2E7D32;
            --wl-green-dark: #1B5E20;
            --wl-green-light: #4CAF50;
            --wl-tech: #1A237E;
            --wl-ink: #1a2330;
            --wl-muted: #6b7a8c;
            --wl-line: #e6e9ee;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            color: var(--wl-ink);
            -webkit-font-smoothing: antialiased;
        }

        .text-caption {
            font-size: 12px;
            color: var(--wl-muted);
        }

        .text-body-sm {
            font-size: 14px;
        }

        .text-wl-green {
            color: var(--wl-green) !important;
        }

        .text-wl-tech {
            color: var(--wl-tech) !important;
        }

        .text-wl-muted {
            color: var(--wl-muted) !important;
        }

        .bg-wl-green {
            background: var(--wl-green) !important;
        }

        .bg-wl-green-dark {
            background: var(--wl-green-dark) !important;
        }

        .bg-wl-soft {
            background: #f6faf6 !important;
        }

        .bg-wl-tech-soft {
            background: rgba(26, 35, 126, 0.06) !important;
        }

        .border-wl {
            border-color: var(--wl-line) !important;
        }

        .btn-wl-primary {
            background: var(--wl-green);
            color: #fff;
            border: 0;
            font-weight: 600;
            border-radius: 999px;
            padding: .65rem 1.4rem;
            transition: background .2s ease, box-shadow .2s ease;
        }

        .btn-wl-primary:hover {
            background: var(--wl-green-dark);
            color: #fff;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.25);
        }

        .btn-wl-outline {
            background: transparent;
            color: var(--wl-ink);
            border: 1px solid var(--wl-line);
            font-weight: 600;
            border-radius: 999px;
            padding: .65rem 1.4rem;
            transition: all .2s ease;
        }

        .btn-wl-outline:hover {
            border-color: var(--wl-green);
            color: var(--wl-green);
            background: #f3f8f3;
        }

        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .7);
            font-weight: 600;
            border-radius: 10px;
            padding: .7rem 1.4rem;
            transition: all .2s ease;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, .12);
            color: #fff;
            border-color: #fff;
        }

        .wl-badge {
            display: inline-block;
            padding: .3rem .8rem;
            font-size: 12px;
            font-weight: 600;
            border-radius: 999px;
            line-height: 1;
        }

        .lv-newbie {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .lv-explorer {
            background: #C8E6C9;
            color: #1B5E20;
        }

        .lv-warrior {
            background: #A5D6A7;
            color: #1B5E20;
        }

        .lv-master {
            background: #2E7D32;
            color: #fff;
        }

        .lv-legend {
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            color: #fff;
        }

        .wl-progress {
            height: 8px;
            background: #eef1f4;
            border-radius: 999px;
            overflow: hidden;
        }

        .wl-progress>span {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, var(--wl-green), var(--wl-green-light));
            border-radius: 999px;
            transition: width .6s ease;
        }

        .section {
            padding: 72px 0;
        }

        @media (min-width: 992px) {
            .section {
                padding: 100px 0;
            }
        }

        .section-divider {
            border-top: 1px solid var(--wl-line);
        }

        .eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--wl-green);
        }

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

        .swiper-slide {
            height: auto;
        }

        .fiturSwiper,
        .levelSwiper {
            padding: 10px 5px;
            overflow: hidden;
        }

        .wl-footer a {
            color: rgba(255, 255, 255, .5);
            text-decoration: none;
            font-size: 14px;
        }

        .wl-footer a:hover {
            color: var(--wl-green-light);
        }

        .hero-banner {
            position: relative;
            height: 560px;
            overflow: hidden;
            margin-top: 76px;
        }

        .hero-banner img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-banner .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, .75) 0%, rgba(0, 0, 0, .35) 60%, rgba(0, 0, 0, .15) 100%);
        }

        .hero-banner .content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            align-items: center;
            color: #fff;
        }

        .hero-banner h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -.02em;
        }

        @media (max-width: 768px) {
            .hero-banner {
                height: 520px;
                margin-top: 68px;
            }

            .hero-banner h1 {
                font-size: 34px;
            }
        }

        .wl-navbar {
            background: #fff;
            border-bottom: 1px solid var(--wl-line);
            padding-top: .9rem;
            padding-bottom: .9rem;
            transition: box-shadow .25s ease;
        }

        .wl-navbar.scrolled {
            box-shadow: 0 2px 12px rgba(15, 23, 32, .06);
        }

        .wl-brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
        }

        .wl-brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .wl-brand-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .wl-brand-text {
            line-height: 1.1;
        }

        .wl-brand-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--wl-ink);
            letter-spacing: -.02em;
        }

        .wl-brand-tagline {
            font-size: 11px;
            color: var(--wl-green);
            font-weight: 600;
        }

        .wl-nav-link {
            color: var(--wl-ink);
            font-weight: 600;
            font-size: 14px;
            padding: .5rem .9rem;
            text-decoration: none;
            transition: color .2s ease;
        }

        .wl-nav-link:hover {
            color: var(--wl-green);
        }

        .wl-nav-link.active {
            color: var(--wl-green);
            font-weight: 700;
        }

        .wl-cta-btn {
            background: var(--wl-green);
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: .7rem 1.5rem;
            border: 0;
            transition: background .2s ease, box-shadow .2s ease;
        }

        .wl-cta-btn:hover {
            background: var(--wl-green-dark);
            color: #fff;
            box-shadow: 0 8px 20px rgba(46, 125, 50, .3);
        }

        .tentang-visual {
            position: relative;
            min-height: 320px;
        }

        .tentang-visual .circle-big {
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: #d7ecd9;
            top: 0;
            left: 0;
        }

        .tentang-visual .circle-small {
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: var(--wl-green);
            bottom: 0;
            left: 70px;
        }

        .tentang-visual .tentang-logo {
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
            display: grid;
            place-items: center;
            bottom: 60px;
            left: 180px;
        }

        .tentang-visual .tentang-logo img {
            max-width: 80px;
        }

        ::selection {
            background: var(--wl-green);
            color: #fff;
        }
    </style>
</head>

<body class="bg-light">

    <nav id="wlNavbar" class="navbar navbar-expand-lg wl-navbar fixed-top">
        <div class="container">

            <a class="wl-brand" href="{{ url('/') }}">
                <span class="wl-brand-logo">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo WasteLyn">
                </span>
                <span class="wl-brand-text">
                    <span class="wl-brand-title d-block">WasteLyn</span>
                    <span class="wl-brand-tagline d-block">Kurangi Sampah, Tambah Manfaat</span>
                </span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#wlNavMenu">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="wlNavMenu">
                <ul class="navbar-nav mx-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="wl-nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="wl-nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="wl-nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="wl-nav-link" href="#level">Level</a></li>
                    <li class="nav-item"><a class="wl-nav-link" href="#kontak">Kontak</a></li>
                </ul>
                <div class="d-flex flex-column flex-lg-row gap-2">
                    <a href="{{ route('login') }}" class="btn btn-wl-outline btn-sm px-3 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="wl-cta-btn">Daftar Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="hero-banner">
        <img src="{{ asset('storage/banner.jpg') }}" alt="Banner WasteLyn">
        <div class="overlay"></div>
        <div class="content">
            <div class="container">
                <div style="max-width: 640px;">
                    <h1 class="mb-3">
                        Ubah sampah jadi kebiasaan baik, bukan sekadar aksi sekali.
                    </h1>
                    <p class="lead mb-4" style="color: rgba(255,255,255,.9); line-height: 1.6;">
                        WasteLyn adalah platform pembentukan kebiasaan pengelolaan sampah rumah tangga
                        berbasis gamifikasi dan kecerdasan buatan.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('register') }}" class="btn btn-wl-primary">
                            Mulai Gratis <i class="fas fa-arrow-right small ms-1"></i>
                        </a>
                        <a href="#fitur" class="btn btn-hero-outline">
                            <i class="fas fa-circle-play me-1"></i> Lihat Fitur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6">
                <div class="card border shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-recycle fa-3x text-wl-green mb-3"></i>
                        <h2 class="fw-bold mb-0">50+</h2>
                        <p class="text-muted mb-0">Bank Sampah Mitra</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card border shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-users fa-3x text-wl-green mb-3"></i>
                        <h2 class="fw-bold mb-0">1.2K</h2>
                        <p class="text-muted mb-0">Pengguna Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card border shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-leaf fa-3x text-wl-green mb-3"></i>
                        <h2 class="fw-bold mb-0">8.5K</h2>
                        <p class="text-muted mb-0">Kg Sampah Didaur Ulang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="tentang" class="section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="tentang-visual">
                        <div class="circle-big"></div>
                        <div class="circle-small"></div>
                        <div class="tentang-logo">
                            <img src="{{ asset('assets/logo.PNG') }}" alt="Logo WasteLyn">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <span class="wl-badge bg-wl-soft text-wl-green mb-3">
                        <i class="fas fa-leaf me-1"></i> Tentang Kami
                    </span>
                    <h2 class="fw-bold mb-3" style="letter-spacing:-.02em;">WasteLyn</h2>
                    <p class="text-muted mb-3" style="line-height:1.8;">
                        WasteLyn adalah platform pembentukan kebiasaan pengelolaan sampah rumah tangga
                        berbasis gamifikasi dan kecerdasan buatan. Kami percaya bahwa perubahan besar
                        dimulai dari kebiasaan kecil yang dilakukan secara konsisten.
                    </p>
                    <p class="text-muted mb-4" style="line-height:1.8;">
                        Melalui lima fitur utama — Waste Mission, Eco Habit Score, Smart Waste Network,
                        Bero AI, dan Reward System — WasteLyn membantu pengguna mengubah aksi sekali
                        menjadi kebiasaan jangka panjang yang berdampak nyata bagi lingkungan.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border">
                                <i class="fas fa-bullseye text-wl-green"></i>
                                <span class="small fw-semibold">Misi lingkungan bertahap</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border">
                                <i class="fas fa-chart-line text-wl-green"></i>
                                <span class="small fw-semibold">Eco Habit Score permanen</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border">
                                <i class="fas fa-robot text-wl-green"></i>
                                <span class="small fw-semibold">Didukung Bero AI</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border">
                                <i class="fas fa-gift text-wl-green"></i>
                                <span class="small fw-semibold">Reward yang nyata</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="eyebrow">Eco Habit Score</div>
                                <div class="mt-1 mb-0 fw-bolder" style="font-size:28px;">850</div>
                                <div class="text-caption">/ 1000 XP</div>
                            </div>
                            <span class="wl-badge lv-warrior">
                                <i class="fas fa-seedling me-1"></i> Green Warrior
                            </span>
                        </div>

                        <div class="wl-progress mb-2"><span style="width:85%;"></span></div>
                        <div class="d-flex justify-content-between text-caption mb-3">
                            <span>Level 3 → Level 4</span>
                            <span class="fw-semibold text-wl-green">85%</span>
                        </div>

                        <div class="row g-0 rounded-3 border border-wl mb-3">
                            <div class="col-3 wl-mock-stat border-end border-wl">
                                <div class="n">1250</div>
                                <div class="l">Poin</div>
                            </div>
                            <div class="col-3 wl-mock-stat border-end border-wl">
                                <div class="n">7</div>
                                <div class="l">Streak</div>
                            </div>
                            <div class="col-3 wl-mock-stat border-end border-wl">
                                <div class="n">15,5</div>
                                <div class="l">Kg</div>
                            </div>
                            <div class="col-3 wl-mock-stat">
                                <div class="n">8,2</div>
                                <div class="l">CO₂</div>
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
                                    <span class="wl-badge"
                                        style="background:var(--wl-tech);color:#fff;font-size:10px;">AI</span>
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
    </div>

    <section id="fitur" class="section">
        <div class="container">
            <div class="border-bottom pb-3 mb-4 text-center">
                <div class="eyebrow">Fitur Utama</div>
                <h2 class="fw-bold mt-2 mb-0">Lima fitur yang saling terintegrasi.</h2>
                <p class="text-muted mt-2 mb-0">
                    Dirancang bukan hanya untuk transaksi setoran, tetapi untuk membentuk
                    kebiasaan pengelolaan sampah rumah tangga secara berkelanjutan.
                </p>
            </div>

            @php
                $features = [
                    ['fa-bullseye', 'Waste Mission', 'Misi lingkungan yang dirancang untuk membentuk kebiasaan baik secara bertahap dengan target dan batas waktu.'],
                    ['fa-chart-line', 'Eco Habit Score', 'Sistem XP yang mencerminkan kebiasaan ramah lingkungan jangka panjang. Hanya naik, tidak pernah turun.'],
                    ['fa-map-location-dot', 'Smart Waste Network', 'Menghubungkan pengguna dengan ekosistem pengelolaan sampah — cari bank sampah, ajukan penjemputan.'],
                    ['fa-robot', 'Bero AI', 'Asisten cerdas untuk edukasi, klasifikasi jenis sampah, dan rekomendasi misi personal.'],
                    ['fa-gift', 'Reward System', 'Tukarkan poin menjadi pulsa, voucher, bibit tanaman, atau merchandise eksklusif.'],
                    ['fa-gem', 'Dual Reward System', 'XP untuk motivasi jangka panjang, Poin untuk hadiah instan. Kombinasi menjaga motivasi tetap seimbang.'],
                ];
            @endphp

            <div class="swiper fiturSwiper">
                <div class="swiper-wrapper">
                    @foreach ($features as $f)
                        <div class="swiper-slide">
                            <div class="card h-100 shadow-sm border text-center">
                                <div class="card-body py-4">
                                    <span class="d-inline-grid rounded-3 mb-3 bg-wl-soft text-wl-green"
                                        style="width:52px;height:52px;place-items:center;">
                                        <i class="fas {{ $f[0] }} fs-4"></i>
                                    </span>
                                    <h6 class="fw-bold mb-2">{{ $f[1] }}</h6>
                                    <p class="small text-muted mb-0">{{ $f[2] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="level" class="section bg-light border-top">
        <div class="container">
            <div class="border-bottom pb-3 mb-4 text-center">
                <div class="eyebrow">Eco Habit Score</div>
                <h2 class="fw-bold mt-2 mb-0">Naik level, buktikan kebiasaanmu.</h2>
                <p class="text-muted mt-2 mb-0">
                    XP permanen sebagai investasi psikologis — kamu tidak akan kehilangan
                    level yang sudah dicapai. Setiap aksi ramah lingkungan menambah XP.
                </p>
            </div>

            @php
                $levels = [
                    ['fa-seedling', 'Green Newbie', '0 – 200 XP', 'lv-newbie'],
                    ['fa-leaf', 'Green Explorer', '201 – 500 XP', 'lv-explorer'],
                    ['fa-tree', 'Green Warrior', '501 – 800 XP', 'lv-warrior'],
                    ['fa-trophy', 'Green Master', '801 – 1000 XP', 'lv-master'],
                    ['fa-crown', 'Eco Legend', '1000+ XP', 'lv-legend'],
                ];
            @endphp

            <div class="swiper levelSwiper">
                <div class="swiper-wrapper">
                    @foreach ($levels as $l)
                        <div class="swiper-slide">
                            <div class="card h-100 shadow-sm border text-center">
                                <div class="card-body py-4">
                                    <i class="fas {{ $l[0] }} fs-3 text-wl-green mb-3 d-block"></i>
                                    <span class="wl-badge {{ $l[3] }} mb-2">{{ $l[1] }}</span>
                                    <div class="text-caption">{{ $l[2] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-10 mx-auto">
                    <div class="card shadow-sm border">
                        <div class="card-body p-4">
                            <h3 class="h6 fw-bold mb-3">
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
                                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border">
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
        </div>
    </section>

    <section id="cara" class="section">
        <div class="container">
            <div class="border-bottom pb-3 mb-4 text-center">
                <div class="eyebrow">Cara Kerja</div>
                <h2 class="fw-bold mt-2 mb-0">Tiga langkah. Satu kebiasaan baru.</h2>
                <p class="text-muted mt-2 mb-0">
                    Mengadopsi siklus <em>cue–routine–reward</em> dari riset pembentukan kebiasaan
                    (<em>The Power of Habit</em>, Duhigg) ke dalam alur aplikasi.
                </p>
            </div>

            @php
                $steps = [
                    ['01', 'Ikuti Misi', 'Pilih misi lingkungan yang tersedia sesuai level Eco Habit Score kamu. Mulai dari yang paling mudah.'],
                    ['02', 'Kumpulkan XP & Poin', 'Setor sampah, selesaikan misi, dan konsisten setiap minggu. XP akan terus bertambah.'],
                    ['03', 'Nikmati Hasil', 'Tukar poin di Reward Center, pantau progres di Eco Habit Score, dan rasakan dampaknya bagi lingkungan.'],
                ];
            @endphp

            <div class="row g-4">
                @foreach ($steps as $s)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-body p-4">
                                <div style="font-size:38px;font-weight:800;color:#c8e6c9;line-height:1;">{{ $s[0] }}</div>
                                <h3 class="h6 fw-bold mt-2 mb-2">{{ $s[1] }}</h3>
                                <p class="small text-muted mb-0">{{ $s[2] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="btn btn-wl-primary">
                    Gabung Sekarang <i class="fas fa-arrow-right small ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="position-relative overflow-hidden rounded-4 p-4 p-lg-5 bg-wl-green">
                <div class="position-absolute rounded-circle"
                    style="width:260px;height:260px;top:-100px;right:-100px;background:rgba(255,255,255,.08);"></div>
                <div class="position-absolute rounded-circle"
                    style="width:220px;height:220px;bottom:-100px;left:-80px;background:rgba(255,255,255,.08);"></div>

                <div class="row position-relative align-items-center">
                    <div class="col-lg-8">
                        <h2 class="text-white mb-3">Siap memulai perubahan?</h2>
                        <p class="mb-4" style="color: rgba(255,255,255,.85); max-width: 560px;">
                            Bergabunglah dengan masyarakat Kota Depok yang telah membentuk kebiasaan
                            mengelola sampah rumah tangga bersama WasteLyn.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('register') }}"
                                class="btn bg-white text-wl-green fw-semibold px-4 py-2 rounded-pill">
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="btn text-white fw-semibold px-4 py-2 rounded-pill"
                                style="border:1px solid rgba(255,255,255,.45);">
                                Masuk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-dark text-white-50 mt-5 pt-5 pb-4 wl-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/logo.jpg') }}" alt="Logo WasteLyn" height="32" class="me-2">
                        <h5 class="fw-bold text-white mb-0">
                            Waste<span class="text-wl-green">Lyn</span>
                        </h5>
                    </div>
                    <p class="small pe-md-4">
                        Platform pembentukan kebiasaan pengelolaan sampah rumah tangga berbasis
                        gamifikasi dan kecerdasan buatan. Dikembangkan untuk Kota Depok.
                    </p>
                    <div class="d-flex gap-2 mt-3">
                        @foreach (['instagram', 'youtube', 'tiktok', 'twitter'] as $icon)
                            <a href="#" class="text-white-50"><i class="fab fa-{{ $icon }}"></i></a>
                        @endforeach
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <h6 class="text-white fw-bold mb-3">Fitur</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="#fitur">Waste Mission</a></li>
                        <li><a href="#fitur">Eco Habit Score</a></li>
                        <li><a href="#fitur">Smart Waste Network</a></li>
                        <li><a href="#fitur">Bero AI</a></li>
                        <li><a href="#fitur">Reward System</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-2">
                    <h6 class="text-white fw-bold mb-3">Perusahaan</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#">Tim</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="fas fa-envelope text-wl-green me-2"></i> si.wastelyn@gmail.com</li>
                        <li><i class="fas fa-phone text-wl-green me-2"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-map-marker-alt text-wl-green me-2"></i> Kota Depok, Jawa Barat</li>
                    </ul>
                    <div class="small mt-3">
                        <i class="fas fa-school text-wl-green me-1"></i> SMK Informatika Utama — Tim UtamainMenang
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
                <span>&copy; {{ date('Y') }} WasteLyn. Seluruh hak cipta dilindungi.</span>
                <span>Dibuat dengan <span class="text-wl-green">♥</span> untuk lingkungan yang lebih baik.</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        (function () {
            const nav = document.getElementById('wlNavbar');
            const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 40);
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();

        new Swiper(".fiturSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 2500, disableOnInteraction: false },
            breakpoints: {
                576: { slidesPerView: 2, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 24 },
                992: { slidesPerView: 4, spaceBetween: 24 },
                1200: { slidesPerView: 5, spaceBetween: 24 },
            }
        });

        new Swiper(".levelSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 2500, disableOnInteraction: false },
            breakpoints: {
                576: { slidesPerView: 2, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 24 },
                992: { slidesPerView: 4, spaceBetween: 24 },
                1200: { slidesPerView: 5, spaceBetween: 24 },
            }
        });
    </script>
</body>

</html>