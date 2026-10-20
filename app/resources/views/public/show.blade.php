@extends('layouts.app')

@section('title', $news->title)
@section('meta_description', Str::limit($news->excerpt, 160))

@push('styles')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "NewsArticle",
    "headline": "{{ $news->title }}",
    "description": "{{ Str::limit($news->excerpt, 200) }}",
    "datePublished": "{{ $news->published_at->toIso8601String() }}",
    "dateModified": "{{ $news->updated_at->toIso8601String() }}",
    @if($news->image)
    "image": "{{ asset('storage/' . $news->image) }}",
    @endif
    "author": {
        "@type": "Organization",
        "name": "Portal de Noticias"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Portal de Noticias"
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
    },
    "articleSection": "{{ $news->category->name ?? '' }}"
}
</script>
@endpush

@section('content')
<div class="container">
    <!-- Ruta de navegación / Breadcrumb -->
    <nav class="breadcrumb" aria-label="Ruta de navegación" style="padding: 1rem 0; font-size: 0.9rem; color: var(--color-text-secondary, #64748b);">
        <a href="{{ route('home') }}" style="color: var(--color-primary, #1a365d); text-decoration: none;">Inicio</a>
        <span class="breadcrumb-separator" aria-hidden="true" style="margin: 0 0.5rem;">/</span>
        @if($news->category)
            <a href="{{ route('category.show', $news->category->slug) }}" style="color: var(--color-primary, #1a365d); text-decoration: none;">{{ $news->category->name }}</a>
            <span class="breadcrumb-separator" aria-hidden="true" style="margin: 0 0.5rem;">/</span>
        @endif
        <span aria-current="page" style="color: var(--color-text, #1e293b);">{{ Str::limit($news->title, 50) }}</span>
    </nav>

    <div class="article-wrapper" style="max-width: 840px; margin: 0 auto; padding-bottom: 3rem;">
        <article itemscope itemtype="https://schema.org/NewsArticle">
            <!-- Encabezado del artículo -->
            <header class="article-header" style="margin-bottom: 2rem;">
                @if($news->category)
                    <span class="category-badge" style="background-color: {{ $news->category->color }}; color: #fff; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600; display: inline-block; margin-bottom: 1rem;">
                        <a href="{{ route('category.show', $news->category->slug) }}" style="color: inherit; text-decoration: none;" itemprop="articleSection">
                            {{ $news->category->name }}
                        </a>
                    </span>
                @endif

                <h1 class="article-title" itemprop="headline" style="font-size: 2.25rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem; color: var(--color-text, #1e293b);">
                    {{ $news->title }}
                </h1>

                @if($news->excerpt)
                    <p class="article-excerpt" itemprop="description" style="font-size: 1.2rem; line-height: 1.6; color: var(--color-text-secondary, #475569); margin-bottom: 1.5rem; font-weight: 400;">
                        {{ $news->excerpt }}
                    </p>
                @endif

                <div class="article-meta" style="display: flex; flex-wrap: wrap; gap: 1.5rem; font-size: 0.9rem; color: var(--color-text-secondary, #64748b); padding-bottom: 1rem; border-bottom: 1px solid var(--color-border, #e2e8f0);">
                    <div class="article-meta-item">
                        <span aria-hidden="true">📅</span>
                        <time datetime="{{ $news->published_at->toIso8601String() }}" itemprop="datePublished">
                            {{ $news->published_at->translatedFormat('d \d\e F \d\e Y, H:i') }}
                        </time>
                    </div>
                    <div class="article-meta-item">
                        <span aria-hidden="true">⏱️</span>
                        <span>{{ $news->reading_time }} min de lectura</span>
                    </div>
                    <div class="article-meta-item">
                        <span aria-hidden="true">👁️</span>
                        <span>{{ number_format($news->views) }} {{ $news->views === 1 ? 'lectura' : 'lecturas' }}</span>
                    </div>
                </div>
            </header>

            <!-- Controles de Lectura en Voz Alta (Accesibilidad WCAG 2.2 - Web Speech API) -->
            <div class="tts-controls" id="tts-controls" role="region" aria-label="Controles de lectura en voz alta"
                 style="background: var(--color-bg-subtle, #f1f5f9); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                <span class="tts-label" style="font-weight: 600; font-size: 0.95rem; color: var(--color-text, #1e293b);">
                    🔊 Escuchar artículo:
                </span>
                <button type="button" class="btn btn-sm btn-primary tts-btn" id="tts-play" aria-label="Reproducir lectura del artículo en voz alta" style="padding: 0.4rem 0.85rem; border-radius: 4px; cursor: pointer;">
                    ▶ Reproducir
                </button>
                <button type="button" class="btn btn-sm tts-btn" id="tts-pause" aria-label="Pausar lectura" disabled style="padding: 0.4rem 0.85rem; border-radius: 4px; background: #e2e8f0; cursor: not-allowed;">
                    ⏸ Pausar
                </button>
                <button type="button" class="btn btn-sm tts-btn" id="tts-stop" aria-label="Detener lectura" disabled style="padding: 0.4rem 0.85rem; border-radius: 4px; background: #e2e8f0; cursor: not-allowed;">
                    ⏹ Detener
                </button>
                <span id="tts-status" style="font-size: 0.85rem; color: var(--color-text-secondary, #64748b); margin-left: auto;" aria-live="polite"></span>
            </div>

            <!-- Imagen principal -->
            @if($news->image)
                <figure class="article-image" style="margin: 0 0 2rem 0;">
                    <img src="{{ asset('storage/' . $news->image) }}"
                         alt="{{ $news->image_alt ?? $news->title }}"
                         itemprop="image"
                         loading="eager"
                         style="width: 100%; max-height: 480px; object-fit: cover; border-radius: 8px;">
                    @if($news->image_alt)
                        <figcaption style="font-size: 0.85rem; color: var(--color-text-secondary, #64748b); margin-top: 0.5rem; text-align: center;">
                            {{ $news->image_alt }}
                        </figcaption>
                    @endif
                </figure>
            @endif

            <!-- Contenido del artículo -->
            <div class="article-content" id="article-content" itemprop="articleBody" style="font-size: 1.125rem; line-height: 1.8; color: var(--color-text, #1e293b);">
                {!! $news->content !!}
            </div>

            <meta itemprop="dateModified" content="{{ $news->updated_at->toIso8601String() }}">
        </article>

        <!-- Noticias Relacionadas -->
        @if(isset($related) && $related->count() > 0)
        <section class="related-section" aria-label="Noticias relacionadas" style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid var(--color-border, #e2e8f0);">
            <h2 class="section-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--color-text, #1e293b);">
                Noticias Relacionadas
            </h2>
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
                @foreach($related as $item)
                <article class="news-card" style="background: var(--color-surface, #fff); border: 1px solid var(--color-border, #e2e8f0); border-radius: 8px; overflow: hidden; box-shadow: var(--shadow-sm);">
                    <div class="card-body" style="padding: 1.25rem;">
                        <span class="category-badge" style="background-color: {{ $item->category->color }}; color: #fff; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                            {{ $item->category->name }}
                        </span>
                        <h3 style="font-size: 1.1rem; margin: 0.75rem 0 0.5rem 0;">
                            <a href="{{ route('news.show', $item->slug) }}" style="color: var(--color-text, #1e293b); text-decoration: none;">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary, #64748b); line-height: 1.5;">
                            {{ Str::limit($item->excerpt, 90) }}
                        </p>
                        <div style="font-size: 0.75rem; color: var(--color-text-secondary, #94a3b8); margin-top: 0.75rem;">
                            <time datetime="{{ $item->published_at->toIso8601String() }}">{{ $item->published_at->diffForHumans() }}</time>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection
