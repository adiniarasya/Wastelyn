<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WasteLyn - Platform Pengelolaan Sampah')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('mazer/dist/assets/images/favicon.svg') }}" type="image/x-icon">
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        @include('template.sidebar')
        <!-- /Sidebar -->

        <div id="main" class="layout-navbar">

            <header class="mb-3">
                <!-- Navbar -->
                @include('template.navbar')
                <!-- /Navbar -->
            </header>

            <!-- Main Content -->
            <div id="main-content">

                {{-- Breadcrumb dihapus dari sini, sekarang terserah masing-masing view --}}

                {{-- Konten utama --}}
                @yield('content')

                <!-- Footer -->
                @include('template.footer')
                <!-- /Footer -->

            </div>

        </div>
    </div>

    <script src="{{ asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/js/main.js') }}"></script>
</body>

</html>