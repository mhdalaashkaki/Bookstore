<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.select_language') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .language-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .language-buttons a {
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .language-buttons a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
        }

        .language-buttons a.active {
            background: rgba(255, 255, 255, 0.4);
            border: 2px solid white;
        }

        .content {
            margin-top: 2rem;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1rem 0;
        }

        .info-box h3 {
            margin-bottom: 0.5rem;
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌍 {{ __('messages.select_language') }}</h1>

        <div class="language-buttons">
            <a href="{{ route('language.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}">
                🇸🇦 العربية
            </a>
            <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">
                🇬🇧 English
            </a>
            <a href="{{ route('language.switch', 'tr') }}" class="{{ app()->getLocale() === 'tr' ? 'active' : '' }}">
                🇹🇷 Türkçe
            </a>
        </div>

        <div class="content">
            <div class="info-box">
                <h3>{{ __('messages.welcome') }}</h3>
                <p>{{ __('messages.home') }}</p>
            </div>

            <div class="info-box">
                <h3>{{ __('messages.products') }}</h3>
                <p>{{ __('messages.product_name') }}: {{ __('messages.price') }} - {{ __('messages.stock') }}</p>
            </div>

            <div class="info-box">
                <h3>{{ __('messages.orders') }}</h3>
                <p>{{ __('messages.order_status') }}: {{ __('messages.pending') }} / {{ __('messages.completed') }}</p>
            </div>

            <div class="info-box">
                <h3>{{ __('messages.admin_panel') }}</h3>
                <p>{{ __('messages.dashboard') }} - {{ __('messages.statistics') }}</p>
            </div>
        </div>

        <a href="{{ route('home') }}" class="back-link">
            {{ __('messages.back') }} {{ __('messages.home') }}
        </a>
    </div>
</body>
</html>
