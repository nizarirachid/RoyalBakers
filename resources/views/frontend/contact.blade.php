@extends('layouts.app')
@section('title', 'اتصل بنا - رشيد النزاري')
@section('content')

<div class="pattern-header">
    <div class="container">
        <h1>{{ __('messages.contact') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item active">اتصل بنا</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="form-card h-100">
                    <h4 class="text-gold mb-4">معلومات التواصل</h4>

                    <div class="d-flex gap-3 mb-4">
                        <div class="service-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.2rem;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">الموقع</h6>
                            <p class="text-muted mb-0">النزاري رشيد، صندوق البريد 29 اثنين أوريكة، إقليم الحوز، مراكش، المغرب</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="service-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.2rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">البريد الإلكتروني</h6>
                            <a href="mailto:nizarirachid@gmail.com" class="text-muted">nizarirachid@gmail.com</a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="service-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.2rem;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">واتساب</h6>
                            <a href="https://wa.me/212663690212" class="text-muted">+212 663 690 212</a>
                        </div>
                    </div>

                    <hr class="gold-divider">

                    <h6 class="fw-bold mb-3">تابعنا على</h6>
                    <div class="social-links">
                        <a href="#" class="social-link" style="background: rgba(27,67,50,0.1); color: var(--green-dark);">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" style="background: rgba(27,67,50,0.1); color: var(--green-dark);">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" style="background: rgba(27,67,50,0.1); color: var(--green-dark);">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link" style="background: rgba(27,67,50,0.1); color: var(--green-dark);">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="form-card">
                    <h4 class="mb-4" style="color: var(--green-dark); font-family: var(--font-arabic);">
                        <i class="fas fa-envelope text-gold me-2"></i>أرسل لنا رسالة
                    </h4>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', auth()->user()?->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الهاتف</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الموضوع</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                                    placeholder="مثال: طلب استفسار عن أسعار اللوحات">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الرسالة *</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                                    rows="6" required placeholder="اكتب رسالتك هنا...">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>إرسال الرسالة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
