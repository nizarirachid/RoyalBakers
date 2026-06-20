@extends('layouts.app')
@section('title', $post->title_ar . ' - مدونة النزاري')
@section('description', $post->meta_description_ar ?? $post->excerpt_ar)

@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <article>
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">المدونة</a></li>
                            <li class="breadcrumb-item active">{{ Str::limit($post->title_ar, 40) }}</li>
                        </ol>
                    </nav>

                    @if($post->featured_image)
                    <div class="mb-4 overflow-hidden rounded-3 shadow">
                        <img src="{{ Storage::url($post->featured_image) }}" class="img-fluid w-100" style="max-height:450px;object-fit:cover;" alt="{{ $post->title_ar }}">
                    </div>
                    @endif

                    <div class="d-flex align-items-center gap-3 mb-4 text-muted small">
                        <span><i class="fas fa-calendar me-1"></i>{{ $post->published_at?->format('Y/m/d') }}</span>
                        <span><i class="fas fa-eye me-1"></i>{{ number_format($post->views) }} مشاهدة</span>
                        <span><i class="fas fa-user me-1"></i>{{ $post->author->name ?? 'النزاري رشيد' }}</span>
                    </div>

                    <h1 class="fw-bold mb-4" style="font-family: 'Amiri', serif; font-size: 2rem; line-height: 1.5;">
                        {{ $post->title_ar }}
                    </h1>

                    <div class="article-content" style="font-size: 1.1rem; line-height: 2; font-family: 'Amiri', serif;">
                        {!! nl2br(e($post->content_ar)) !!}
                    </div>

                    {{-- Social Share --}}
                    <div class="border-top border-bottom py-3 my-4 d-flex align-items-center gap-3">
                        <span class="small fw-bold text-muted">مشاركة:</span>
                        <a href="https://wa.me/?text={{ urlencode($post->title_ar . ' ' . request()->url()) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fab fa-whatsapp me-1"></i>واتساب
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-facebook me-1"></i>فيسبوك
                        </a>
                    </div>
                </article>

                {{-- Related Posts --}}
                @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
                <div class="mt-5">
                    <h4 class="fw-bold text-gold mb-4" style="font-family: 'Amiri', serif;">مقالات ذات صلة</h4>
                    <div class="row g-3">
                        @foreach($relatedPosts as $related)
                        <div class="col-md-6">
                            <div class="blog-card">
                                @if($related->featured_image)
                                <img src="{{ Storage::url($related->featured_image) }}" class="w-100 rounded-top" style="height:150px;object-fit:cover;" alt="">
                                @endif
                                <div class="p-3">
                                    <h6 class="fw-bold mb-1" style="font-family: 'Amiri', serif;">
                                        <a href="{{ route('blog.show', $related->slug) }}" class="text-decoration-none text-dark">{{ $related->title_ar }}</a>
                                    </h6>
                                    <small class="text-muted">{{ $related->published_at?->format('Y/m/d') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="stat-card position-sticky" style="top: 80px;">
                    <h6 class="fw-bold text-gold mb-3">طلب لوحة خطية</h6>
                    <p class="small text-muted">هل أعجبك هذا الفن؟ اطلب لوحة خطية مخصصة بتوقيع النزاري رشيد</p>
                    <a href="{{ route('order.create') }}" class="btn btn-gold btn-sm w-100 mb-3">
                        <i class="fas fa-pen-fancy me-1"></i>اطلب الآن
                    </a>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-arrow-right me-1"></i>كل المقالات
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
