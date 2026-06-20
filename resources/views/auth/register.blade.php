<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.register') }} - NIZARI</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nizari.css') }}">
</head>
<body style="padding-top: 0;">

<div class="auth-page">
    <div class="auth-card" style="max-width: 550px;">
        <div class="auth-logo">
            <span class="logo-ar">النزاري</span>
            <span class="logo-en">NIZARI Rachid</span>
        </div>
        <h5 class="text-center mb-4" style="color: var(--green-dark);">{{ __('auth.register') }}</h5>

        @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('auth.email') }} *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('auth.password_label') }} *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">{{ __('auth.password_requirements') }}</small>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('auth.confirm_password') }} *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الهاتف</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الدولة</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-gold w-100 btn-lg">
                        <i class="fas fa-user-plus me-2"></i>{{ __('auth.register') }}
                    </button>
                </div>
            </div>
        </form>

        <hr class="gold-divider">
        <p class="text-center text-muted">
            {{ __('auth.have_account') }}
            <a href="{{ route('login') }}" class="text-gold fw-bold">{{ __('auth.login') }}</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
