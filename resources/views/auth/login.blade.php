@extends('layouts.main')

@section('title', 'Iniciar Sesión')

@section('content')

<style>
    body {
        background-color: #FDFDFC;
        color: #1b1b18;
        font-family: var(--font-sans);
    }

    .login-container {
        min-height: 100vh;
        background-color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .login-card {
        background-color: #1a1a1a;
        border: 2px solid #D4B68A;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(212, 182, 138, 0.3);
        overflow: hidden;
        max-width: 450px;
        width: 100%;
    }

    .login-header {
        background-color: #D4B68A;
        color: #000;
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .login-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .login-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
        font-size: 0.95rem;
    }

    .login-body {
        padding: 2.5rem 2rem;
    }

    .form-floating > .form-control {
        background-color: #2a2a2a;
        border: 2px solid #D4B68A;
        border-radius: 10px;
        padding: 1rem 0.75rem;
        color: #f5f5dc;
    }

    .form-floating > .form-control:focus {
        border-color: #D4B68A;
        box-shadow: 0 0 0 0.2rem rgba(212, 182, 138, 0.25);
        background-color: #2a2a2a;
        color: #f5f5dc;
    }

    .form-floating > label {
        color: #D4B68A;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #D4B68A;
    }

    .btn-login {
        background-color: #D4B68A;
        border: none;
        border-radius: 10px;
        padding: 0.875rem;
        font-weight: 600;
        font-size: 1.05rem;
        color: #000;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-login:hover {
        background-color: #c9a770;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(212, 182, 138, 0.3);
    }

    .btn-google {
        border: 2px solid #D4B68A;
        border-radius: 10px;
        padding: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        background: #2a2a2a;
        color: #D4B68A;
    }

    .btn-google:hover {
        border-color: #D4B68A;
        background: #D4B68A;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(212, 182, 138, 0.2);
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
        border-bottom: 1px solid #D4B68A;
    }

    .divider span {
        padding: 0 1rem;
        color: #D4B68A;
        font-size: 0.9rem;
    }

    .form-check-input {
        background-color: #2a2a2a;
        border-color: #D4B68A;
    }

    .form-check-input:checked {
        background-color: #D4B68A;
        border-color: #D4B68A;
    }

    .form-check-label {
        color: #f5f5dc;
    }

    .login-footer {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #D4B68A;
        text-align: center;
    }

    .login-footer a {
        color: #D4B68A;
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer a:hover {
        color: #c9a770;
        text-decoration: underline;
    }

    .login-footer p {
        color: #f5f5dc;
    }

    .alert {
        border-radius: 10px;
    }
</style>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Bienvenido</h2>
                <p>Inicia sesión en tu cuenta</p>
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
                        <button type="submit" class="btn btn-login btn-lg">Iniciar Sesión</button>
                    </div>

                    <div class="login-footer">
                        <p class="mb-2">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
