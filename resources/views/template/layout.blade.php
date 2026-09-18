<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WasteLyn - Platform Pengelolaan Sampah')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('mazer/dist/assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/wastelyn.css') }}">

    <link rel="shortcut icon" href="{{ asset('mazer/dist/assets/images/favicon.svg') }}" type="image/x-icon">

    @stack('styles')
</head>

<body>
    <div id="app">
        @include('template.sidebar')

        <div id="main" class="layout-navbar">
            <header class="mb-3">
                @include('template.navbar')
            </header>

            <div id="main-content">
                @yield('content')

                @include('template.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('mazer/dist/assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>