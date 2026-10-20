@extends('layouts.admin')

@section('title', 'Categorías')
@section('page_title', 'Gestión de Categorías')

@section('content')
<div class="admin-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; box-shadow: var(--shadow-sm);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Lista de Categorías</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">
            + Nueva Categoría
        </a>
    </div>

    @if($categories->count() > 0)
    <div style="overflow-x: auto;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--color-border, #e2e8f0);">
                    <th style="padding: 0.75rem;">Color</th>
                    <th style="padding: 0.75rem;">Nombre</th>
                    <th style="padding: 0.75rem;">Slug</th>
                    <th style="padding: 0.75rem;">Descripción</th>
                    <th style="padding: 0.75rem; text-align: center;">Noticias</th>
                    <th style="padding: 0.75rem; text-align: center;">Estado</th>
                    <th style="padding: 0.75rem; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr style="border-bottom: 1px solid var(--color-border, #e2e8f0);">
                    <td style="padding: 0.75rem;">
                        <span style="display: inline-block; width: 24px; height: 24px; border-radius: 50%; background-color: {{ $category->color }}; border: 1px solid rgba(0,0,0,0.1);" title="{{ $category->color }}"></span>
                    </td>
                    <td style="padding: 0.75rem; font-weight: 600;">
                        {{ $category->name }}
                    </td>
                    <td style="padding: 0.75rem; font-family: var(--font-mono, monospace); font-size: 0.85rem; color: var(--color-text-secondary, #64748b);">
                        {{ $category->slug }}
                    </td>
                    <td style="padding: 0.75rem; font-size: 0.875rem; color: var(--color-text-secondary, #64748b);">
                        {{ Str::limit($category->description, 50) ?? '-' }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <span style="background: var(--color-bg-subtle, #f1f5f9); padding: 0.2rem 0.6rem; border-radius: 12px; font-weight: 600; font-size: 0.85rem;">
                            {{ $category->news_count }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($category->is_active)
                            <span style="color: #16a34a; background: #f0fdf4; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Activa</span>
                        @else
                            <span style="color: #64748b; background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Inactiva</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; text-align: right; white-space: nowrap;">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; border-radius: 4px; text-decoration: none;">
                            Editar
                        </a>

                        @if($category->news_count == 0)
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline-block; margin-left: 0.25rem;" onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; border-radius: 4px; cursor: pointer;">
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p style="color: var(--color-text-secondary, #64748b); text-align: center; padding: 2rem 0;">No hay categorías registradas aún.</p>
    @endif
</div>
@endsection
