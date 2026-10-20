<!DOCTYPE html>
<html lang="es" data-theme="light" data-font-size="normal">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de administración - Portal de Noticias">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') - Admin Portal de Noticias</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Skip to content link -->
    <a href="#admin-content" class="skip-link">Saltar al contenido principal</a>

    <div class="admin-layout" style="display: flex; min-height: 100vh;">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="admin-sidebar" role="navigation" aria-label="Navegación de administración"
               style="width: 250px; background: var(--color-primary, #1a365d); color: #fff; padding: 1.5rem 1rem; display: flex; flex-direction: column; flex-shrink: 0;">
            <div class="admin-sidebar-header" style="margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">
                    <a href="{{ route('home') }}" style="color: #fff; text-decoration: none;">
                        📰 Portal Admin
                    </a>
                </h2>
                <p style="font-size: 0.8rem; opacity: 0.8; margin-top: 0.25rem;">Panel de Control</p>
            </div>

            <nav class="admin-nav" aria-label="Menú de administración" style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('admin.dashboard') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}"
                   style="color: #fff; text-decoration: none; padding: 0.75rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; background: {{ request()->routeIs('admin.dashboard') ? 'rgba(255,255,255,0.15)' : 'transparent' }};">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('admin.news.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.news.*') ? 'page' : 'false' }}"
                   style="color: #fff; text-decoration: none; padding: 0.75rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; background: {{ request()->routeIs('admin.news.*') ? 'rgba(255,255,255,0.15)' : 'transparent' }};">
                    <span>📝</span> Noticias
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                   aria-current="{{ request()->routeIs('admin.categories.*') ? 'page' : 'false' }}"
                   style="color: #fff; text-decoration: none; padding: 0.75rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; background: {{ request()->routeIs('admin.categories.*') ? 'rgba(255,255,255,0.15)' : 'transparent' }};">
                    <span>📁</span> Categorías
                </a>

                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;"></div>

                <a href="{{ route('home') }}" class="admin-nav-item" target="_blank" rel="noopener"
                   style="color: #fff; text-decoration: none; padding: 0.75rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; opacity: 0.85;">
                    <span>🌐</span> Ver Sitio Web ↗
                </a>

                <form action="{{ route('logout') }}" method="POST" id="admin-logout-form" style="margin-top: auto;">
                    @csrf
                    <button type="submit" class="admin-nav-item"
                            style="width: 100%; border: none; background: rgba(220, 38, 38, 0.2); color: #fca5a5; text-align: left; padding: 0.75rem 1rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
                        <span>🚪</span> Cerrar Sesión
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main" style="flex: 1; display: flex; flex-direction: column; background: var(--color-bg, #f8fafc); min-width: 0;">
            <!-- Admin Header -->
            <header class="admin-header" role="banner" style="background: #fff; border-bottom: 1px solid var(--color-border, #e2e8f0); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
                <h1 class="admin-header-title" style="font-size: 1.5rem; font-weight: 700; margin: 0; color: var(--color-text, #1e293b);">@yield('page_title', 'Dashboard')</h1>

                <div class="admin-user-info" style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-weight: 600; color: var(--color-text, #1e293b);" aria-label="Usuario actual">
                        👤 {{ Auth::user()->name ?? 'Administrador' }}
                    </span>
                    <span style="background: #dbeafe; color: #1e40af; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                        {{ Auth::user()->role ?? 'admin' }}
                    </span>
                </div>
            </header>

            <!-- Flash messages -->
            @if(session('success'))
                <div class="alert alert-success" role="alert" style="background: #f0fdf4; border-left: 4px solid #16a34a; color: #16a34a; padding: 1rem 2rem;">
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" role="alert" style="background: #fef2f2; border-left: 4px solid #dc2626; color: #dc2626; padding: 1rem 2rem;">
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="admin-body" id="admin-content" tabindex="-1" style="padding: 2rem; flex: 1;">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
