<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة الإدارة') - NIZARI Admin</title>

    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nizari.css') }}">
    @yield('styles')
</head>
<body class="locale-{{ app()->getLocale() }}" style="padding-top: 0;">

<div class="d-flex" style="min-height: 100vh;">
    {{-- Sidebar --}}
    <nav class="admin-sidebar">
        <div class="admin-brand">
            <span class="brand-ar">النزاري</span>
            <small style="color: rgba(255,255,255,0.5); font-size: 0.7rem; display: block; margin-top: 2px;">Admin Panel</small>
        </div>

        <div class="admin-nav-section">
            <span class="admin-nav-label">الرئيسية</span>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                لوحة التحكم
            </a>
        </div>

        <div class="admin-nav-section">
            <span class="admin-nav-label">المحتوى</span>
            <a href="{{ route('admin.artworks.index') }}" class="admin-nav-link {{ request()->routeIs('admin.artworks.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-paint-brush"></i></span>
                الأعمال الفنية
            </a>
            <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-store"></i></span>
                المنتجات
            </a>
            <a href="{{ route('admin.courses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
                الدورات
            </a>
            <a href="{{ route('admin.blog.index') }}" class="admin-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-newspaper"></i></span>
                المدونة
            </a>
        </div>

        <div class="admin-nav-section">
            <span class="admin-nav-label">المبيعات</span>
            <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-shopping-bag"></i></span>
                الطلبات
                @php $newOrders = \App\Models\Order::where('status','new')->count(); @endphp
                @if($newOrders > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.65rem;">{{ $newOrders }}</span>
                @endif
            </a>
        </div>

        <div class="admin-nav-section">
            <span class="admin-nav-label">التواصل</span>
            <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                الرسائل
                @php $newMsgs = \App\Models\ContactMessage::where('status','new')->count(); @endphp
                @if($newMsgs > 0)
                    <span class="badge bg-danger ms-auto" style="font-size:0.65rem;">{{ $newMsgs }}</span>
                @endif
            </a>
        </div>

        @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
        <div class="admin-nav-section">
            <span class="admin-nav-label">الإدارة</span>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                المستخدمون
            </a>
            @if(auth()->user()->hasRole('super-admin'))
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-cog"></i></span>
                الإعدادات
            </a>
            @endif
        </div>
        @endif

        <div class="admin-nav-section border-top" style="border-color: rgba(201,168,76,0.2) !important;">
            <a href="{{ route('home') }}" class="admin-nav-link">
                <span class="nav-icon"><i class="fas fa-globe"></i></span>
                زيارة الموقع
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-nav-link border-0 bg-transparent w-100 text-start"
                    style="font-size:0.9rem; color: rgba(255,100,100,0.8);">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </nav>

    {{-- Main --}}
    <div class="admin-main flex-grow-1">
        <div class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h6 class="mb-0 fw-bold" style="color: var(--green-dark);">@yield('page-title', 'لوحة التحكم')</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-inline">
                    <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    <span class="badge badge-gold ms-2">{{ auth()->user()->getRoleNames()->first() }}</span>
                </span>
                <span class="text-muted small">
                    <i class="far fa-clock me-1"></i>{{ now()->format('H:i') }}
                </span>
            </div>
        </div>

        <div class="admin-content">
            @foreach(['success', 'error', 'warning', 'info'] as $type)
                @if(session($type))
                    <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show">
                        {{ session($type) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/nizari.js') }}"></script>
@yield('scripts')
</body>
</html>
