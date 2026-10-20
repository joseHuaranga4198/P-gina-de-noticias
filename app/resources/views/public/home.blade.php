@extends('layouts.app')

@section('title', 'Inicio')
@section('meta_description', 'Portal de noticias actualizado. Lee las últimas noticias sobre política, tecnología, deportes y más.')

@section('content')
<div class="container">
    {{-- Featured news section --}}
    @if($featured->count() > 0)
    <section class="featured-section" aria-label="Noticias destacadas">
        <h2 class="section-title"><span class="accent-bar" aria-hidden="true"></span>Noticias Destacadas</h2>
        <div class="featured-grid">
            @foreach($featured as $item)
            <article class="featured-card {{ $loop->first ? 'featured-main' : 'featured-secondary' }}">
                <div class="card-image-wrapper">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->image_alt ?? $item->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    @else
                        <div class="card-placeholder" role="img" aria-label="{{ $item->image_alt ?? 'Imagen ilustrativa de la noticia' }}" style="background-color: {{ $item->category->color ?? '#3B82F6' }}20">
                            <span aria-hidden="true">📰</span>
                        </div>
                    @endif
                    <span class="category-badge" style="background-color: {{ $item->category->color }}">{{ $item->category->name }}</span>
                </div>
                <div class="card-body">
                    <h3><a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a></h3>
                    <p class="card-excerpt">{{ Str::limit($item->excerpt, 150) }}</p>
                    <div class="card-meta">
                        <time datetime="{{ $item->published_at->toIso8601String() }}">{{ $item->published_at->diffForHumans() }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ $item->reading_time }} min de lectura</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Categories sidebar + Latest news --}}
    <div class="content-layout">
        <section class="latest-section" aria-label="Últimas noticias">
            <h2 class="section-title"><span class="accent-bar" aria-hidden="true"></span>Últimas Noticias</h2>
            <div class="news-grid">
                @forelse($latest as $item)
                <article class="news-card">
                    <div class="card-image-wrapper">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->image_alt ?? $item->title }}" loading="lazy">
                        @else
                            <div class="card-placeholder" role="img" aria-label="{{ $item->image_alt ?? 'Imagen ilustrativa' }}" style="background-color: {{ $item->category->color ?? '#3B82F6' }}20">
                                <span aria-hidden="true">📰</span>
                            </div>
                        @endif
                        <span class="category-badge" style="background-color: {{ $item->category->color }}">{{ $item->category->name }}</span>
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
                @empty
                <div class="empty-state" role="status">
                    <p>No hay noticias disponibles en este momento.</p>
                </div>
                @endforelse
            </div>
        </section>

        <aside class="sidebar" aria-label="Categorías">
            <div class="sidebar-section">
                <h2 class="sidebar-title">Categorías</h2>
                <ul class="category-list">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}" class="category-link">
                            <span class="category-color" style="background-color: {{ $category->color }}" aria-hidden="true"></span>
                            <span>{{ $category->name }}</span>
                            <span class="category-count" aria-label="{{ $category->news_count }} noticias">{{ $category->news_count }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection
