@extends('layouts.app')
@section('title', 'المتجر - النزاري للخط العربي')
@section('description', 'اكتشف مجموعة المنتجات الرقمية والمادية للخط العربي الأصيل')

@section('content')
{{-- Page Header --}}
<section class="py-5 mt-5" style="background: linear-gradient(135deg, var(--green-dark) 0%, #2d6a4f 100%);">
    <div class="container text-center text-white py-3">
        <h1 class="display-5 fw-bold mb-2" style="font-family: 'Amiri', serif;">المتجر</h1>
        <p class="lead opacity-75">منتجات رقمية ومادية أصيلة للخط العربي</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">الرئيسية</a></li>
                <li class="breadcrumb-item active text-white">المتجر</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            {{-- Sidebar Filters --}}
            <div class="col-lg-3">
                <div class="stat-card position-sticky" style="top: 80px;">
                    <h6 class="fw-bold text-gold mb-3"><i class="fas fa-filter me-2"></i>تصفية النتائج</h6>
                    <form method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">نوع المنتج</label>
                            <div>
                                @foreach([''=>'الكل', 'digital'=>'رقمي', 'physical'=>'مادي', 'template'=>'قوالب'] as $val => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" value="{{ $val }}"
                                        id="type_{{ $val }}" {{ request('type', '') === $val ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="type_{{ $val }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">ترتيب حسب</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>السعر: الأقل أولاً</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>السعر: الأعلى أولاً</option>
                            </select>
                        </div>
                        <button class="btn btn-gold btn-sm w-100">تطبيق</button>
                    </form>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="col-lg-9">
                @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">لا توجد منتجات حالياً</p>
                </div>
                @else
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 artwork-card">
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                @if($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name_ar }}">
                                @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center zellige-pattern">
                                    <i class="fas fa-box" style="font-size: 3rem; color: var(--gold); opacity: 0.5;"></i>
                                </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge" style="background: var(--gold); color: var(--green-dark);">
                                        {{ $product->type === 'digital' ? 'رقمي' : ($product->type === 'template' ? 'قالب' : 'مادي') }}
                                    </span>
                                </div>
                                @if($product->sale_price)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-danger">خصم</span>
                                </div>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-2" style="font-family: 'Amiri', serif;">{{ $product->name_ar }}</h6>
                                <p class="small text-muted flex-grow-1">{{ Str::limit($product->description_ar, 80) }}</p>
                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                    <div>
                                        @if($product->sale_price)
                                        <span class="text-muted text-decoration-line-through small">{{ number_format($product->price) }} MAD</span>
                                        <span class="fw-bold text-gold"> {{ number_format($product->sale_price) }} MAD</span>
                                        @else
                                        <span class="fw-bold text-gold">{{ number_format($product->price) }} MAD</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-gold btn-sm">
                                        عرض <i class="fas fa-arrow-left ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
