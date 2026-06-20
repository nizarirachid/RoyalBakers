@extends('layouts.app')

@section('title', __('messages.hero_title') . ' | خطاط ومغربي من مراكش')
@section('description', 'رشيد النزاري - خطاط وفنان مغربي متخصص في الخط المغربي والأندلسي والعربي الكلاسيكي من مراكش')

@section('content')

{{-- Hero Section --}}
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <p class="hero-pretitle">NIZARI Rachid | Calligraphe Marocain</p>
            <div class="hero-ornament">❋ ✦ ❋</div>
            <h1 class="hero-title">رشيد النزاري</h1>
            <p class="hero-title-en">Moroccan Calligrapher & Artist</p>
            <div class="hero-ornament">۞</div>
            <p class="hero-desc">{{ __('messages.hero_description') }}</p>
            <div class="hero-actions">
                <a href="{{ route('gallery.index') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-images me-2"></i>{{ __('messages.discover_works') }}
                </a>
                <a href="{{ route('order.create') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-pen-nib me-2"></i>{{ __('messages.order_artwork') }}
                </a>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <i class="fas fa-chevron-down fa-2x"></i>
    </div>
</section>

{{-- About Artist Strip --}}
<section class="py-5 bg-beige">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="position-relative">
                    <div class="placeholder-img rounded-lg shadow-lg" style="height: 450px; border-radius: 16px;">
                        <i class="fas fa-user-circle" style="font-size: 6rem; opacity: 0.3;"></i>
                    </div>
                    <div class="position-absolute" style="bottom: -20px; {{ app()->getLocale() === 'ar' ? 'left: -20px;' : 'right: -20px;' }}">
                        <div class="bg-green-dark text-center p-3 rounded-lg shadow" style="min-width: 120px;">
                            <div class="stat-number text-gold" style="font-size: 2.5rem;" data-target="15" data-suffix="+">15+</div>
                            <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7);">سنوات خبرة</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <span class="section-pretitle">{{ __('messages.about') }}</span>
                <h2 class="display-5 fw-bold mb-3" style="font-family: var(--font-arabic); color: var(--green-dark);">
                    رشيد النزاري
                </h2>
                <p class="text-muted mb-2" style="font-size: 0.9rem; letter-spacing: 1px;">
                    <i class="fas fa-map-marker-alt text-gold me-2"></i>مراكش، المغرب
                </p>
                <hr class="gold-divider">
                <p class="lead" style="line-height: 1.9; font-family: var(--font-arabic);">
                    {{ __('messages.artist_bio') }}
                </p>
                <div class="row g-3 mt-2">
                    @foreach(['الخط المغربي', 'الخط الأندلسي', 'الخط العربي الكلاسيكي', 'اللوحات الفنية', 'الكتابة على الخشب', 'الكتابة على الزجاج'] as $skill)
                    <div class="col-auto">
                        <span class="badge" style="background: var(--beige-dark); color: var(--green-dark); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; border: 1px solid var(--border);">
                            ✦ {{ $skill }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('about') }}" class="btn btn-green me-3">
                        {{ __('messages.about') }} <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-gold">
                        {{ __('messages.contact') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Artworks --}}
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <span class="section-pretitle">أحدث الأعمال</span>
            <h2>{{ __('messages.gallery') }}</h2>
            <div class="section-divider">
                <span class="line"></span>
                <span class="ornament">✦</span>
                <span class="line"></span>
            </div>
            <p class="text-muted">أعمال فنية أصيلة بالخط المغربي والعربي الكلاسيكي</p>
        </div>

        @if($featuredArtworks->count())
        <div class="row g-4">
            @foreach($featuredArtworks as $artwork)
            <div class="col-lg-4 col-md-6">
                <div class="artwork-card">
                    <div class="card-img-wrapper">
                        @if($artwork->main_image && file_exists(public_path('storage/' . $artwork->main_image)))
                            <img src="{{ asset('storage/' . $artwork->main_image) }}" alt="{{ $artwork->title_ar }}" data-lightbox="{{ asset('storage/' . $artwork->main_image) }}">
                        @else
                            <div class="placeholder-img" style="height: 240px;">
                                <span style="font-family: var(--font-arabic); font-size: 2rem; color: var(--gold); opacity: 0.5;">
                                    {{ mb_substr($artwork->text_content ?? $artwork->title_ar, 0, 10) }}
                                </span>
                            </div>
                        @endif
                        <div class="card-overlay">
                            <div class="card-overlay-actions">
                                <a href="{{ route('gallery.show', $artwork->slug) }}" class="btn btn-gold btn-sm">
                                    <i class="fas fa-eye me-1"></i>عرض
                                </a>
                                @if($artwork->is_for_sale && !$artwork->is_sold)
                                <a href="{{ route('order.create') }}" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-shopping-cart me-1"></i>اطلب
                                </a>
                                @endif
                            </div>
                        </div>
                        @if($artwork->is_for_sale && !$artwork->is_sold)
                            <span class="card-badge">للبيع</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $artwork->title_ar }}</h5>
                        <div class="card-meta d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-tag me-1 text-gold"></i>
                                {{ __('messages.' . $artwork->calligraphy_style) }}
                            </span>
                            @if($artwork->price)
                                <strong class="text-gold">{{ number_format($artwork->price, 0) }} MAD</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        {{-- Placeholder artworks --}}
        <div class="row g-4">
            @php
            $placeholders = [
                ['title' => 'بسم الله الرحمن الرحيم', 'style' => 'الخط المغربي', 'material' => 'ورق عالي الجودة'],
                ['title' => 'وما توفيقي إلا بالله', 'style' => 'الخط الأندلسي', 'material' => 'خشب الزيتون'],
                ['title' => 'يا مراكش', 'style' => 'الخط الثلث', 'material' => 'قماش فاخر'],
                ['title' => 'الحمد لله', 'style' => 'الخط الكوفي', 'material' => 'ورق مغربي'],
                ['title' => 'مراكش الحمراء', 'style' => 'الخط المغربي', 'material' => 'زجاج'],
                ['title' => 'الله نور السماوات والأرض', 'style' => 'الخط النسخ', 'material' => 'جبس'],
            ];
            @endphp
            @foreach($placeholders as $i => $p)
            <div class="col-lg-4 col-md-6">
                <div class="artwork-card">
                    <div class="card-img-wrapper">
                        <div class="placeholder-img" style="height: 260px; background: linear-gradient(135deg, #{{ ['F5F0E8','E8DFD0','F0EBE0','EDE6D8','E5DDD0','F8F3EC'][$i] }} 0%, #E8DFD0 100%);">
                            <div class="text-center p-3">
                                <div style="font-family: var(--font-arabic); font-size: 1.8rem; color: var(--green-dark); line-height: 1.8;">
                                    {{ $p['title'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $p['title'] }}</h5>
                        <div class="card-meta d-flex justify-content-between">
                            <span><i class="fas fa-pen-nib me-1 text-gold"></i>{{ $p['style'] }}</span>
                            <span class="text-muted">{{ $p['material'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('gallery.index') }}" class="btn btn-outline-gold btn-lg">
                <i class="fas fa-images me-2"></i>عرض جميع الأعمال
            </a>
        </div>
    </div>
</section>

{{-- Services --}}
<section class="py-5 bg-beige">
    <div class="container">
        <div class="section-title">
            <span class="section-pretitle">ما نقدمه</span>
            <h2>{{ __('messages.services_title') }}</h2>
            <div class="section-divider">
                <span class="line"></span>
                <span class="ornament">❋</span>
                <span class="line"></span>
            </div>
        </div>

        <div class="row g-4">
            @php
            $services = [
                ['icon' => 'fa-paint-brush', 'key' => 'service_artwork', 'desc' => 'لوحات فنية مخصصة بأسلوبك المفضل وبالمقاسات التي تريدها'],
                ['icon' => 'fa-signature', 'key' => 'service_names', 'desc' => 'كتابة الأسماء بخطوط عربية جميلة على مختلف المواد'],
                ['icon' => 'fa-book-open', 'key' => 'service_verses', 'desc' => 'كتابة الآيات القرآنية والأحاديث النبوية الشريفة'],
                ['icon' => 'fa-building', 'key' => 'service_institution', 'desc' => 'تصميم لوحات احترافية للمؤسسات والشركات والمساجد'],
                ['icon' => 'fa-home', 'key' => 'service_home', 'desc' => 'لوحات ديكور راقية لتزيين المنازل والقصور'],
                ['icon' => 'fa-file-image', 'key' => 'service_digital', 'desc' => 'تحويل الأعمال إلى نسخ رقمية PNG/PDF/SVG عالية الدقة'],
                ['icon' => 'fa-chalkboard-teacher', 'key' => 'service_education', 'desc' => 'دروس خط عربي فردية وجماعية حضورياً وأونلاين'],
            ];
            @endphp

            @foreach($services as $service)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas {{ $service['icon'] }}"></i>
                    </div>
                    <h4>{{ __('messages.' . $service['key']) }}</h4>
                    <p class="text-muted" style="font-size: 0.87rem;">{{ $service['desc'] }}</p>
                    <a href="{{ route('order.create') }}" class="btn btn-outline-gold btn-sm mt-2">
                        اطلب الآن
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats - Zellige Pattern --}}
<section class="zellige-section">
    <div class="container">
        <div class="row g-4">
            @foreach([
                ['number' => 500, 'suffix' => '+', 'label' => 'عمل فني منجز'],
                ['number' => 15, 'suffix' => '+', 'label' => 'سنة من الخبرة'],
                ['number' => 50, 'suffix' => '+', 'label' => 'دولة تم الشحن إليها'],
                ['number' => 98, 'suffix' => '%', 'label' => 'رضا العملاء'],
            ] as $stat)
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number" data-target="{{ $stat['number'] }}" data-suffix="{{ $stat['suffix'] }}">
                        {{ $stat['number'] }}{{ $stat['suffix'] }}
                    </div>
                    <div class="stat-label">{{ $stat['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Courses Teaser --}}
@if($activeCourses->count())
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <span class="section-pretitle">تعلم الخط</span>
            <h2>{{ __('messages.learn') }}</h2>
            <div class="section-divider">
                <span class="line"></span>
                <span class="ornament">✦</span>
                <span class="line"></span>
            </div>
        </div>
        <div class="row g-4">
            @foreach($activeCourses as $course)
            <div class="col-md-4">
                <div class="blog-card h-100">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="blog-img" alt="{{ $course->title_ar }}">
                    @else
                        <div class="placeholder-img" style="height: 200px;"><i class="fas fa-graduation-cap fa-3x"></i></div>
                    @endif
                    <div class="blog-body">
                        <span class="badge badge-green mb-2">{{ ucfirst($course->type) }}</span>
                        <h5 class="blog-title">{{ $course->title_ar }}</h5>
                        <p class="text-muted small">{{ Str::limit($course->description_ar, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <strong class="text-gold">{{ number_format($course->price, 0) }} MAD</strong>
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-gold btn-sm">التفاصيل</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('courses.index') }}" class="btn btn-outline-gold">عرض جميع الدورات</a>
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if($testimonials->count())
<section class="py-5 bg-beige">
    <div class="container">
        <div class="section-title">
            <span class="section-pretitle">آراء العملاء</span>
            <h2>ماذا يقولون عنا</h2>
            <div class="section-divider">
                <span class="line"></span>
                <span class="ornament">★</span>
                <span class="line"></span>
            </div>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        @for($i = 0; $i < $t->rating; $i++) ★ @endfor
                    </div>
                    <p class="testimonial-text">{{ $t->testimonial }}</p>
                    <div class="testimonial-author">
                        @if($t->client_avatar)
                            <img src="{{ asset('storage/' . $t->client_avatar) }}" class="testimonial-avatar" alt="{{ $t->client_name }}">
                        @else
                            <div class="testimonial-avatar-placeholder">
                                {{ mb_substr($t->client_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="testimonial-name">{{ $t->client_name }}</p>
                            @if($t->client_country)
                            <p class="testimonial-country">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $t->client_country }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Blog Teaser --}}
@if($latestPosts->count())
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <span class="section-pretitle">آخر المقالات</span>
            <h2>{{ __('messages.blog') }}</h2>
            <div class="section-divider">
                <span class="line"></span>
                <span class="ornament">✦</span>
                <span class="line"></span>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestPosts as $post)
            <div class="col-md-4">
                <div class="blog-card">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="blog-img" alt="{{ $post->title_ar }}">
                    @else
                        <div class="placeholder-img" style="height: 200px;"><i class="fas fa-newspaper fa-3x"></i></div>
                    @endif
                    <div class="blog-body">
                        <span class="blog-category">الخط العربي</span>
                        <h5 class="blog-title">{{ $post->title_ar }}</h5>
                        <p class="blog-meta">
                            <i class="far fa-calendar me-1"></i>
                            {{ $post->published_at?->format('Y/m/d') }}
                            <span class="mx-2">·</span>
                            <i class="far fa-eye me-1"></i>{{ $post->views }}
                        </p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-gold btn-sm mt-2">
                            قراءة المزيد
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="btn btn-outline-gold">جميع المقالات</a>
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="zellige-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div style="font-family: var(--font-arabic); font-size: 3rem; color: var(--gold); margin-bottom: 1rem; opacity: 0.5;">
                    ✦ ۩ ✦
                </div>
                <h2 style="color: var(--gold); font-family: var(--font-arabic); font-size: 2.5rem;">
                    هل لديك فكرة؟ دعنا نحولها إلى تحفة فنية
                </h2>
                <p style="color: rgba(255,255,255,0.75); font-size: 1.1rem; margin: 1.5rem 0;">
                    اتصل بنا الآن واحصل على استشارة مجانية لعملك الفني
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('order.create') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-pen-nib me-2"></i>اطلب عملاً فنياً
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>تواصل معنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
