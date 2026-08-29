<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertical Navbar - Mazer Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('mazer/dist/assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('mazer/dist/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('mazer/dist/assets/css/app.css')}}">
    <link rel="shortcut icon" href="{{asset('mazer/dist/assets/images/favicon.svg')}}" type="image/x-icon">
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        @include('template.sidebar')
        <!-- /Sidebar -->
        <div id="main" class='layout-navbar'>

            <header class='mb-3'>
                <!--- Navbar -->
                @include('template.navbar')
                <!--- /Navbar -->
            </header>


            <!-- Main Content -->
            <div id="main-content">

                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Vertical Layout with Navbar</h3>
                                <p class="text-subtitle text-muted">Navbar will appear in top of the page.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Layout Vertical Navbar
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        @yield('content')
                    </section>
                </div>
                <!-- footer -->
                @include('template.footer')
                <!-- footer -->
            </div>

        </div>
    </div>
    <script src="{{asset('mazer/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('mazer/dist/assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('mazer/dist/assets/js/main.js')}}"></script>
</body>

</html>