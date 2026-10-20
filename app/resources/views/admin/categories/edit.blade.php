@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('page_title', 'Editar Categoría')

@section('content')
<div class="admin-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 2rem; box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto;">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert alert-error" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;" role="alert">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Nombre de la Categoría <span style="color: #dc2626;">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; font-size: 1rem;">
            @error('name')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="color" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Color Identificador <span style="color: #dc2626;">*</span></label>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <input type="color" id="color" name="color" value="{{ old('color', $category->color) }}"
                       style="width: 48px; height: 42px; padding: 2px; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; cursor: pointer;">
                <span style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b);">Seleccione el color para los badges y etiquetas</span>
            </div>
            @error('color')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="description" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Descripción (Opcional)</label>
            <textarea id="description" name="description" rows="3" maxlength="500"
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; font-family: inherit;">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       style="width: 1.1rem; height: 1.1rem;">
                <span style="font-weight: 600;">Categoría Activa (Visible en el menú del portal)</span>
            </label>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('admin.categories.index') }}" class="btn" style="padding: 0.75rem 1.5rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; background: #fff; color: var(--color-text, #1e293b); text-decoration: none;">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600;">
                Actualizar Categoría
            </button>
        </div>
    </form>
</div>
@endsection
