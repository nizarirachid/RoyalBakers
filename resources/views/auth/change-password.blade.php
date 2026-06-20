<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير كلمة المرور - NIZARI</title>
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
        </div>
        <h5 class="text-center mb-2" style="color: var(--green-dark);">{{ __('auth.change_password') }}</h5>

        @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.change.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('auth.new_password') }} *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">{{ __('auth.password_requirements') }}</small>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('auth.confirm_password') }} *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-gold w-100 btn-lg">
                <i class="fas fa-lock me-2"></i>{{ __('auth.change_password') }}
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
