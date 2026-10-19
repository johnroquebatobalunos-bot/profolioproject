<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f3f5ef">
    <title>@yield('title', 'PortfolioForm')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <header class="site-nav">
        <a class="brand" href="{{ route('home') }}" aria-label="PortfolioForm home">
            <span>PortfolioForm</span>
        </a>
        <nav class="nav-links" aria-label="Main navigation">
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Workspace</a>
            @auth
                <span class="nav-user">{{ auth()->user()->name }}</span>
                <form class="nav-logout" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link-button" type="submit">Log out</button>
                </form>
            @endauth
        </nav>
        <div class="header-actions">
            @guest
                <a class="header-login" href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Log in</a>
            @endguest
            <a class="button button-dark nav-button" href="{{ route('portfolios.create') }}">New portfolio <span aria-hidden="true">+</span></a>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="flash-message" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <a class="brand brand-small" href="{{ route('home') }}"><span>PortfolioForm</span></a>
        <span>Make room for good work.</span>
        <span class="footer-templates">SIMPLE <i></i> MODERN <i></i> CREATIVE</span>
    </footer>
</body>
</html>