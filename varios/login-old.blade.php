<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión | Focaccia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

<style>
    :root {
        /* Welcome palette - Light mode */
        --bg-primary: #FDFDFC;
        --bg-secondary: #ffffff;
        --bg-tertiary: #fff2f2;
        --text-primary: #1b1b18;
        --text-secondary: #706f6c;
        --text-muted: #A1A09A;
        --border-primary: #e3e3e0;
        --border-accent: #19140035;
        --border-hover: #1915014a;
        --accent-primary: #f53003;
        --accent-hover: #FF4433;
        --shadow-light: rgba(0,0,0,0.03);
        --shadow-medium: rgba(0,0,0,0.06);
        --inset-shadow: rgba(26,26,0,0.16);
        
        /* Welcome palette - Dark mode */
        --dark-bg-primary: #0a0a0a;
        --dark-bg-secondary: #161615;
        --dark-bg-tertiary: #1D0002;
        --dark-text-primary: #EDEDEC;
        --dark-text-secondary: #A1A09A;
        --dark-border-primary: #3E3E3A;
        --dark-border-hover: #62605b;
        --dark-accent: #FF4433;
        --dark-btn-bg: #eeeeec;
        --dark-btn-text: #1C1C1A;
        
        /* Laravel logo SVG colors */
        --laravel-red-light: #f53003;
        --laravel-red-dark: #F61500;
        
        /* Focaccia golden accent */
        --focaccia-gold: #D4B68A;
        --focaccia-gold-hover: #c9a770;
        --focaccia-gold-shadow: rgba(212, 182, 138, 0.3);
    }

    body {
        background-color: var(--bg-primary);
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--dark-bg-primary) 100%);
        color: var(--text-primary);
        font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .login-container {
        width: 100%;
        max-width: 1200px;
        display: flex;
        gap: 3rem;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-primary);
        border-radius: 16px;
        box-shadow: 0 20px 60px var(--shadow-light);
        overflow: hidden;
        max-width: 450px;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 30px 80px var(--shadow-medium);
    }

    .login-header {
        background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-hover) 100%);
        color: var(--bg-primary);
        padding: 2.5rem 2rem;
        text-align: center;
        border-bottom: 1px solid var(--border-accent);
    }

    .login-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .login-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem;
        color: var(--bg-secondary);
    }

    .login-body {
        padding: 2.5rem 2rem;
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-primary);
        box-shadow: inset 0px 0px 0px 1px var(--inset-shadow);
        border-top: none;
    }

    .form-floating > .form-control {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-primary);
        border-radius: 8px;
        padding: 1rem 0.75rem;
        color: var(--text-primary);
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-floating > .form-control:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 0.2rem rgba(245, 48, 3, 0.25);
        background-color: var(--bg-primary);
        color: var(--text-primary);
        outline: none;
    }

    .form-floating > label {
        color: var(--text-secondary);
        font-family: inherit;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--accent-primary);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-hover) 100%);
        border: 1px solid var(--accent-primary);
        border-radius: 8px;
        padding: 0.875rem;
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--bg-primary);
        transition: all 0.2s ease;
        font-family: inherit;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent-primary) 100%);
        color: var(--bg-primary);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 48, 3, 0.3);
        border-color: var(--accent-hover);
    }

    .btn-login:active {
        transform: translateY(-1px);
        box-shadow: 0 5px 10px rgba(245, 48, 3, 0.2);
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border-primary);
        background: linear-gradient(90deg, transparent, var(--border-primary), transparent);
    }

    .divider span {
        padding: 0 1rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
        background-color: var(--bg-secondary);
        font-family: inherit;
    }

    .form-check-input {
        background-color: var(--bg-primary);
        border-color: var(--border-primary);
        border-radius: 4px;
    }

    .form-check-input:checked {
        background-color: var(--accent-primary);
        border-color: var(--accent-primary);
    }

    .form-check-input:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 0.2rem rgba(245, 48, 3, 0.25);
    }

    .form-check-label {
        color: var(--text-primary);
        font-family: inherit;
    }

    .login-footer {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-primary);
        text-align: center;
        background: var(--bg-secondary);
        border-radius: 0 0 16px 16px;
    }

    .login-footer a {
        color: var(--accent-primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .login-footer a:hover {
        color: var(--accent-hover);
        text-decoration: underline;
        transform: translateY(-1px);
    }

    .login-footer p {
        color: var(--text-secondary);
        font-family: inherit;
        margin-bottom: 0;
    }

    .alert {
        border-radius: 10px;
        border: 1px solid;
        font-family: inherit;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background-color: #fff2f2;
        border-color: var(--accent-primary);
        color: var(--text-primary);
    }

    .alert-success {
        background-color: rgba(34, 197, 94, 0.1);
        border-color: #22c55e;
        color: var(--text-primary);
    }

    /* Add Laravel welcome page styling elements */
    .brand-element {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--accent-primary);
        font-weight: 600;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem;
        }

        .login-card {
            max-width: 100%;
        }

        .login-header {
            padding: 2rem 1.5rem;
        }

        .login-body {
            padding: 2rem 1.5rem;
        }
    }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-element">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 22V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 7L12 12L2 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Focaccia</h2>
                </div>
                <p>Iniciar sesión en tu cuenta</p>
            </div>

            <div class="login-body">

                @if(session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif

                @if(session('errors'))
                    <div class="alert alert-danger" role="alert">
                        @if(is_array(session('errors')))
                            @foreach(session('errors') as $error)
                                {{ $error }}<br>
                            @endforeach
                        @else
                            {{ session('errors') }}
                        @endif
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="Email" value="{{ old('email') }}" required>
                        <label for="floatingEmailInput">Email</label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="Contraseña" required>
                        <label for="floatingPasswordInput">Contraseña</label>
                    </div>

                    <!-- Remember me -->
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                            Recordarme
                        </label>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-login btn-lg">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 3H9C7.89543 3 7 3.89543 7 5V19C7 20.1046 7.89543 21 9 21H15C16.1046 21 17 20.1046 17 19V5C17 3.89543 16.1046 3 15 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 7H14M10 11H14M10 15H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Iniciar Sesión
                        </button>
                    </div>

                    <div class="login-footer">
                        <p class="mb-2">¿No tienes cuenta?</p>
                        <div class="brand-element">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <a href="{{ route('register') }}">Crear cuenta</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
