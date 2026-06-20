<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق - NIZARI</title>
    <style>
        body { font-family: 'Arial', sans-serif; background: #f5f0e8; margin: 0; padding: 0; direction: rtl; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1B4332 0%, #2d6a4f 100%); padding: 32px 40px; text-align: center; }
        .header h1 { color: #C9A84C; font-size: 28px; margin: 0 0 4px; letter-spacing: 2px; }
        .header p { color: rgba(255,255,255,0.75); margin: 0; font-size: 13px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #1B4332; font-weight: bold; margin-bottom: 16px; }
        .text { color: #555; font-size: 14px; line-height: 1.8; margin-bottom: 24px; }
        .otp-box { background: #f5f0e8; border: 2px dashed #C9A84C; border-radius: 10px; text-align: center; padding: 24px; margin: 24px 0; }
        .otp-code { font-size: 42px; font-weight: bold; color: #1B4332; letter-spacing: 10px; font-family: monospace; }
        .otp-note { color: #888; font-size: 12px; margin-top: 8px; }
        .warning { background: #fff8e1; border-right: 4px solid #C9A84C; padding: 12px 16px; border-radius: 4px; color: #6d4c00; font-size: 13px; margin-top: 20px; }
        .footer { background: #1B4332; padding: 20px 40px; text-align: center; }
        .footer p { color: rgba(255,255,255,0.5); font-size: 11px; margin: 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>النزاري</h1>
        <p>NIZARI Rachid — Calligraphie Marocaine</p>
    </div>
    <div class="body">
        <p class="greeting">مرحباً {{ $name }}،</p>
        <p class="text">
            تلقّينا طلب تسجيل دخول إلى لوحة تحكم موقع النزاري.<br>
            أدخل الرمز التالي لإتمام عملية الدخول:
        </p>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
            <div class="otp-note">صالح لمدة 10 دقائق فقط — Valable 10 minutes</div>
        </div>

        <div class="warning">
            <strong>تنبيه أمني:</strong> لا تشارك هذا الرمز مع أي شخص. فريق النزاري لن يطلب منك هذا الرمز أبداً.
        </div>

        <p class="text" style="margin-top: 24px; font-size: 13px; color: #888;">
            إذا لم تطلب تسجيل الدخول، تجاهل هذه الرسالة وقم بتغيير كلمة مرورك فوراً.
        </p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} NIZARI Rachid — Rachid.nizari.net</p>
        <p style="margin-top: 4px;">nizarirachid@gmail.com | +212 663 690 212</p>
    </div>
</div>
</body>
</html>
