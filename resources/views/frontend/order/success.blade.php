@extends('layouts.app')
@section('title', 'تم إرسال الطلب بنجاح')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>تم استقبال طلبك</h1>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="form-card text-center">
                    <div style="font-size: 5rem; color: var(--gold); margin-bottom: 1rem;">✓</div>
                    <h2 style="color: var(--green-dark); font-family: var(--font-arabic);">شكراً لك!</h2>
                    <p class="lead text-muted">تم استقبال طلبك بنجاح وسيتم التواصل معك في أقرب وقت.</p>

                    <div class="p-3 bg-beige rounded mt-3 mb-4">
                        <p class="mb-1"><strong>رقم الطلب:</strong> <span class="text-gold fw-bold">{{ $order->order_number }}</span></p>
                        <p class="mb-0 text-muted small">احتفظ بهذا الرقم للمتابعة</p>
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('home') }}" class="btn btn-green">العودة للرئيسية</a>
                        <a href="{{ route('gallery.index') }}" class="btn btn-outline-gold">استكشف الأعمال</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
