@extends('layouts.admin')

@section('title', 'Editar Noticia')
@section('page_title', 'Editar Noticia')

@section('content')
<div class="admin-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 2rem; box-shadow: var(--shadow-sm); max-width: 900px; margin: 0 auto;">
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert alert-error" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;" role="alert">
            <p style="font-weight: 600; margin-bottom: 0.5rem;">Por favor, corrija los siguientes errores:</p>
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div style="margin-bottom: 1.5rem;">
            <label for="title" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Título de la Noticia <span style="color: #dc2626;">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" required maxlength="255"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; font-size: 1rem;">
            @error('title')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label for="category_id" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Categoría <span style="color: #dc2626;">*</span></label>
                <select id="category_id" name="category_id" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px;">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="status" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Estado de Publicación <span style="color: #dc2626;">*</span></label>
                <select id="status" name="status" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px;">
                    <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="unpublished" {{ old('status', $news->status) === 'unpublished' ? 'selected' : '' }}>Despublicado</option>
                </select>
                @error('status')
                    <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="excerpt" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Resumen o Entradilla <span style="color: #dc2626;">*</span></label>
            <textarea id="excerpt" name="excerpt" rows="3" required maxlength="1000"
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; font-family: inherit; font-size: 0.95rem;">{{ old('excerpt', $news->excerpt) }}</textarea>
            @error('excerpt')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="content" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Cuerpo de la Noticia <span style="color: #dc2626;">*</span></label>
            <textarea id="content" name="content" rows="10" required
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; font-family: inherit; font-size: 0.95rem; line-height: 1.6;">{{ old('content', $news->content) }}</textarea>
            @error('content')
                <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
            @enderror
        </div>

        <div style="border: 1px dashed var(--color-border, #e2e8f0); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; background: var(--color-bg-subtle, #f8fafc);">
            @if($news->image)
            <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->image_alt ?? 'Imagen actual' }}" style="max-width: 120px; max-height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid var(--color-border, #e2e8f0);">
                <div>
                    <span style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b);">Imagen actual cargada</span>
                </div>
            </div>
            @endif

            <div style="margin-bottom: 1rem;">
                <label for="image" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Cambiar Imagen de Portada (Opcional)</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif"
                       style="display: block; margin-bottom: 0.5rem;">
                <small style="color: var(--color-text-secondary, #64748b);">Formatos: JPG, PNG, WebP, GIF. Máximo 2 MB.</small>
                @error('image')
                    <span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="image_alt" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Texto Alternativo de la Imagen</label>
                <input type="text" id="image_alt" name="image_alt" value="{{ old('image_alt', $news->image_alt) }}" maxlength="255"
                       style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px;">
                @error('image_alt')
                    <span style="color: #dc2626; font-size: 0.875rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}
                       style="width: 1.1rem; height: 1.1rem;">
                <span style="font-weight: 600;">Marcar como Noticia Destacada</span>
            </label>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('admin.news.index') }}" class="btn" style="padding: 0.75rem 1.5rem; border: 1px solid var(--color-border, #e2e8f0); border-radius: 6px; background: #fff; color: var(--color-text, #1e293b); text-decoration: none;">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 600;">
                Actualizar Noticia
            </button>
        </div>
    </form>
</div>
@endsection
