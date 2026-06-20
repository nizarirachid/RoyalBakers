@extends('layouts.app')
@section('title', $artwork->title_ar . ' - معرض النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ $artwork->title_ar }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">معرض الأعمال</a></li>
                <li class="breadcrumb-item active">{{ $artwork->title_ar }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                @if($artwork->main_image)
                    <img src="{{ asset('storage/' . $artwork->main_image) }}" class="img-fluid rounded shadow-lg"
                        alt="{{ $artwork->title_ar }}" data-lightbox="{{ asset('storage/' . $artwork->main_image) }}"
                        style="cursor: zoom-in; width: 100%; border: 2px solid var(--border);">
                @else
                    <div class="calligraphy-showcase" style="min-height: 400px; display:flex; align-items:center; justify-content:center;">
                        {{ $artwork->text_content ?? $artwork->title_ar }}
                    </div>
                @endif

                @if($artwork->gallery_images && count($artwork->gallery_images) > 0)
                <div class="row g-2 mt-2">
                    @foreach($artwork->gallery_images as $img)
                    <div class="col-3">
                        <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded" style="height:80px; object-fit:cover; cursor:zoom-in;"
                            data-lightbox="{{ asset('storage/' . $img) }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="form-card">
                    <h2 style="font-family: var(--font-arabic); color: var(--green-dark);">{{ $artwork->title_ar }}</h2>
                    @if($artwork->title_fr)
                    <p class="text-muted">{{ $artwork->title_fr }}</p>
                    @endif

                    <hr class="gold-divider">

                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-gold" style="width:140px;">أسلوب الخط</th>
                            <td>{{ __('messages.' . $artwork->calligraphy_style) }}</td>
                        </tr>
                        <tr>
                            <th class="text-gold">المادة</th>
                            <td>{{ __('messages.' . $artwork->material) }}</td>
                        </tr>
                        @if($artwork->width_cm && $artwork->height_cm)
                        <tr>
                            <th class="text-gold">المقاس</th>
                            <td>{{ $artwork->width_cm }} × {{ $artwork->height_cm }} سم</td>
                        </tr>
                        @endif
                        @if($artwork->text_content)
                        <tr>
                            <th class="text-gold">النص</th>
                            <td style="font-family: var(--font-arabic);">{{ $artwork->text_content }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="text-gold">المشاهدات</th>
                            <td>{{ number_format($artwork->views) }}</td>
                        </tr>
                    </table>

                    @if($artwork->description_ar)
                    <hr class="gold-divider">
                    <p style="line-height: 1.8; font-family: var(--font-arabic);">{{ $artwork->description_ar }}</p>
                    @endif

                    @if($artwork->is_for_sale && !$artwork->is_sold)
                    <div class="alert" style="background: rgba(201,168,76,0.1); border: 1px solid var(--gold); border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-gold" style="font-size: 1.5rem;">{{ number_format($artwork->price, 0) }} MAD</strong>
                            <span class="badge badge-green">متاح للبيع</span>
                        </div>
                    </div>
                    <a href="{{ route('order.create') }}" class="btn btn-gold w-100 btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>اطلب هذه اللوحة
                    </a>
                    @elseif($artwork->is_sold)
                    <div class="alert alert-secondary">هذا العمل تم بيعه. يمكنك طلب عمل مشابه.</div>
                    <a href="{{ route('order.create') }}" class="btn btn-outline-gold w-100">اطلب عملاً مشابهاً</a>
                    @else
                    <a href="{{ route('order.create') }}" class="btn btn-gold w-100">
                        <i class="fas fa-pen-nib me-2"></i>اطلب عملاً مشابهاً
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Related --}}
        @if($related->count())
        <div class="mt-5">
            <h4 class="mb-4 text-gold">أعمال ذات صلة</h4>
            <div class="row g-4">
                @foreach($related as $r)
                <div class="col-md-3">
                    <a href="{{ route('gallery.show', $r->slug) }}" class="text-decoration-none">
                        <div class="artwork-card">
                            <div class="card-img-wrapper">
                                @if($r->main_image)
                                    <img src="{{ asset('storage/' . $r->main_image) }}" alt="{{ $r->title_ar }}">
                                @else
                                    <div class="placeholder-img" style="height:160px;">
                                        <span style="font-family: var(--font-arabic);">{{ mb_substr($r->title_ar, 0, 8) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ $r->title_ar }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
