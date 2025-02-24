<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" />
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/materialdesignicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
</head>

<body>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    @yield('scripts')

</body>

</html>