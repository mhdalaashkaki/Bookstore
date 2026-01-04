<!DOCTYPE html>
<html lang="{{ get_current_locale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_new_account') }}</title>
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
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.2);
        }

        .btn-register {
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
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 107, 157, 0.7);
            background: linear-gradient(135deg, #ff5a8f 0%, #ff9166 100%);
        }

        .btn-register:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.5);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #4a4a6a;
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: #ff6b9d;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .auth-footer a:hover {
            text-decoration: underline;
            color: #ff5a8f;
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

        .form-error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-title">👤 {{ __('messages.create_new_account') }}</div>

        @if ($errors->any())
            <div class="error-message">
                <strong>{{ __('messages.form_error') }}:</strong>
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Registration Form -->
        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('messages.username') }}</label>
                <input
                    type="text"
                    name="name"
                    class="form-input"
                    placeholder="{{ __('messages.full_name') }}"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.login_username') }}</label>
                <input
                    type="text"
                    name="username"
                    class="form-input"
                    placeholder="{{ __('messages.unique_name') }}"
                    value="{{ old('username') }}"
                    required
                >
                @error('username')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.email') }}</label>
                <input
                    type="email"
                    name="email"
                    class="form-input"
                    placeholder="example@email.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.password') }}</label>
                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="{{ __('messages.strong_password') }}"
                    required
                >
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.password_confirmation') }}</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="{{ __('messages.confirm_password') }}"
                    required
                >
            </div>

            <button type="submit" class="btn-register">{{ __('messages.create_account') }}</button>
        </form>

        <div class="auth-footer">
            {{ __('messages.already_have_account') }} <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
        </div>
    </div>
</body>
</html>
