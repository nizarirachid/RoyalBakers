# دليل التثبيت — Installation Guide

## المتطلبات الأساسية

- PHP >= 8.2 مع الإضافات: `mbstring`, `pdo`, `pdo_mysql`, `xml`, `curl`, `zip`, `gd`
- Composer >= 2.0
- MySQL 8.0+
- خادم ويب: Apache أو Nginx

## خطوات التثبيت على استضافة Linux

### 1. رفع الملفات

```bash
# رفع ملفات المشروع إلى مجلد الاستضافة
# أو استخدام Git
git clone https://github.com/your-repo/nizari-calligraphy.git /var/www/nizari
cd /var/www/nizari
```

### 2. تثبيت المكتبات

```bash
composer install --optimize-autoloader --no-dev
```

### 3. إعداد ملف البيئة

```bash
cp .env.example .env
php artisan key:generate
```

ثم عدّل `.env`:
```env
APP_NAME="النزاري للخط العربي"
APP_URL=https://nizari.net
APP_LOCALE=ar

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nizari_db
DB_USERNAME=nizari_user
DB_PASSWORD=your_strong_password

MAIL_MAILER=smtp
MAIL_HOST=mail.nizari.net
MAIL_PORT=587
MAIL_USERNAME=contact@nizari.net
MAIL_PASSWORD=mail_password
MAIL_FROM_ADDRESS=contact@nizari.net
MAIL_FROM_NAME="النزاري للخط العربي"

PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_secret

SUPER_ADMIN_NAME="النزاري رشيد"
SUPER_ADMIN_EMAIL=nizari@nizari.net
SUPER_ADMIN_PASSWORD=Fatima@1977
```

### 4. إعداد قاعدة البيانات

```bash
# إنشاء قاعدة البيانات في MySQL
mysql -u root -p -e "CREATE DATABASE nizari_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# تشغيل الهجرات والبيانات الأولية
php artisan migrate --force
php artisan db:seed --force
```

### 5. إعداد الصلاحيات

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

### 6. إعداد الكاش للإنتاج

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 7. إعداد Apache (.htaccess موجود في public/)

```apache
<VirtualHost *:80>
    ServerName nizari.net
    ServerAlias www.nizari.net
    DocumentRoot /var/www/nizari/public

    <Directory /var/www/nizari/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 8. إعداد Nginx (بديل)

```nginx
server {
    listen 80;
    server_name nizari.net www.nizari.net;
    root /var/www/nizari/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 9. SSL مع Let's Encrypt

```bash
apt install certbot python3-certbot-nginx
certbot --nginx -d nizari.net -d www.nizari.net
```

### 10. إعداد Cron (للمهام المجدولة)

```bash
# أضف إلى crontab
* * * * * cd /var/www/nizari && php artisan schedule:run >> /dev/null 2>&1
```

## تسجيل الدخول الأول

1. افتح `https://nizari.net/admin`
2. البريد: `nizari@nizari.net`
3. كلمة المرور: `Fatima@1977`
4. سيُطلب منك تغيير كلمة المرور فوراً

## استيراد قاعدة البيانات (بديل للـ Migrations)

يمكن استيراد الملف `database/nizari_calligraphy.sql` مباشرة:
```bash
mysql -u nizari_user -p nizari_db < database/nizari_calligraphy.sql
```
