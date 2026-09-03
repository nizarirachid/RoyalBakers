# دليل الاستضافة — Hosting Guide

## متطلبات الاستضافة

| المورد | الحد الأدنى | الموصى به |
|--------|-------------|-----------|
| RAM | 512 MB | 2 GB |
| CPU | 1 vCPU | 2 vCPU |
| مساحة القرص | 10 GB | 50 GB |
| نقل البيانات | 100 GB/شهر | غير محدود |
| PHP | 8.2 | 8.3+ |
| MySQL | 5.7 | 8.0+ |

## استضافات موصى بها

### للإنتاج (VPS)
- **DigitalOcean**: Droplet $12/شهر (2GB RAM)
- **Vultr**: Cloud Compute $10/شهر
- **Hetzner** (اقتصادي): CX22 €4.5/شهر
- **OVH**: VPS Starter 4GB

### الاستضافات المشتركة التي تدعم Laravel
- **Hostinger** Business Plan
- **cPanel** مع PHP 8.2 وComposer

## إعداد على cPanel

```bash
# 1. رفع ملفات المشروع إلى مجلد خاص (ليس public_html)
# مثال: /home/username/nizari/

# 2. أنشئ Symlink من public_html إلى public/
ln -s /home/username/nizari/public /home/username/public_html

# 3. أو انسخ محتوى public/ إلى public_html
# وعدّل DOCUMENT_ROOT في index.php:
# require __DIR__.'/../nizari/vendor/autoload.php';

# 4. إعداد قاعدة البيانات من cPanel > MySQL Databases
# 5. عدّل .env بمعلومات قاعدة البيانات الجديدة
```

## إعداد على Plesk

```bash
# 1. أنشئ موقعاً جديداً بـ Document Root يشير إلى /httpdocs/public
# 2. ارفع ملفات المشروع إلى /httpdocs
# 3. أعد تشغيل Plesk
plesk restart
```

## إعداد SSL (HTTPS)

### Let's Encrypt (مجاني)
```bash
# تثبيت certbot
apt install certbot python3-certbot-nginx

# الحصول على شهادة
certbot --nginx -d nizari.net -d www.nizari.net

# التجديد التلقائي (يُضاف تلقائياً إلى cron)
certbot renew --dry-run
```

### إعداد HTTPS الإجباري في .env
```env
APP_URL=https://nizari.net
FORCE_HTTPS=true
```

وأضف في `app/Http/Middleware/SecurityHeaders.php`:
```php
if (!request()->isSecure() && app()->environment('production')) {
    return redirect()->secure(request()->getRequestUri());
}
```

## إعداد CDN (اختياري)

### Cloudflare (مجاني)
1. أضف نطاقك في Cloudflare
2. غيّر nameservers في سجلات DNS
3. **ثبّت شهادة SSL على السيرفر الأصلي أولاً** (انظر التحذير أسفله)
4. بعدها فقط: فعّل "Full (strict)" SSL mode
5. فعّل "Minify" للـ CSS, JS, HTML
6. أضف Page Rule لتخزين الأصول الثابتة

> ⚠️ **تحذير — خطأ 526 (Invalid SSL certificate)**
>
> وضع **"Full (strict)"** يجعل Cloudflare يرفض الاتصال بالسيرفر الأصلي
> إذا لم تكن عليه شهادة SSL صالحة وغير منتهية ومطابقة لاسم النطاق.
> النتيجة: الموقع كله يتوقف بخطأ **526** رغم أن الكود سليم تماماً.
>
> **الترتيب الصحيح:**
> 1. ابدأ بوضع **"Full"** (وليس strict) — الموقع يعمل مباشرة.
> 2. ثبّت شهادة صالحة على السيرفر:
>    - **الأسهل والأفضل:** Cloudflare → SSL/TLS → Origin Server →
>      *Create Certificate* (مجانية وصالحة 15 سنة، ولا تحتاج تحقّقاً)،
>      ثم الصقها في لوحة الاستضافة (DirectAdmin → SSL Certificates →
>      *Paste a pre-generated certificate and key*).
>    - **أو** Let's Encrypt من لوحة الاستضافة. ملاحظة: قد يفشل التحقق
>      والسحابة البرتقالية مفعّلة، فأطفئها مؤقتاً (DNS only) أثناء الإصدار.
> 3. تأكد أن الشهادة تغطي `nizari.net` و `www.nizari.net`
>    و `*.nizari.net` (للنطاقات الفرعية مثل `rachid.nizari.net`).
> 4. عُد الآن إلى **"Full (strict)"**.
>
> **للتشخيص السريع:**
> ```bash
> # 526 = الشهادة على السيرفر الأصلي غير صالحة
> curl -sSI https://nizari.net | head -1
>
> # فحص الشهادة على السيرفر الأصلي مباشرة (تجاوز Cloudflare)
> openssl s_client -connect ORIGIN_IP:443 -servername nizari.net \
>   </dev/null 2>/dev/null | openssl x509 -noout -subject -issuer -dates
> ```

## المراقبة والأداء

### قياس الأداء
```bash
# تشغيل سكريبت التحقق
php artisan about

# فحص زمن الاستجابة
curl -o /dev/null -s -w "%{time_total}" https://nizari.net
```

### تحسين الأداء على الإنتاج
```bash
# كاش جميع الإعدادات
php artisan optimize

# تفعيل OPcache في php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.revalidate_freq=60
```

## ربط النطاق بـ DNS

```
# سجلات DNS المطلوبة
A     @        IP_ADDRESS_OF_SERVER
A     www      IP_ADDRESS_OF_SERVER
MX    @        mail.nizari.net
TXT   @        v=spf1 include:_spf.nizari.net ~all
```

## نصائح لاستضافة ناجحة

1. استخدم Redis لتخزين الجلسات والكاش في بيئة الإنتاج
2. فعّل Queue Worker لمعالجة الطوابير (رسائل البريد، إشعارات)
3. اضبط PHP memory_limit على 256M على الأقل
4. تأكد من توفر امتداد `mbstring` لدعم العربية
5. اضبط `max_execution_time=120` لرفع الملفات الكبيرة
