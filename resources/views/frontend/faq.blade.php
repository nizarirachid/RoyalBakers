@extends('layouts.app')
@section('title', 'الأسئلة الشائعة - رشيد النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ __('messages.faq') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">الأسئلة الشائعة</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($faqs->count())
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $i => $faq)
                    <div class="accordion-item mb-3 border" style="border-color: var(--border) !important; border-radius: var(--radius-sm) !important; overflow:hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} fw-bold"
                                type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}"
                                style="font-family: var(--font-arabic); color: var(--green-dark); background: {{ $i === 0 ? 'rgba(201,168,76,0.08)' : 'white' }};">
                                <i class="fas fa-question-circle text-gold me-2"></i>
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq{{ $faq->id }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                            data-bs-parent="#faqAccordion">
                            <div class="accordion-body" style="line-height: 1.9; font-family: var(--font-arabic);">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-4x text-gold mb-3"></i>
                    <p class="text-muted">سيتم إضافة الأسئلة الشائعة قريباً</p>
                </div>
                @endif

                <div class="text-center mt-5">
                    <p class="text-muted">لم تجد إجابة على سؤالك؟</p>
                    <a href="{{ route('contact') }}" class="btn btn-gold">تواصل معنا مباشرة</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
