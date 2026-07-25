<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TechSecond') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell d-flex flex-column">
        <nav class="navbar navbar-expand-lg bg-white border-bottom">
            <div class="container">
                <a class="navbar-brand fw-semibold" href="{{ route('home') }}">TechSecond</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                        @auth
                            <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Orders</a></li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button class="btn btn-link nav-link" type="submit">Logout</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('auth.login.create') }}">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('auth.register.create') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-fill py-4">
            <div class="container">
                <x-alerts />
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
