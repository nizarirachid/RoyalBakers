# دليل الأمان — Security Guide

## الحماية المُطبَّقة

### 1. حماية CSRF
جميع النماذج تستخدم توكن CSRF الخاص بـ Laravel (`@csrf`).

### 2. حماية XSS
- جميع المتغيرات في Blade مُصرَّحة بـ `{{ }}` (تُهرِّب HTML تلقائياً)
- لا يُستخدم `{!! !!}` إلا مع المحتوى الموثوق من المدير

### 3. حماية SQL Injection
- جميع الاستعلامات تستخدم Eloquent ORM أو Query Builder المُحضَّر

### 4. رؤوس HTTP الأمنية (SecurityHeaders Middleware)
```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

### 5. تحديد معدل الطلبات (Rate Limiting)
- تسجيل الدخول: 5 محاولات / دقيقة
- نموذج التواصل: 3 محاولات / 10 دقائق
- API: 60 طلب / دقيقة

### 6. حماية Brute Force
- قفل الحساب بعد 5 محاولات فاشلة متتالية
- فترة القفل: 15 دقيقة
- حقول `login_attempts` و`locked_until` في جدول المستخدمين

### 7. إدارة الجلسات
- الجلسات محمية بـ `HttpOnly` و`Secure` cookies
- التحقق من CSRF لكل طلب POST/PATCH/DELETE

### 8. تغيير كلمة المرور الإجباري
- الحساب الرئيسي يُطلب تغيير كلمة المرور عند أول دخول
- حقل `force_password_change` في جدول المستخدمين

### 9. تحميل الملفات بأمان
- التحقق من نوع MIME وامتداد الملف
- تخزين الملفات خارج المجلد العام (`storage/app/private`)
- حد أقصى لحجم الملف: 10MB

### 10. إخفاء معلومات الإصدار
```env
# في .env الإنتاج
APP_DEBUG=false
APP_ENV=production
```

## توصيات إضافية للإنتاج

### WAF (جدار حماية تطبيق الويب)
يُوصى بـ Cloudflare Free أو ModSecurity مع OWASP CRS.

### نسخ احتياطية
- قاعدة البيانات: يومياً تلقائياً (راجع BACKUP.md)
- مجلد storage/: أسبوعياً

### مراقبة الأمان
تدقيق السجلات في `storage/logs/laravel.log`:
```bash
tail -f storage/logs/laravel.log | grep -E "ERROR|WARNING"
```

### تغيير كلمات المرور الافتراضية
**مهم:** غيّر فوراً بعد النشر:
1. كلمة مرور قاعدة البيانات
2. كلمة مرور حساب nizari
3. مفتاح `.env APP_KEY`

### مراجعة الصلاحيات
```bash
# تأكد من صلاحيات الملفات
find /var/www/nizari -type f -exec chmod 644 {} \;
find /var/www/nizari -type d -exec chmod 755 {} \;
chmod 600 .env
```

## الإبلاغ عن ثغرات أمنية

للإبلاغ عن ثغرة أمنية، تواصل مع:
- البريد: security@nizari.net
- يُرجى عدم الإفصاح العلني قبل التنسيق مع الفريق
