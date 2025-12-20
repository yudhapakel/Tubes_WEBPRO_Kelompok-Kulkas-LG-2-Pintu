<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') - Admin Panel</title>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <!-- Admin Navbar -->
    <nav class="admin-navbar">
        <div class="admin-navbar-logo">CV Triloka Admin</div>
        <div class="admin-nav-links">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.requests.index') }}" class="admin-nav-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">Requests</a>
            <a href="{{ route('admin.penawarans.index') }}" class="admin-nav-link {{ request()->routeIs('admin.penawarans.*') ? 'active' : '' }}">Penawaran</a>
            <a href="{{ route('admin.invoices.index') }}" class="admin-nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">Invoices</a>
            <a href="{{ route('admin.payments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Payments</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="admin-nav-link" style="background: none; border: none; cursor: pointer;">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="admin-container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>

</html>