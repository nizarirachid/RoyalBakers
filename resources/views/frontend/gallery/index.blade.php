@extends('layouts.app')
@section('title', 'معرض الأعمال - رشيد النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ __('messages.gallery') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.gallery') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        {{-- Filters --}}
        <div class="row mb-4 g-3 align-items-center">
            <div class="col-md-4">
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-gold btn-sm" data-filter-btn="all">الكل</button>
                    @foreach($categories as $cat)
                        <button class="btn btn-outline-gold btn-sm" data-filter-btn="{{ $cat->id }}">
                            {{ $cat->name_ar }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <form method="GET" class="d-flex gap-2">
                    <select name="style" class="form-select form-select-sm">
                        <option value="">جميع الأساليب</option>
                        <option value="moroccan">الخط المغربي</option>
                        <option value="andalusian">الخط الأندلسي</option>
                        <option value="naskh">خط النسخ</option>
                        <option value="thuluth">خط الثلث</option>
                        <option value="kufic">الخط الكوفي</option>
                    </select>
                    <select name="material" class="form-select form-select-sm">
                        <option value="">جميع المواد</option>
                        <option value="paper">ورق</option>
                        <option value="wood">خشب</option>
                        <option value="glass">زجاج</option>
                        <option value="canvas">قماش</option>
                    </select>
                    <button class="btn btn-gold btn-sm">تصفية</button>
                </form>
            </div>
        </div>

        @if($artworks->count())
        <div class="row g-4">
            @foreach($artworks as $artwork)
            <div class="col-lg-4 col-md-6" data-filter-item="{{ $artwork->category_id }}">
                <div class="artwork-card">
                    <div class="card-img-wrapper">
                        @if($artwork->main_image)
                            <img src="{{ asset('storage/' . $artwork->main_image) }}" alt="{{ $artwork->title_ar }}"
                                data-lightbox="{{ asset('storage/' . $artwork->main_image) }}">
                        @else
                            <div class="placeholder-img" style="height: 260px;">
                                <div style="font-family: var(--font-arabic); font-size: 2rem; color: var(--gold); padding: 1rem; text-align:center; line-height:1.8;">
                                    {{ $artwork->text_content ?? $artwork->title_ar }}
                                </div>
                            </div>
                        @endif
                        <div class="card-overlay">
                            <div class="card-overlay-actions">
                                <a href="{{ route('gallery.show', $artwork->slug) }}" class="btn btn-gold btn-sm">
                                    <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $artwork->title_ar }}</h5>
                        <div class="card-meta d-flex justify-content-between">
                            <span><i class="fas fa-pen-nib me-1 text-gold"></i>{{ $artwork->calligraphy_style }}</span>
                            @if($artwork->price)
                                <strong class="text-gold">{{ number_format($artwork->price, 0) }} MAD</strong>
                            @endif
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ $artwork->views }} مشاهدة
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $artworks->links() }}</div>
        @else
        <div class="text-center py-5">
            <div class="calligraphy-showcase">بسم الله الرحمن الرحيم</div>
            <p class="text-muted mt-3">سيتم إضافة الأعمال قريباً</p>
        </div>
        @endif
    </div>
</section>
@endsection
