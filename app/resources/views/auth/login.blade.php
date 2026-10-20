@extends('layouts.app')

@section('title', 'Iniciar Sesión')
@section('meta_description', 'Iniciar sesión en el panel de administración del Portal de Noticias')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h1>Iniciar Sesión</h1>
        <p class="login-subtitle">Acceda al panel de administración</p>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="validation-errors" role="alert">
                <h3>Se encontraron los siguientes errores:</h3>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            {{-- Email Field --}}
            <div class="form-group">
                <label for="email" class="form-label">
                    Correo electrónico <span class="required" aria-label="requerido">*</span>
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="email"
                       aria-describedby="email-error"
                       placeholder="correo@ejemplo.com">
                @error('email')
                    <p class="form-error" id="email-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="form-group">
                <label for="password" class="form-label">
                    Contraseña <span class="required" aria-label="requerido">*</span>
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                       autocomplete="current-password"
                       aria-describedby="password-error"
                       placeholder="••••••••">
                @error('password')
                    <p class="form-error" id="password-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Recordarme</label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="form-actions" style="flex-direction: column; gap: var(--space-md);">
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    Iniciar Sesión
                </button>
                <a href="{{ route('home') }}" class="btn btn-outline" style="width: 100%;">
                    ← Volver al sitio
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
