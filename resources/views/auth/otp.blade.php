<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.otp_title') }} - NIZARI</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nizari.css') }}">
    <style>
        .otp-inputs { display: flex; gap: 10px; justify-content: center; direction: ltr; }
        .otp-inputs input {
            width: 52px; height: 60px; text-align: center; font-size: 1.6rem; font-weight: bold;
            border: 2px solid #dee2e6; border-radius: 10px; transition: border-color .2s;
            font-family: monospace;
        }
        .otp-inputs input:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(201,168,76,.15); }
        .otp-inputs input.filled { border-color: var(--green-dark); background: rgba(27,67,50,.05); }
        .countdown { font-size: 0.85rem; color: #888; }
        .countdown span { color: var(--gold); font-weight: bold; }
    </style>
</head>
<body style="padding-top: 0;">

<div class="auth-page">
    <div class="auth-card" style="max-width: 420px;">
        <div class="auth-logo">
            <span class="logo-ar">النزاري</span>
            <span class="logo-en">NIZARI Rachid</span>
        </div>

        <div class="text-center mb-4">
            <div style="width:60px;height:60px;background:rgba(27,67,50,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                <i class="fas fa-shield-alt" style="font-size:1.5rem;color:var(--green-dark);"></i>
            </div>
            <h5 style="color:var(--green-dark);">{{ __('auth.otp_title') }}</h5>
            <p class="text-muted small mb-0">
                {{ __('auth.otp_instruction') }}<br>
                <strong>{{ session('otp_email') }}</strong>
            </p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
        @endif
        @if(session('info'))
        <div class="alert alert-info text-center small">{{ session('info') }}</div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf
            <div class="mb-4">
                <div class="otp-inputs mb-2" id="otpInputs">
                    @for($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric"
                           class="otp-digit" data-index="{{ $i }}" autocomplete="off">
                    @endfor
                </div>
                <input type="hidden" name="otp" id="otpHidden">
            </div>

            <button type="submit" class="btn btn-gold w-100 btn-lg" id="submitBtn">
                <i class="fas fa-check-circle me-2"></i>{{ __('auth.otp_submit') }}
            </button>
        </form>

        <div class="text-center mt-3">
            <p class="countdown" id="countdownText">
                {{ __('auth.otp_resend') }} {{ app()->getLocale() === 'ar' ? 'بعد' : 'in' }}
                <span id="timer">10:00</span>
            </p>
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" style="display:none;">
                @csrf
                <button type="submit" class="btn btn-link text-gold p-0 small">
                    <i class="fas fa-redo me-1"></i>{{ __('auth.otp_resend') }}
                </button>
            </form>
        </div>

        <hr class="gold-divider">
        <p class="text-center">
            <a href="{{ route('login') }}" class="text-muted small">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-1"></i>
                {{ app()->getLocale() === 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Login' }}
            </a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // OTP digit inputs handler
    const digits = document.querySelectorAll('.otp-digit');
    const hiddenInput = document.getElementById('otpHidden');

    digits.forEach((input, i) => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
            if (this.value && i < digits.length - 1) digits[i + 1].focus();
            if (this.value) this.classList.add('filled');
            else this.classList.remove('filled');
            updateHidden();
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                digits[i - 1].focus();
                digits[i - 1].value = '';
                digits[i - 1].classList.remove('filled');
                updateHidden();
            }
        });
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            paste.split('').forEach((ch, j) => {
                if (digits[j]) { digits[j].value = ch; digits[j].classList.add('filled'); }
            });
            if (digits[paste.length - 1]) digits[paste.length - 1].focus();
            updateHidden();
        });
    });

    function updateHidden() {
        hiddenInput.value = Array.from(digits).map(d => d.value).join('');
    }

    digits[0].focus();

    // Countdown timer (10 minutes)
    let seconds = 600;
    const timerEl = document.getElementById('timer');
    const countdownText = document.getElementById('countdownText');
    const resendForm = document.getElementById('resendForm');

    const countdown = setInterval(() => {
        seconds--;
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        timerEl.textContent = m + ':' + s;
        if (seconds <= 0) {
            clearInterval(countdown);
            countdownText.style.display = 'none';
            resendForm.style.display = 'block';
        }
    }, 1000);
</script>
</body>
</html>
