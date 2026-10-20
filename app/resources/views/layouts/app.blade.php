<!DOCTYPE html>
<html lang="es" data-theme="light" data-font-size="normal">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Portal de noticias - Información actualizada')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Noticias') - Portal de Noticias</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Skip to content link (accesibilidad WCAG) -->
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>

    <!-- Barra de accesibilidad WCAG 2.2 AA -->
    <div class="a11y-toolbar" role="toolbar" aria-label="Herramientas de accesibilidad">
        <div class="container">
            <button type="button" class="a11y-btn" id="btn-font-increase" aria-label="Aumentar tamaño de texto" title="Aumentar tamaño de texto">
                <span aria-hidden="true">A+</span>
            </button>
            <button type="button" class="a11y-btn" id="btn-font-decrease" aria-label="Reducir tamaño de texto" title="Reducir tamaño de texto">
                <span aria-hidden="true">A-</span>
            </button>
            <button type="button" class="a11y-btn" id="btn-font-reset" aria-label="Restablecer tamaño de texto normal" title="Restablecer tamaño de texto">
                <span aria-hidden="true">A</span>
            </button>
            <button type="button" class="a11y-btn" id="btn-high-contrast" aria-label="Activar alto contraste" title="Alternar modo alto contraste">
                <span aria-hidden="true">◐</span> <span class="a11y-label">Contraste</span>
            </button>
            <button type="button" class="a11y-btn" id="btn-reading-mode" aria-label="Activar modo lectura simplificada" title="Modo lectura simplificada">
                <span aria-hidden="true">☰</span> <span class="a11y-label">Lectura</span>
            </button>
        </div>
    </div>

    <!-- Encabezado principal -->
    <header class="site-header" role="banner">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="site-logo" aria-label="Ir a la página principal del Portal de Noticias">
                    <span class="logo-icon" aria-hidden="true">📰</span>
                    <span class="logo-text">Portal de <strong>Noticias</strong></span>
                </a>

                <button type="button" class="menu-toggle" id="menu-toggle" aria-expanded="false" aria-controls="main-nav" aria-label="Abrir menú de navegación">
                    <span class="hamburger" aria-hidden="true">☰</span>
                </button>

                <nav id="main-nav" class="main-nav" role="navigation" aria-label="Navegación principal">
                    <ul class="nav-list">
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                        @php $navCategories = \App\Models\Category::active()->orderBy('name')->get(); @endphp
                        @foreach($navCategories as $cat)
                            <li><a href="{{ route('category.show', $cat->slug) }}" class="{{ request()->is('categoria/'.$cat->slug) ? 'active' : '' }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <div class="header-actions">
                    <form action="{{ route('search') }}" method="GET" class="search-form" role="search" aria-label="Buscar noticias">
                        <label for="search-input" class="sr-only">Buscar noticias</label>
                        <input type="search" id="search-input" name="q" placeholder="Buscar..." value="{{ request('q') }}" aria-label="Término de búsqueda" autocomplete="off">
                        <button type="submit" aria-label="Buscar">
                            <span aria-hidden="true">🔍</span>
                        </button>
                    </form>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">Panel Admin</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm" style="background: var(--color-bg-subtle, #f1f5f9); color: var(--color-text, #1e293b); text-decoration: none; padding: 0.35rem 0.75rem; border-radius: 4px;">Ingresar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Notificaciones flash -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            <div class="container">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" role="alert">
            <div class="container">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Contenido principal -->
    <main id="main-content" class="main-content" role="main" tabindex="-1">
        @yield('content')
    </main>

    <!-- Pie de página -->
    <footer class="site-footer" role="contentinfo">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h2>Portal de Noticias</h2>
                    <p>Tu fuente confiable de información actualizada. Cubrimos política, economía, tecnología, deportes, cultura, ciencia, salud y educación.</p>
                </div>
                <div class="footer-section">
                    <h2>Secciones</h2>
                    <nav aria-label="Navegación del pie de página">
                        <ul>
                            @foreach($navCategories ?? [] as $cat)
                                <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="footer-section">
                    <h2>Accesibilidad y Calidad</h2>
                    <p>Este sitio web cumple con las pautas de accesibilidad WCAG 2.2 nivel AA. Incluye síntesis de voz, alto contraste, modo lectura y navegación completa por teclado.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Portal de Noticias autoadministrable. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
