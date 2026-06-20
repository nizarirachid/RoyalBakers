<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO --}}
    <title>@yield('title', __('messages.hero_title') . ' | ' . config('app.name'))</title>
    <meta name="description" content="@yield('description', __('messages.hero_description'))">
    <meta name="keywords" content="@yield('keywords', 'خط مغربي, خطاط مغربي, نزاري رشيد, calligraphie marocaine, moroccan calligraphy')">
    <meta name="author" content="NIZARI Rachid">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', __('messages.hero_description'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ app()->getLocale() }}_MA">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description" content="@yield('description', __('messages.hero_description'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Schema.org --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "NIZARI Rachid",
        "jobTitle": "Calligrapher",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Marrakech",
            "addressCountry": "MA"
        },
        "url": "{{ config('app.url') }}",
        "sameAs": []
    }
    </script>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    {{-- Bootstrap 5 RTL/LTR --}}
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @endif

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Google Fonts (Arabic & Latin) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Scheherazade+New:wght@400;500;600;700&family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/nizari.css') }}">

    @yield('styles')
</head>
<body class="locale-{{ app()->getLocale() }}">

{{-- Navigation --}}
<nav class="navbar navbar-expand-lg fixed-top navbar-nizari" id="mainNav">
    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="brand-logo">
                <span class="brand-ar">النزاري</span>
                <span class="brand-sub">Calligraphie Marocaine</span>
            </div>
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        {{ __('messages.home') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        {{ __('messages.about') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}" href="{{ route('gallery.index') }}">
                        {{ __('messages.gallery') }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        {{ __('messages.services') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('order.create') }}">{{ __('messages.order') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('courses.index') }}">{{ __('messages.learn') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('shop.index') }}">{{ __('messages.shop') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">
                        {{ __('messages.blog') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">
                        {{ __('messages.faq') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        {{ __('messages.contact') }}
                    </a>
                </li>
            </ul>

            {{-- Right Side --}}
            <div class="navbar-nav ms-auto align-items-center gap-2">
                {{-- Language Switcher --}}
                <div class="dropdown lang-switcher">
                    <button class="btn btn-sm btn-outline-gold dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('locale.set', 'ar') }}">🇲🇦 العربية</a></li>
                        <li><a class="dropdown-item" href="{{ route('locale.set', 'fr') }}">🇫🇷 Français</a></li>
                        <li><a class="dropdown-item" href="{{ route('locale.set', 'en') }}">🇬🇧 English</a></li>
                    </ul>
                </div>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-gold dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'editor']))
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.admin') }}
                                </a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user-circle me-2"></i>{{ __('messages.profile') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-gold">
                        {{ __('messages.login') }}
                    </a>
                    <a href="{{ route('order.create') }}" class="btn btn-sm btn-gold">
                        {{ __('messages.order') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('warning') || session('info'))
<div class="alert-container" style="margin-top: 76px;">
    @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'exclamation-circle' : 'info-circle') }} me-2"></i>
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach
</div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="footer-nizari">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                {{-- Brand --}}
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <h3 class="footer-logo">النزاري</h3>
                        <p class="footer-tagline">{{ __('messages.footer_tagline') }}</p>
                        <p class="footer-bio text-muted">{{ __('messages.artist_bio') }}</p>

                        <div class="social-links mt-3">
                            <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link" title="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="https://wa.me/212663690212" class="social-link" title="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="social-link" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">{{ __('messages.quick_links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                        <li><a href="{{ route('gallery.index') }}">{{ __('messages.gallery') }}</a></li>
                        <li><a href="{{ route('order.create') }}">{{ __('messages.order') }}</a></li>
                        <li><a href="{{ route('courses.index') }}">{{ __('messages.learn') }}</a></li>
                        <li><a href="{{ route('shop.index') }}">{{ __('messages.shop') }}</a></li>
                        <li><a href="{{ route('blog.index') }}">{{ __('messages.blog') }}</a></li>
                        <li><a href="{{ route('faq') }}">{{ __('messages.faq') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact') }}</a></li>
                    </ul>
                </div>

                {{-- Services --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">{{ __('messages.services') }}</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('order.create') }}">{{ __('messages.service_artwork') }}</a></li>
                        <li><a href="{{ route('order.create') }}">{{ __('messages.service_names') }}</a></li>
                        <li><a href="{{ route('order.create') }}">{{ __('messages.service_verses') }}</a></li>
                        <li><a href="{{ route('order.create') }}">{{ __('messages.service_institution') }}</a></li>
                        <li><a href="{{ route('order.create') }}">{{ __('messages.service_digital') }}</a></li>
                        <li><a href="{{ route('courses.index') }}">{{ __('messages.service_education') }}</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">{{ __('messages.contact_info') }}</h5>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>النزاري رشيد، ص.ب 29 أوريكة، إقليم الحوز، مراكش</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:nizarirachid@gmail.com">nizarirachid@gmail.com</a>
                        </li>
                        <li>
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://wa.me/212663690212">+212 663 690 212</a>
                        </li>
                    </ul>

                    <a href="{{ route('order.create') }}" class="btn btn-gold mt-3 w-100">
                        <i class="fas fa-paint-brush me-2"></i>
                        {{ __('messages.order_artwork') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} <strong>NIZARI Rachid</strong> — {{ __('messages.all_rights') }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('sitemap') }}" class="footer-legal-link">Sitemap</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="footer-legal-link">Politique de Confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- AI Assistant Widget --}}
<div class="ai-assistant" id="aiAssistant">
    <button class="ai-toggle-btn" id="aiToggle" title="{{ __('messages.ai_assistant') }}">
        <i class="fas fa-robot"></i>
        <span class="ai-badge">AI</span>
    </button>

    <div class="ai-chat-panel" id="aiPanel" style="display: none;">
        <div class="ai-header">
            <div class="ai-header-info">
                <i class="fas fa-robot"></i>
                <span>{{ __('messages.ai_assistant') }}</span>
            </div>
            <button class="ai-close" id="aiClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="ai-messages" id="aiMessages">
            <div class="ai-message ai-bot">
                <p>{{ __('messages.ai_greeting') }}</p>
            </div>
        </div>
        <div class="ai-input-area">
            <input type="text" id="aiInput" placeholder="{{ __('messages.ai_input_placeholder') }}" class="ai-input">
            <button class="ai-send" id="aiSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

{{-- Scroll to Top --}}
<button class="scroll-top" id="scrollTop" title="العودة للأعلى">
    <i class="fas fa-chevron-up"></i>
</button>

{{-- Bootstrap 5 --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Custom JS --}}
<script src="{{ asset('js/nizari.js') }}"></script>

@yield('scripts')
</body>
</html>
