<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.home'))</title>
    {{-- Vite disabled locally to avoid missing manifest errors; re-enable when running npm run dev/build --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 50%, #0b1e3b 100%);
            min-height: 100vh;
            color: #fff;
        }

        /* Navigation bar */
        .navbar {
            background: #1e293b;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: #8B4513;
        }

        .navbar-nav {
            list-style: none;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-nav a {
            color: #8B4513;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-nav a:hover {
            color: #A0522D;
        }

        /* Language switcher */
        .language-switcher {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .language-switcher a {
            padding: 0.25rem 0.75rem;
            background: #8B4513;
            border-radius: 4px;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        .language-switcher a:hover {
            background: #A0522D;
            opacity: 1;
        }

        .language-switcher a.active {
            background: #D2691E;
            font-weight: bold;
        }

        /* Main container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert button {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 1.2rem;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 100%);
            color: white;
            padding: 3rem 2rem;
            margin-top: 4rem;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section {
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
        }

        .footer-section p {
            line-height: 1.6;
        }

        .footer-bottom {
            border-top: 1px solid #555;
            padding-top: 2rem;
            margin-top: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">📚 {{ __('messages.products') }}</a>
            <ul class="navbar-nav">
                <li><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('messages.admin_panel') }}</a></li>
                    @endif
                    @if(auth()->user()->role === 'user')
                        <li><a href="{{ route('orders.user') }}">{{ __('messages.my_orders') }}</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: white; cursor: pointer;">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    </li>
                    <li>{{ auth()->user()->username ?? auth()->user()->name }}</li>
                @else
                    <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                    <li><a href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                @endauth
                <li class="language-switcher">
                    <a href="{{ route('language.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}">AR</a>
                    <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('language.switch', 'tr') }}" class="{{ app()->getLocale() === 'tr' ? 'active' : '' }}">TR</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Messages and alerts -->
    @if ($errors->any())
        <div class="container">
            <div class="alert alert-error">
                <div>
                    <strong>{{ __('messages.error') }}</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.style.display='none';">×</button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="container">
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none';">×</button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none';">×</button>
            </div>
        </div>
    @endif

    <!-- Page content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>{{ __('messages.home') }}</h3>
                <p>{{ __('messages.about_us') }}</p>
            </div>

            <div class="footer-section">
                <h3>{{ __('messages.contact_us') }}</h3>
                <p>
                    {{ __('messages.email') }}: info@bookstore.com<br>
                    {{ __('messages.shipping_phone') }}: +90 500 000 00 00 <br>
                    {{ __('messages.shipping_city') }}: istanbul_turkey
                </p>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2026 {{ __('messages.home') }}. {{ __('messages.all_rights_reserved') }}</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                    Made with ❤️ using Laravel
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
