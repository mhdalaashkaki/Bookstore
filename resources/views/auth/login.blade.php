<!DOCTYPE html>
<html lang="{{ get_current_locale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.login') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            background: linear-gradient(135deg, #0b1e3b 0%, #1a2f5a 50%, #0b1e3b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .auth-container {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin: 2rem;
            backdrop-filter: blur(10px);
        }

        .auth-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 1.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #2c2c54;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #ff6b9d 0%, #ffa07a 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.05rem;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.7);
            background: linear-gradient(135deg, #ff5a8f 0%, #ff9166 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.5);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #4a4a6a;
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: #ff6b9d;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .auth-footer a:hover {
            color: #ff5a8f;
            text-decoration: underline;
            text-shadow: 0 0 10px rgba(255, 107, 157, 0.5);
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-title">🔐 {{ __('messages.login') }}</div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('messages.email_or_username') }}</label>
                <input type="text" name="login" class="form-input" placeholder="email@example.com {{ __('messages.or') }} {{ __('messages.username') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.password') }}</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">{{ __('messages.login') }}</button>
        </form>

        <div class="auth-footer">
            {{ __('messages.no_account') }} <a href="{{ route('register') }}">{{ __('messages.create_new_account') }}</a>
        </div>

        <div class="auth-footer" style="margin-top: 1rem; font-size: 0.9rem;">
            <p>{{ __('messages.test_data') }}:</p>
            <p>{{ __('messages.email') }}: admin@bookstore.com<br>{{ __('messages.password') }}: password123</p>
        </div>
    </div>
</body>
</html>
