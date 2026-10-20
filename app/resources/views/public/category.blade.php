@extends('layouts.app')

@section('title', $category->name)
@section('meta_description', $category->description ?? 'Noticias de ' . $category->name . ' — Portal de Noticias')

@section('content')
    <!-- Category Header -->
    <div class="category-header">
        <div class="container">
            <h1>
                <span class="category-color-dot" style="background-color: {{ $category->color }}" aria-hidden="true"></span>
                {{ $category->name }}
            </h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="content-layout">
            <!-- News for this category -->
            <section aria-label="Noticias de {{ $category->name }}">
                <div class="news-grid">
                    @forelse($news as $item)
                    <article class="news-card">
                        <div class="card-image-wrapper">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->image_alt ?? $item->title }}" loading="lazy">
                            @else
                                <div class="card-placeholder" role="img" aria-label="{{ $item->image_alt ?? 'Imagen ilustrativa' }}" style="background-color: {{ $category->color }}20">
                                    <span aria-hidden="true">📰</span>
                                </div>
                            @endif
                            <span class="category-badge" style="background-color: {{ $category->color }}">{{ $category->name }}</span>
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
                        <div class="empty-state-icon" aria-hidden="true">📭</div>
                        <p>No hay noticias en esta categoría por el momento.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio</a>
                    </div>
                    @endforelse
                </div>

                @if($news->hasPages())
                    <nav class="pagination-wrapper" aria-label="Paginación de noticias">
                        {{ $news->links() }}
                    </nav>
                @endif
            </section>

            <!-- Sidebar with other categories -->
            <aside class="sidebar" aria-label="Otras categorías">
                <div class="sidebar-section">
                    <h2 class="sidebar-title">Otras Categorías</h2>
                    <ul class="category-list">
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="category-link {{ $cat->id === $category->id ? 'active' : '' }}"
                               @if($cat->id === $category->id) aria-current="page" @endif>
                                <span class="category-color" style="background-color: {{ $cat->color }}" aria-hidden="true"></span>
                                <span>{{ $cat->name }}</span>
                                <span class="category-count" aria-label="{{ $cat->news_count }} noticias">{{ $cat->news_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection
