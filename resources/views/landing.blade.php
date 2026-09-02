<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WasteLyn - Platform Pengelolaan Sampah</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', 'Poppins', sans-serif;
        }

        .font-display {
            font-family: 'Poppins', sans-serif;
        }

        /* Warna Brand */
        .bg-primary { background: #2E7D32; }
        .bg-primary-dark { background: #1B5E20; }
        .bg-primary-light { background: #4CAF50; }
        .bg-tech { background: #1A237E; }
        .text-primary { color: #2E7D32; }
        .text-tech { color: #1A237E; }
        .border-primary { border-color: #2E7D32; }
        .hover-bg-primary:hover { background: #1B5E20; }

        html {
            scroll-behavior: smooth;
        }

        .hero-pattern {
            background-color: #f8fbf8;
            background-image: 
                radial-gradient(at 0% 0%, rgba(46, 125, 50, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(26, 35, 126, 0.03) 0px, transparent 50%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(46, 125, 50, 0.15);
        }

        .float-slow {
            animation: floatSlow 6s ease-in-out infinite;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }

        .gradient-text-green {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            background: #2E7D32;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1B5E20;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #2E7D32;
            color: #2E7D32;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #2E7D32;
            color: white;
            transform: translateY(-2px);
        }

        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(16px) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        }

        .navbar-scrolled .nav-link {
            color: #1a1a2e !important;
        }

        .navbar-scrolled .nav-brand {
            color: #2E7D32 !important;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #2E7D32; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #1B5E20; }
    </style>
</head>
<body>
    
    <nav id="navbar" class="fixed w-full top-0 z-50 transition-all duration-300 py-3">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2.5 nav-brand transition-colors duration-300">
                    <img src="{{ asset('img/logo.png') }}" alt="WasteLyn" class="h-10 w-auto" onerror="this.style.display='none'">
                    <span class="text-xl font-bold text-primary font-display">WasteLyn</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="nav-link text-gray-600 hover:text-primary transition duration-300 text-sm font-medium">Fitur</a>
                    <a href="#about" class="nav-link text-gray-600 hover:text-primary transition duration-300 text-sm font-medium">Tentang</a>
                    <a href="#contact" class="nav-link text-gray-600 hover:text-primary transition duration-300 text-sm font-medium">Kontak</a>
                    <div class="flex items-center gap-3 ml-4">
                        <a href="{{ route('login') }}" class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 text-primary bg-white/80 hover:bg-white border border-gray-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 text-white bg-primary hover:bg-primary-dark shadow-md hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    </div>
                </div>

                <!-- Mobile Toggle -->
                <button id="menuToggle" class="md:hidden text-gray-700 text-xl hover:text-primary transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden mt-4 pt-4 border-t border-gray-100">
                <div class="flex flex-col gap-3">
                    <a href="#features" class="text-gray-600 hover:text-primary transition py-2 text-sm font-medium">Fitur</a>
                    <a href="#about" class="text-gray-600 hover:text-primary transition py-2 text-sm font-medium">Tentang</a>
                    <a href="#contact" class="text-gray-600 hover:text-primary transition py-2 text-sm font-medium">Kontak</a>
                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold text-center transition text-primary bg-gray-50 hover:bg-gray-100 border border-gray-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full text-sm font-semibold text-center transition text-white bg-primary hover:bg-primary-dark">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-pattern min-h-screen flex items-center pt-20 relative overflow-hidden">
        <div class="absolute top-20 right-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-10 w-80 h-80 bg-tech/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 md:px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left -->
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full text-sm text-primary font-medium border border-primary/10 shadow-sm mb-6">
                        <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                        Platform Pengelolaan Sampah
                    </div>

                    <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Bentuk Kebiasaan
                        <span class="gradient-text-green">Ramah Lingkungan</span>
                        <br>dengan <span class="text-tech">Teknologi</span>
                    </h1>

                    <p class="text-gray-600 text-lg leading-relaxed mb-8 max-w-lg">
                        Wastelyn membantu Anda mengelola sampah rumah tangga dengan pendekatan gamifikasi 
                        dan kecerdasan buatan. Mulai dari misi harian hingga reward menarik.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-3.5 rounded-full font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                            <i class="fas fa-rocket"></i> Mulai Sekarang
                        </a>
                        <a href="#features" class="btn-outline-primary px-8 py-3.5 rounded-full font-semibold flex items-center gap-2">
                            <i class="fas fa-play-circle"></i> Lihat Fitur
                        </a>
                    </div>

                    <!-- Social Proof -->
                    <div class="flex items-center gap-8 mt-10 pt-8 border-t border-gray-200/60">
                        <div class="flex -space-x-2">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary border-2 border-white font-semibold text-xs">A</div>
                            <div class="w-10 h-10 rounded-full bg-tech/10 flex items-center justify-center text-tech border-2 border-white font-semibold text-xs">B</div>
                            <div class="w-10 h-10 rounded-full bg-primary-light/10 flex items-center justify-center text-primary-light border-2 border-white font-semibold text-xs">C</div>
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-2 border-white font-semibold text-xs">+</div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">500+ Pengguna Aktif</p>
                            <p class="text-xs text-gray-500">Bergabung setiap minggu</p>
                        </div>
                    </div>
                </div>

                <!-- Right - Hero Image -->
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="glass rounded-3xl p-8 shadow-2xl border border-white/60">
                            <div class="text-center">
                                <!-- Placeholder Image -->
                                <div class="mb-4 flex justify-center">
                                    <img src="{{ asset('img/hero-illustration.png') }}" 
                                         alt="WasteLyn Illustration" 
                                         class="w-full max-w-md mx-auto"
                                         onerror="this.style.display='none'">
                                    <!-- Fallback jika gambar tidak ada -->
                                    <div id="heroFallback" class="w-full max-w-md mx-auto aspect-square bg-gradient-to-br from-primary/5 to-tech/5 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <div class="text-center">
                                            <div class="text-8xl mb-4">🌿</div>
                                            <p class="text-gray-400 text-sm">Hero Illustration</p>
                                            <p class="text-gray-300 text-xs">(Placeholder)</p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-2xl font-bold text-gray-800 font-display">Eco Habit Score</h3>
                                <p class="text-gray-500 text-sm">Cerminan kebiasaan ramah lingkungan</p>

                                <div class="mt-6 inline-block bg-primary/10 px-4 py-1.5 rounded-full">
                                    <span class="text-primary font-semibold text-sm">🏆 Green Warrior</span>
                                </div>

                                <div class="mt-4">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                                        <span>Level 3</span>
                                        <span>75% ke Level 4</span>
                                    </div>
                                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-primary to-primary-light rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100">
                                    <div>
                                        <p class="text-2xl font-bold text-primary">750</p>
                                        <p class="text-xs text-gray-500">XP</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-tech">320</p>
                                        <p class="text-xs text-gray-500">Poin</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-800">12</p>
                                        <p class="text-xs text-gray-500">Misi</p>
                                    </div>
                                </div>

                                <div class="flex justify-center gap-2 mt-6 pt-6 border-t border-gray-100">
                                    <span class="px-3 py-1 bg-primary/5 text-primary rounded-full text-xs font-medium">🎯 Misi</span>
                                    <span class="px-3 py-1 bg-tech/5 text-tech rounded-full text-xs font-medium">🤖 AI</span>
                                    <span class="px-3 py-1 bg-primary-light/5 text-primary-light rounded-full text-xs font-medium">🎁 Reward</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm tracking-wider uppercase">FITUR UNGGULAN</span>
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-gray-800 mt-2 mb-4">
                    Semua yang Anda Butuhkan
                </h2>
                <p class="text-gray-500 leading-relaxed">
                    5 fitur utama yang terintegrasi untuk membentuk kebiasaan pengelolaan sampah berkelanjutan
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-2xl p-7 border border-gray-100 shadow-sm hover:shadow-lg">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-2xl mb-4">🎯</div>
                    <h3 class="text-lg font-bold text-gray-800">Eco Mission</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5">
                        Misi lingkungan dengan target dan batas waktu. Dapatkan XP & poin setiap misi selesai.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-2xl p-7 border border-gray-100 shadow-sm hover:shadow-lg">
                    <div class="w-14 h-14 rounded-2xl bg-tech/10 flex items-center justify-center text-2xl mb-4">📊</div>
                    <h3 class="text-lg font-bold text-gray-800">Eco Habit Score</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5">
                        XP permanen yang mencerminkan kebiasaan ramah lingkungan. Hanya naik, tidak pernah turun.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-2xl p-7 border border-gray-100 shadow-sm hover:shadow-lg">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-2xl mb-4">🏪</div>
                    <h3 class="text-lg font-bold text-gray-800">Smart Waste Network</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5">
                        Cari bank sampah terdekat dan request penjemputan sampah dari rumah.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-white rounded-2xl p-7 border border-gray-100 shadow-sm hover:shadow-lg">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center text-2xl mb-4">🤖</div>
                    <h3 class="text-lg font-bold text-gray-800">EcoGuide AI</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5">
                        Asisten cerdas untuk edukasi, klasifikasi sampah, dan rekomendasi misi personal.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover bg-white rounded-2xl p-7 border border-gray-100 shadow-sm hover:shadow-lg">
                    <div class="w-14 h-14 rounded-2xl bg-yellow-50 flex items-center justify-center text-2xl mb-4">🎁</div>
                    <h3 class="text-lg font-bold text-gray-800">Reward System</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5">
                        Tukarkan poin dengan pulsa, voucher, bibit tanaman, dan merchandise eksklusif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== HOW IT WORKS ==================== -->
    <section id="about" class="py-24 bg-gray-50/80">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-tech font-semibold text-sm tracking-wider uppercase">CARA KERJA</span>
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-gray-800 mt-2 mb-4">
                    Mulai dalam 3 Langkah Mudah
                </h2>
                <p class="text-gray-500 leading-relaxed">
                    Wastelyn dirancang sederhana agar siapa pun bisa memulai kebiasaan ramah lingkungan
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center text-3xl mx-auto mb-4 font-bold text-primary border-4 border-white shadow-lg">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Ikuti Misi</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5 max-w-xs mx-auto">
                        Pilih misi lingkungan sesuai level dan minat Anda. Mulai dari yang mudah hingga tantangan.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-tech/10 flex items-center justify-center text-3xl mx-auto mb-4 font-bold text-tech border-4 border-white shadow-lg">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Kumpulkan XP & Poin</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5 max-w-xs mx-auto">
                        Setor sampah, selesaikan misi, dan konsisten setiap hari. Naikkan level Anda.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-primary-light/10 flex items-center justify-center text-3xl mx-auto mb-4 font-bold text-primary-light border-4 border-white shadow-lg">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Rasakan Manfaatnya</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mt-1.5 max-w-xs mx-auto">
                        Tukarkan poin, lihat progress, dan bangga menjadi bagian dari solusi lingkungan.
                    </p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="bg-tech text-white px-10 py-3.5 rounded-full font-semibold shadow-lg hover:shadow-xl inline-flex items-center gap-2 transition hover:bg-opacity-90">
                    <i class="fas fa-user-plus"></i> Gabung Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== STATISTICS ==================== -->
    <section class="py-16 bg-primary">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                <div>
                    <p class="text-4xl md:text-5xl font-extrabold font-display">50+</p>
                    <p class="text-sm text-white/70 mt-1.5">Bank Sampah</p>
                </div>
                <div>
                    <p class="text-4xl md:text-5xl font-extrabold font-display">1.2K</p>
                    <p class="text-sm text-white/70 mt-1.5">Pengguna Aktif</p>
                </div>
                <div>
                    <p class="text-4xl md:text-5xl font-extrabold font-display">8.5K</p>
                    <p class="text-sm text-white/70 mt-1.5">Sampah Didaur Ulang</p>
                </div>
                <div>
                    <p class="text-4xl md:text-5xl font-extrabold font-display">95%</p>
                    <p class="text-sm text-white/70 mt-1.5">Kepuasan Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA ==================== -->
    <section class="py-20 bg-primary relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
            <h2 class="font-display text-3xl md:text-4xl font-extrabold text-white mb-4">
                Siap Memulai Perubahan?
            </h2>
            <p class="text-white/80 text-lg max-w-2xl mx-auto leading-relaxed mb-8">
                Bergabunglah dengan ribuan pengguna yang telah membentuk kebiasaan baik 
                dalam mengelola sampah rumah tangga.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-primary px-10 py-3.5 rounded-full font-semibold hover:bg-gray-50 transition shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                    <i class="fas fa-rocket"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white px-10 py-3.5 rounded-full font-semibold hover:bg-white/10 transition inline-flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer id="contact" class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-2.5 text-2xl font-bold font-display">
                        <img src="{{ asset('img/logo-white.png') }}" alt="WasteLyn" class="h-8 w-auto" onerror="this.style.display='none'">
                        <span>WasteLyn</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mt-4">
                        Platform pembentukan kebiasaan pengelolaan sampah rumah tangga berbasis gamifikasi dan AI.
                    </p>
                    <div class="flex gap-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition text-lg"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Fitur</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Eco Mission</a></li>
                        <li><a href="#" class="hover:text-white transition">Eco Habit Score</a></li>
                        <li><a href="#" class="hover:text-white transition">Smart Waste Network</a></li>
                        <li><a href="#" class="hover:text-white transition">EcoGuide AI</a></li>
                        <li><a href="#" class="hover:text-white transition">Reward System</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Perusahaan</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Tim</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Kontak</h4>
                    <ul class="space-y-2.5 text-sm text-gray-400">
                        <li><i class="fas fa-envelope w-5"></i> wastelyn@gmail.com</li>
                        <li><i class="fas fa-phone w-5"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-map-marker-alt w-5"></i> Kota Depok, Jawa Barat</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; 2026 WasteLyn. Dibuat dengan <span class="text-red-400">❤️</span> untuk lingkungan yang lebih baik.</p>
            </div>
        </div>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        // Mobile Menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Close mobile menu on link click
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        // Hide fallback jika gambar muncul
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('load', function() {
                const fallback = this.parentElement.querySelector('#heroFallback');
                if (fallback) fallback.style.display = 'none';
            });
        });
    </script>

</body>
</html>