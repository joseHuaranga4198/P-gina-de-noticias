@extends('layouts.admin')

@section('title', 'Noticias')
@section('page_title', 'Gestión de Noticias')

@section('content')
<div class="admin-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;">
    <!-- Filters & Actions Header -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('admin.news.index') }}" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; flex: 1;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título o contenido..."
                   style="padding: 0.5rem 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; min-width: 220px;"
                   aria-label="Buscar noticias">

            <select name="status" style="padding: 0.5rem 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px;" aria-label="Filtrar por estado">
                <option value="">Todos los estados</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicadas</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borradores</option>
                <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>Despublicadas</option>
            </select>

            <select name="category" style="padding: 0.5rem 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px;" aria-label="Filtrar por categoría">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px;">Filtrar</button>
            @if(request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.news.index') }}" class="btn" style="padding: 0.5rem 1rem; border-radius: 6px; background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b); text-decoration: none;">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('admin.news.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; white-space: nowrap;">
            + Nueva Noticia
        </a>
    </div>

    <!-- News Table -->
    @if($news->count() > 0)
    <div style="overflow-x: auto;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border, #e2e8f0);">
                    <th style="padding: 0.75rem;">Título</th>
                    <th style="padding: 0.75rem;">Categoría</th>
                    <th style="padding: 0.75rem;">Estado</th>
                    <th style="padding: 0.75rem;">Destacada</th>
                    <th style="padding: 0.75rem;">Vistas</th>
                    <th style="padding: 0.75rem;">Fecha</th>
                    <th style="padding: 0.75rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                <tr style="border-bottom: 1px solid var(--color-border, #e2e8f0);">
                    <td style="padding: 0.75rem; font-weight: 500;">
                        <a href="{{ route('admin.news.edit', $item) }}" style="color: var(--color-text, #1e293b); text-decoration: none;">
                            {{ Str::limit($item->title, 45) }}
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
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($item->is_featured)
                            <span title="Noticia destacada" style="color: #ca8a04; font-weight: bold;">★</span>
                        @else
                            <span style="color: #cbd5e1;">-</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">{{ $item->views }}</td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: var(--color-text-secondary, #64748b);">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                    <td style="padding: 0.75rem; text-align: right; white-space: nowrap;">
                        <form action="{{ route('admin.news.toggle-status', $item) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--color-bg-subtle, #f1f5f9); border: 1px solid var(--color-border, #e2e8f0); border-radius: 4px; cursor: pointer;"
                                    title="{{ $item->status === 'published' ? 'Despublicar noticia' : 'Publicar noticia' }}">
                                {{ $item->status === 'published' ? 'Despublicar' : 'Publicar' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; border-radius: 4px; text-decoration: none; margin-left: 0.25rem;">
                            Editar
                        </a>

                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="display: inline-block; margin-left: 0.25rem;" onsubmit="return confirm('¿Está seguro de eliminar esta noticia?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 4px; cursor: pointer;">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $news->links() }}
    </div>
    @else
    <p style="color: var(--color-text-secondary, #64748b); text-align: center; padding: 2rem 0;">No se encontraron noticias con los criterios especificados.</p>
    @endif
</div>
@endsection
