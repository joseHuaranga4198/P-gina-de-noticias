@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Panel de Control')

@section('content')
<div class="dashboard-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); margin-bottom: 0.5rem;">Total de Noticias</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-text, #1e293b);">{{ $stats['total_news'] }}</div>
    </div>
    <div class="stat-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); margin-bottom: 0.5rem;">Noticias Publicadas</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-success, #16a34a);">{{ $stats['published_news'] }}</div>
    </div>
    <div class="stat-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); margin-bottom: 0.5rem;">Borradores</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-warning, #ca8a04);">{{ $stats['draft_news'] }}</div>
    </div>
    <div class="stat-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); margin-bottom: 0.5rem;">Categorías Activas</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-primary, #1a365d);">{{ $stats['categories'] }}</div>
    </div>
    <div class="stat-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
        <div style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); margin-bottom: 0.5rem;">Total de Lecturas</div>
        <div style="font-size: 2rem; font-weight: 700; color: var(--color-info, #0284c7);">{{ number_format($stats['total_views']) }}</div>
    </div>
</div>

<div class="admin-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Noticias Recientes</h2>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">+ Nueva Noticia</a>
    </div>

    @if($recentNews->count() > 0)
    <div style="overflow-x: auto;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border, #e2e8f0);">
                    <th style="padding: 0.75rem;">Título</th>
                    <th style="padding: 0.75rem;">Categoría</th>
                    <th style="padding: 0.75rem;">Estado</th>
                    <th style="padding: 0.75rem;">Vistas</th>
                    <th style="padding: 0.75rem;">Fecha</th>
                    <th style="padding: 0.75rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentNews as $item)
                <tr style="border-bottom: 1px solid var(--color-border, #e2e8f0);">
                    <td style="padding: 0.75rem; font-weight: 500;">
                        <a href="{{ route('admin.news.edit', $item) }}" style="color: var(--color-text, #1e293b); text-decoration: none;">
                            {{ Str::limit($item->title, 50) }}
                        </a>
                    </td>
                    <td style="padding: 0.75rem;">
                        <span class="category-badge" style="background-color: {{ $item->category->color ?? '#3B82F6' }}; color: #fff; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                            {{ $item->category->name ?? 'Sin categoría' }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        @if($item->status === 'published')
                            <span style="color: #16a34a; background: #f0fdf4; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Publicado</span>
                        @elseif($item->status === 'draft')
                            <span style="color: #ca8a04; background: #fefce8; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Borrador</span>
                        @else
                            <span style="color: #64748b; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Despublicado</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">{{ $item->views }}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: var(--color-text-secondary, #64748b);">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding: 0.75rem; text-align: right;">
                        <a href="{{ route('admin.news.edit', $item) }}" style="color: var(--color-secondary, #2563EB); margin-right: 0.5rem; text-decoration: none; font-size: 0.875rem;">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p style="color: var(--color-text-secondary, #64748b); text-align: center; padding: 2rem 0;">No hay noticias registradas aún.</p>
    @endif
</div>

<div class="admin-quick-actions" style="display: flex; gap: 1rem; flex-wrap: wrap;">
    <a href="{{ route('admin.news.index') }}" class="btn" style="background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b); padding: 0.75rem 1.25rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
        Gestionar Noticias
    </a>
    <a href="{{ route('admin.categories.index') }}" class="btn" style="background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b); padding: 0.75rem 1.25rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
        Gestionar Categorías
    </a>
    <a href="{{ route('home') }}" target="_blank" class="btn" style="background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b); padding: 0.75rem 1.25rem; border-radius: 6px; text-decoration: none; font-weight: 500;">
        Ver Portal Público ↗
    </a>
</div>
@endsection
