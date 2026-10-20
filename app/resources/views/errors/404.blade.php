@extends('layouts.app')

@section('title', 'Página no encontrada - 404')

@section('content')
<div class="container" style="text-align: center; padding: 5rem 1rem;">
    <h1 style="font-size: 5rem; font-weight: 800; color: var(--color-primary, #1a365d); margin-bottom: 1rem;">404</h1>
    <h2 style="font-size: 1.75rem; margin-bottom: 1rem;">Página no encontrada</h2>
    <p style="color: var(--color-text-secondary, #64748b); max-width: 500px; margin: 0 auto 2rem;">
        La noticia o página que busca no existe, ha sido eliminada o su enlace es incorrecto.
    </p>
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px;">
            Ir al Inicio
        </a>
        <a href="{{ route('search') }}" class="btn" style="padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b);">
            Buscar Noticias
        </a>
    </div>
</div>
@endsection
