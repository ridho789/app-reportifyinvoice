<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}asset/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('') }}asset/assets/img/favicon.png">
    <title>
        @yield('title', 'Dashboard')
    </title>
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('') }}asset/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('') }}asset/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('') }}asset/assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-200">
    <!-- sidenav -->
    @include('layouts.sidenav')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- navbar -->
        @include('layouts.navbar')

        <!-- content -->
        @yield('content')

        <!-- footer -->
        @include('layouts.footer')
    </main>

    <!-- plugin -->
    @include('layouts.plugin')

    <!--   Core JS Files   -->
    <script src="{{ asset('') }}asset/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('') }}asset/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('') }}asset/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('') }}asset/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="{{ asset('') }}asset/assets/js/plugins/chartjs.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('') }}asset/assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>