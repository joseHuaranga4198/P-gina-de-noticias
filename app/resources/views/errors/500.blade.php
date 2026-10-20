@extends('layouts.app')

@section('title', 'Error del servidor - 500')

@section('content')
<div class="container" style="text-align: center; padding: 5rem 1rem;">
    <h1 style="font-size: 5rem; font-weight: 800; color: #ca8a04; margin-bottom: 1rem;">500</h1>
    <h2 style="font-size: 1.75rem; margin-bottom: 1rem;">Error interno del servidor</h2>
    <p style="color: var(--color-text-secondary, #64748b); max-width: 500px; margin: 0 auto 2rem;">
        Ha ocurrido un error inesperado al procesar su solicitud. El equipo técnico ha sido notificado.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">
            Ir al Inicio
        </a>
    </div>
</div>
@endsection
