@extends('layouts.app')
@section('title', 'تعلم الخط - النزاري للخط العربي')
@section('description', 'دورات تعليمية احترافية في فن الخط العربي مع الأستاذ النزاري رشيد')

@section('content')
{{-- Page Header --}}
<section class="py-5 mt-5" style="background: linear-gradient(135deg, var(--green-dark) 0%, #2d6a4f 100%);">
    <div class="container text-center text-white py-3">
        <h1 class="display-5 fw-bold mb-2" style="font-family: 'Amiri', serif;">تعلم فن الخط العربي</h1>
        <p class="lead opacity-75">دورات احترافية في الخط العربي مع الأستاذ النزاري رشيد</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">الرئيسية</a></li>
                <li class="breadcrumb-item active text-white">الدورات</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Filter Section --}}
<section class="py-3 border-bottom" style="background: var(--beige);">
    <div class="container">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <select name="level" class="form-select form-select-sm">
                    <option value="">جميع المستويات</option>
                    <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>مبتدئ</option>
                    <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>متوسط</option>
                    <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>متقدم</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">جميع الأنواع</option>
                    <option value="online" {{ request('type') === 'online' ? 'selected' : '' }}>أونلاين</option>
                    <option value="in_person" {{ request('type') === 'in_person' ? 'selected' : '' }}>حضوري</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-gold btn-sm">تصفية</button>
            </div>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">
        @forelse($courses as $course)
        <div class="row g-4 align-items-center mb-5 pb-4 border-bottom">
            <div class="col-md-4">
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="img-fluid rounded-3 shadow" alt="{{ $course->title_ar }}">
                @else
                <div class="zellige-pattern rounded-3 d-flex align-items-center justify-content-center" style="height: 250px;">
                    <i class="fas fa-graduation-cap" style="font-size: 4rem; color: var(--gold);"></i>
                </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="d-flex gap-2 mb-2">
                    <span class="badge" style="background: var(--gold); color: var(--green-dark);">{{ $course->level }}</span>
                    <span class="badge bg-secondary">{{ $course->type === 'online' ? 'أونلاين' : ($course->type === 'in_person' ? 'حضوري' : 'هجين') }}</span>
                    @if($course->price == 0)
                    <span class="badge bg-success">مجاني</span>
                    @endif
                </div>
                <h2 class="fw-bold mb-3" style="font-family: 'Amiri', serif;">{{ $course->title_ar }}</h2>
                <p class="text-muted mb-3" style="line-height: 1.8;">{{ Str::limit($course->description_ar, 200) }}</p>
                <div class="d-flex gap-4 mb-4 text-muted small">
                    <span><i class="fas fa-clock me-1"></i>{{ $course->duration_hours }} ساعة</span>
                    <span><i class="fas fa-users me-1"></i>{{ $course->enrolled_count ?? 0 }} مسجّل</span>
                    @if($course->max_students)
                    <span><i class="fas fa-chair me-1"></i>{{ $course->max_students - ($course->enrolled_count ?? 0) }} مقعد متاح</span>
                    @endif
                    @if($course->start_date)
                    <span><i class="fas fa-calendar me-1"></i>{{ $course->start_date->format('Y/m/d') }}</span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold fs-4 text-gold">
                        {{ $course->price == 0 ? 'مجاناً' : number_format($course->price) . ' MAD' }}
                    </span>
                    <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-gold">
                        <i class="fas fa-info-circle me-1"></i>تفاصيل الدورة
                    </a>
                    @auth
                    <form method="POST" action="{{ route('courses.enroll', $course->slug) }}">
                        @csrf
                        <button class="btn btn-outline-gold">التسجيل</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-graduation-cap text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3 fs-5">لا توجد دورات متاحة حالياً</p>
            <a href="{{ route('contact') }}" class="btn btn-gold mt-2">تواصل معنا</a>
        </div>
        @endforelse

        <div class="mt-4">{{ $courses->links() }}</div>
    </div>
</section>
@endsection
