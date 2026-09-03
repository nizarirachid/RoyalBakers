# النزاري للخط العربي — NIZARI Arabic Calligraphy

موقع احترافي لفنان الخط العربي المغربي النزاري رشيد.

## المواصفات التقنية

| المكوّن | التقنية |
|---------|---------|
| Framework | Laravel 13 (PHP 8.4) |
| Frontend | Bootstrap 5 RTL + CSS مخصص |
| قاعدة البيانات | MySQL 8 (SQLite للتطوير) |
| المصادقة | Laravel Auth + Spatie Permission |
| الصور | Intervention Image |
| البريد | SMTP (قابل للتهيئة) |

## الميزات الرئيسية

- موقع متعدد اللغات (عربي، فرنسي، إنجليزي)
- تصميم RTL أصيل مستوحى من الزليج المغربي
- معرض أعمال فنية مع فلاتر ومعاينة
- نظام طلب لوحات مخصصة مع حاسبة أسعار آنية
- متجر منتجات رقمية وجسدية
- منصة دورات تعليمية (أونلاين وحضوري)
- مدونة متعددة اللغات
- لوحة تحكم شاملة للمدير
- نظام صلاحيات متعدد المستويات (super-admin, admin, editor, teacher, customer)
- مساعد ذكاء اصطناعي بسيط لمساعدة العملاء
- SEO كامل (Schema.org, Open Graph, Sitemap XML)
- تكامل PayPal وStripe للدفع

## بيانات الدخول الافتراضية

**المدير العام (Super Admin):**
- URL: `/admin`
- البريد: `nizari@nizari.net`
- كلمة المرور: `Fatima@1977`
- ملاحظة: سيُطلب تغيير كلمة المرور عند أول تسجيل دخول

## هيكل المشروع

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # لوحة التحكم
│   │   │   ├── Auth/           # المصادقة
│   │   │   └── ...             # الواجهة الأمامية
│   │   └── Middleware/
│   │       ├── SetLocale.php   # تبديل اللغة
│   │       └── SecurityHeaders.php
│   └── Models/                 # 18 نموذج بيانات
├── database/
│   ├── migrations/             # 11 ملف هجرة
│   └── seeders/                # بيانات أولية
├── public/
│   ├── css/nizari.css          # نظام التصميم المخصص
│   └── js/nizari.js            # السكريبتات (حاسبة + مساعد ذكي)
├── resources/views/
│   ├── layouts/                # app.blade.php + admin.blade.php
│   ├── frontend/               # جميع صفحات الموقع
│   └── admin/                  # لوحة التحكم
└── routes/web.php              # جميع المسارات
```

## المتطلبات

- PHP >= 8.2
- Composer
- MySQL 8+ أو SQLite
- Node.js (اختياري، لتطوير الأصول)

## التثبيت السريع

راجع ملف [INSTALLATION.md](INSTALLATION.md) للخطوات التفصيلية.

## الوثائق

- [دليل التثبيت](INSTALLATION.md)
- [دليل الأمان](SECURITY.md)
- [دليل النسخ الاحتياطي](BACKUP.md)
- [دليل الاستضافة](HOSTING.md)
