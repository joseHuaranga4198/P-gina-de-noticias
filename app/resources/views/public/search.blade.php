@extends('layouts.app')

@section('title', 'Buscar: ' . ($query ?? ''))
@section('meta_description', 'Resultados de búsqueda en el Portal de Noticias')

@section('content')
    <!-- Search Hero -->
    <div class="search-hero">
        <div class="container">
            <h1>Buscar Noticias</h1>
            <form action="{{ route('search') }}" method="GET" class="search-form" role="search" aria-label="Buscar noticias">
                <label for="search-page-input" class="sr-only">Escriba su término de búsqueda</label>
                <input type="search" id="search-page-input" name="q"
                       placeholder="Escriba su búsqueda..."
                       value="{{ $query ?? '' }}"
                       aria-label="Término de búsqueda"
                       autofocus
                       autocomplete="off">
                <button type="submit" aria-label="Buscar">
                    <span aria-hidden="true">🔍</span>
                </button>
            </form>
        </div>
    </div>

    <div class="container">
        @if(isset($query) && strlen($query) > 0)
            @if(strlen($query) < 2)
                <!-- Query too short -->
                <div class="empty-state" role="status">
                    <div class="empty-state-icon" aria-hidden="true">✏️</div>
                    <p>El término de búsqueda debe tener al menos 2 caracteres.</p>
                </div>
            @else
                <!-- Results info -->
                <p class="search-results-info" role="status" aria-live="polite">
                    Se encontraron <strong>{{ $results->total() }}</strong> resultados para
                    &ldquo;<strong>{{ $query }}</strong>&rdquo;
                </p>

                @if($results->count() > 0)
                    <div class="news-grid">
                        @foreach($results as $item)
                        <article class="news-card">
                            <div class="card-image-wrapper">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->image_alt ?? $item->title }}" loading="lazy">
                                @else
                                    <div class="card-placeholder" role="img" aria-label="{{ $item->image_alt ?? 'Imagen ilustrativa' }}" style="background-color: {{ $item->category->color ?? '#3B82F6' }}20">
                                        <span aria-hidden="true">📰</span>
                                    </div>
                                @endif
                                @if($item->category)
                                    <span class="category-badge" style="background-color: {{ $item->category->color }}">{{ $item->category->name }}</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h3><a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a></h3>
                                <p class="card-excerpt">{{ Str::limit($item->excerpt, 120) }}</p>
                                <div class="card-meta">
                                    <time datetime="{{ $item->published_at->toIso8601String() }}">{{ $item->published_at->diffForHumans() }}</time>
                                    <span aria-hidden="true">·</span>
                                    <span>{{ $item->reading_time }} min</span>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    @if($results->hasPages())
                        <nav class="pagination-wrapper" aria-label="Paginación de resultados">
                            {{ $results->appends(['q' => $query])->links() }}
                        </nav>
                    @endif
                @else
                    <!-- No results -->
                    <div class="empty-state" role="status">
                        <div class="empty-state-icon" aria-hidden="true">🔍</div>
                        <p>No se encontraron resultados para &ldquo;{{ $query }}&rdquo;.</p>
                        <p class="text-muted text-small">Intente con otros términos o navegue por nuestras categorías.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-lg">Volver al inicio</a>
                    </div>
                @endif
            @endif
        @else
            <!-- No query entered -->
            <div class="empty-state" role="status">
                <div class="empty-state-icon" aria-hidden="true">🔍</div>
                <p>Ingrese un término de búsqueda para encontrar noticias.</p>
            </div>
        @endif
    </div>
@endsection
