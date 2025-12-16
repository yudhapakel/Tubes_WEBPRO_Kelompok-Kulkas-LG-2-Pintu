<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Triloka Sejahtera - @yield('title', 'Dashboard')</title>
    
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <script src="{{ asset('js/componentLoader.js') }}"></script>

</head>
<body>

    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <script src="{{ asset('src/js/dashboard.js') }}"></script>
</body>
</html>