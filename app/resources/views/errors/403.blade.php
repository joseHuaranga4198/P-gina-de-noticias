@extends('layouts.app')

@section('title', 'Acceso denegado - 403')

@section('content')
<div class="container" style="text-align: center; padding: 5rem 1rem;">
    <h1 style="font-size: 5rem; font-weight: 800; color: #dc2626; margin-bottom: 1rem;">403</h1>
    <h2 style="font-size: 1.75rem; margin-bottom: 1rem;">Acceso no autorizado</h2>
    <p style="color: var(--color-text-secondary, #64748b); max-width: 500px; margin: 0 auto 2rem;">
        No dispone de los permisos necesarios para acceder a este recurso. Por favor inicie sesión con una cuenta autorizada.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">
            Ir al Inicio
        </a>
        <a href="{{ route('login') }}" class="btn" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b);">
            Iniciar Sesión
        </a>
    </div>
</div>
@endsection
