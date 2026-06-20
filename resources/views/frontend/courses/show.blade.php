@extends('layouts.app')
@section('title', $course->title_ar . ' - دورات النزاري')

@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الدورات</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($course->title_ar, 40) }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-8">
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded-3 shadow mb-4 w-100" style="max-height:400px;object-fit:cover;" alt="{{ $course->title_ar }}">
                @endif

                <div class="d-flex gap-2 mb-3">
                    <span class="badge" style="background: var(--gold); color: var(--green-dark);">{{ $course->level }}</span>
                    <span class="badge bg-secondary">{{ $course->type === 'online' ? 'أونلاين' : ($course->type === 'in_person' ? 'حضوري' : 'هجين') }}</span>
                </div>

                <h1 class="fw-bold mb-4" style="font-family: 'Amiri', serif; font-size: 2.2rem;">{{ $course->title_ar }}</h1>

                <div class="row g-3 mb-4 text-center">
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background: var(--beige);">
                            <i class="fas fa-clock text-gold d-block mb-1"></i>
                            <strong class="d-block">{{ $course->duration_hours }}</strong>
                            <small class="text-muted">ساعة</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background: var(--beige);">
                            <i class="fas fa-users text-gold d-block mb-1"></i>
                            <strong class="d-block">{{ $course->enrolled_count ?? 0 }}</strong>
                            <small class="text-muted">مسجّل</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background: var(--beige);">
                            <i class="fas fa-calendar text-gold d-block mb-1"></i>
                            <strong class="d-block">{{ $course->start_date ? $course->start_date->format('m/Y') : '-' }}</strong>
                            <small class="text-muted">تاريخ البدء</small>
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold text-gold mb-3">عن الدورة</h4>
                <div style="line-height: 2; font-size: 1.05rem; font-family: 'Amiri', serif;">
                    {!! nl2br(e($course->description_ar)) !!}
                </div>

                @if($course->curriculum)
                <div class="mt-4 p-4 rounded-3" style="background: var(--beige);">
                    <h5 class="fw-bold text-gold mb-3"><i class="fas fa-list me-2"></i>المنهج الدراسي</h5>
                    @if(is_array($course->curriculum))
                    @foreach($course->curriculum as $item)
                    <div class="d-flex gap-2 mb-2">
                        <i class="fas fa-check text-gold mt-1 flex-shrink-0"></i>
                        <small>{{ is_array($item) ? ($item['title'] ?? $item) : $item }}</small>
                    </div>
                    @endforeach
                    @else
                    <p class="small mb-0">{{ $course->curriculum }}</p>
                    @endif
                </div>
                @endif

                @if($course->lessons->isNotEmpty())
                <div class="mt-4">
                    <h5 class="fw-bold text-gold mb-3"><i class="fas fa-play me-2"></i>محتوى الدورة</h5>
                    <div class="accordion" id="lessonsAccordion">
                        @foreach($course->lessons as $i => $lesson)
                        <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#lesson{{ $i }}">
                                    <i class="fas fa-play-circle text-gold me-2"></i>
                                    {{ $lesson->title_ar }}
                                </button>
                            </h2>
                            <div id="lesson{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#lessonsAccordion">
                                <div class="accordion-body small text-muted">
                                    {{ $lesson->description_ar ?? 'تفاصيل الدرس ستُكشف عند التسجيل' }}
                                    @if($lesson->duration_minutes)
                                    <span class="badge bg-secondary ms-2">{{ $lesson->duration_minutes }} دقيقة</span>
                                    @endif
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
                    <div class="text-center mb-4">
                        <span class="fw-bold d-block" style="font-size: 2.5rem; color: var(--gold);">
                            {{ $course->price == 0 ? 'مجاناً' : number_format($course->price) . ' MAD' }}
                        </span>
                        @if($course->max_students)
                        <small class="text-muted">
                            {{ $course->max_students - ($course->enrolled_count ?? 0) }} مقعد متبقي
                        </small>
                        @endif
                    </div>

                    @auth
                        @if($isEnrolled ?? false)
                        <div class="alert alert-success text-center small mb-3">
                            <i class="fas fa-check-circle me-1"></i>أنت مسجّل في هذه الدورة
                        </div>
                        @else
                        <form method="POST" action="{{ route('courses.enroll', $course->slug) }}">
                            @csrf
                            <button class="btn btn-gold w-100 mb-3 py-2">
                                <i class="fas fa-graduation-cap me-2"></i>سجّل الآن
                            </button>
                        </form>
                        @endif
                    @else
                    <a href="{{ route('login') }}" class="btn btn-gold w-100 mb-3 py-2">
                        <i class="fas fa-sign-in-alt me-2"></i>سجّل دخولك للتسجيل
                    </a>
                    @endauth

                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary w-100 mb-3">
                        <i class="fas fa-question-circle me-1"></i>استفسار عن الدورة
                    </a>

                    <hr class="gold-divider">
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>شهادة إتمام الدورة</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>إشراف مباشر من الأستاذ</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>مواد تعليمية مرافقة</li>
                        @if($course->start_date)
                        <li class="mb-2"><i class="fas fa-calendar text-gold me-2"></i>بداية: {{ $course->start_date->format('Y/m/d') }}</li>
                        @endif
                        @if($course->location)
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-gold me-2"></i>{{ $course->location }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
