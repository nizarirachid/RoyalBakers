<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login') }} - NIZARI</title>
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
    <div class="auth-card">
        <div class="auth-logo">
            <span class="logo-ar">النزاري</span>
            <span class="logo-en">NIZARI Rachid</span>
        </div>

        <h5 class="text-center mb-4" style="color: var(--green-dark);">{{ __('auth.login') }}</h5>

        @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('auth.email') }}</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('auth.password_label') }}</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">{{ __('auth.remember_me') }}</label>
                </div>
            </div>
            <button type="submit" class="btn btn-gold w-100 btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('auth.login') }}
            </button>
        </form>

        <hr class="gold-divider">
        <p class="text-center text-muted">
            {{ __('auth.no_account') }}
            <a href="{{ route('register') }}" class="text-gold fw-bold">{{ __('auth.register') }}</a>
        </p>
        <p class="text-center">
            <a href="{{ route('home') }}" class="text-muted small">
                <i class="fas fa-home me-1"></i>العودة للموقع
            </a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
