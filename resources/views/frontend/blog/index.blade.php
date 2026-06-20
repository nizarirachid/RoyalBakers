@extends('layouts.app')
@section('title', 'المدونة - النزاري للخط العربي')
@section('description', 'مقالات ودروس حول فن الخط العربي الأصيل')

@section('content')
{{-- Page Header --}}
<section class="py-5 mt-5" style="background: linear-gradient(135deg, var(--green-dark) 0%, #2d6a4f 100%);">
    <div class="container text-center text-white py-3">
        <h1 class="display-5 fw-bold mb-2" style="font-family: 'Amiri', serif;">مدونة الخط العربي</h1>
        <p class="lead opacity-75">مقالات ودروس في فن الخط الأصيل</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">الرئيسية</a></li>
                <li class="breadcrumb-item active text-white">المدونة</li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                @forelse($posts as $post)
                <article class="blog-card mb-4">
                    @if($post->featured_image)
                    <div class="overflow-hidden" style="height: 260px; border-radius: 12px 12px 0 0;">
                        <img src="{{ Storage::url($post->featured_image) }}" class="w-100 h-100 object-fit-cover blog-card-img" alt="{{ $post->title_ar }}">
                    </div>
                    @endif
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                            <span><i class="fas fa-calendar me-1"></i>{{ $post->published_at?->format('Y/m/d') }}</span>
                            <span><i class="fas fa-eye me-1"></i>{{ number_format($post->views) }}</span>
                            <span><i class="fas fa-comments me-1"></i>{{ $post->comments->count() }}</span>
                        </div>
                        <h2 class="h4 fw-bold mb-3" style="font-family: 'Amiri', serif;">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                {{ $post->title_ar }}
                            </a>
                        </h2>
                        @if($post->excerpt_ar)
                        <p class="text-muted" style="line-height: 1.8;">{{ $post->excerpt_ar }}</p>
                        @endif
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-gold btn-sm">
                            قراءة المزيد <i class="fas fa-arrow-left ms-1"></i>
                        </a>
                    </div>
                </article>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-newspaper text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">لا توجد مقالات حتى الآن</p>
                </div>
                @endforelse

                <div class="mt-4">{{ $posts->links() }}</div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="stat-card mb-4 position-sticky" style="top: 80px;">
                    <h6 class="fw-bold text-gold mb-3"><i class="fas fa-star me-2"></i>المقالات المميزة</h6>
                    @foreach($recentPosts ?? [] as $recent)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        @if($recent->featured_image)
                        <img src="{{ Storage::url($recent->featured_image) }}" class="rounded" style="width:60px;height:50px;object-fit:cover;" alt="">
                        @endif
                        <div>
                            <a href="{{ route('blog.show', $recent->slug) }}" class="text-decoration-none fw-bold small text-dark">
                                {{ Str::limit($recent->title_ar, 50) }}
                            </a>
                            <small class="d-block text-muted">{{ $recent->published_at?->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-4">
                        <h6 class="fw-bold text-gold mb-3"><i class="fas fa-tags me-2"></i>روابط سريعة</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('gallery.index') }}" class="btn btn-outline-gold btn-sm">المعرض</a>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-gold btn-sm">الدورات</a>
                            <a href="{{ route('order.create') }}" class="btn btn-outline-gold btn-sm">اطلب لوحة</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
