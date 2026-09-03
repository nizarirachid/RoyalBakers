@extends('layouts.app')
@section('title', 'من نحن - رشيد النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ __('messages.about_title') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">من نحن</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-5">
                <div class="placeholder-img rounded shadow-lg" style="height: 500px; border-radius: 16px;">
                    <div class="text-center">
                        <div style="font-family: var(--font-arabic); font-size: 4rem; color: var(--gold); line-height:1.5; padding: 2rem;">
                            رشيد النزاري<br>
                            <small style="font-size:1.5rem; color: var(--green-dark);">خطاط مغربي</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <span class="section-pretitle">قصتي</span>
                <h2 style="font-family: var(--font-arabic); font-size: 2.5rem; color: var(--green-dark);">رشيد النزاري</h2>
                <p class="text-muted mb-1"><i class="fas fa-map-marker-alt text-gold me-2"></i>مراكش، المغرب</p>
                <hr class="gold-divider">
                <p style="line-height: 2; font-family: var(--font-arabic); font-size: 1.05rem;">
                    {{ __('messages.artist_bio') }}
                </p>
                <p style="line-height: 2; color: var(--text-muted);">
                    يُجسد رشيد النزاري في أعماله روح الفن الإسلامي الأصيل، مُحدثاً الموروث الحضاري المغربي بأسلوب عصري يجمع بين الأصالة والتجديد. تزين لوحاته المنازل والقصور والمساجد والمؤسسات في المغرب والعالم العربي وأوروبا.
                </p>
                <div class="row g-3 mt-2">
                    @foreach([
                        ['icon' => 'fa-graduation-cap', 'text' => 'خريج معهد الفنون'],
                        ['icon' => 'fa-award', 'text' => 'جوائز دولية'],
                        ['icon' => 'fa-globe', 'text' => 'معارض دولية'],
                        ['icon' => 'fa-users', 'text' => '+500 طالب'],
                    ] as $feat)
                    <div class="col-6 col-sm-3">
                        <div class="service-card text-center py-3">
                            <i class="fas {{ $feat['icon'] }} text-gold fa-2x mb-2"></i>
                            <p class="mb-0 small fw-bold">{{ $feat['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Skills --}}
        <div class="zellige-section rounded p-5 mb-5">
            <div class="row text-center text-white g-4">
                @foreach([
                    'الخط المغربي' => 99,
                    'الخط الأندلسي' => 95,
                    'الخط الكوفي' => 90,
                    'الخط النسخ' => 98,
                    'الخط الديواني' => 85,
                    'خط الثلث' => 92,
                ] as $skill => $pct)
                <div class="col-md-4">
                    <div class="mb-1 d-flex justify-content-between">
                        <span>{{ $skill }}</span>
                        <span class="text-gold">{{ $pct }}%</span>
                    </div>
                    <div class="progress" style="height: 8px; background: rgba(255,255,255,0.1);">
                        <div class="progress-bar" style="width: {{ $pct }}%; background: var(--gold); border-radius: 4px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Testimonials --}}
        @if($testimonials->count())
        <div class="section-title">
            <span class="section-pretitle">شهادات العملاء</span>
            <h2>ماذا يقولون عنا</h2>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="testimonial-stars">@for($i=0;$i<$t->rating;$i++)★@endfor</div>
                    <p class="testimonial-text">{{ $t->testimonial }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar-placeholder">{{ mb_substr($t->client_name,0,1) }}</div>
                        <div>
                            <p class="testimonial-name">{{ $t->client_name }}</p>
                            @if($t->client_country)<p class="testimonial-country">{{ $t->client_country }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
