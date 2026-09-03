# دليل النسخ الاحتياطي — Backup Guide

## النسخ الاحتياطي اليدوي

### نسخ قاعدة البيانات
```bash
# إنشاء نسخة احتياطية مضغوطة
mysqldump -u nizari_user -p nizari_db | gzip > /backups/nizari_$(date +%Y%m%d_%H%M%S).sql.gz

# استعادة من نسخة احتياطية
gunzip < /backups/nizari_20240101_120000.sql.gz | mysql -u nizari_user -p nizari_db
```

### نسخ ملفات المشروع
```bash
# نسخ مجلد storage (الصور والملفات المرفوعة)
tar -czf /backups/storage_$(date +%Y%m%d).tar.gz /var/www/nizari/storage/app/public/

# نسخ ملف .env
cp /var/www/nizari/.env /backups/env_$(date +%Y%m%d).env
```

## النسخ الاحتياطي التلقائي (Cron)

أضف إلى `/etc/crontab`:
```bash
# نسخ يومي لقاعدة البيانات في الساعة 2 صباحاً
0 2 * * * root mysqldump -u nizari_user -pPASSWORD nizari_db | gzip > /backups/db_$(date +\%Y\%m\%d).sql.gz

# حذف النسخ الأقدم من 30 يوم
0 3 * * * root find /backups -name "*.sql.gz" -mtime +30 -delete

# نسخ أسبوعي لملفات الصور كل أحد
0 4 * * 0 root tar -czf /backups/storage_$(date +\%Y\%m\%d).tar.gz /var/www/nizari/storage/app/public/
```

## النسخ الاحتياطي على السحابة

### رفع إلى S3/رايدوس
```bash
# تثبيت AWS CLI
apt install awscli

# تكوين
aws configure

# نسخ إلى S3
aws s3 cp /backups/nizari_db.sql.gz s3://your-bucket/backups/
```

### استخدام Rclone مع Google Drive / Dropbox
```bash
# تثبيت rclone
curl https://rclone.org/install.sh | sudo bash
rclone config

# النسخ اليومي
rclone copy /backups remote:nizari-backups/
```

## استعادة الموقع بعد عطل

```bash
# 1. أوقف الخادم
systemctl stop nginx

# 2. استعد قاعدة البيانات
gunzip < /backups/nizari_20240101.sql.gz | mysql -u nizari_user -p nizari_db

# 3. استعد الصور
tar -xzf /backups/storage_20240101.tar.gz -C /

# 4. أعد توليد الكاش
cd /var/www/nizari
php artisan config:cache
php artisan view:cache

# 5. أعد تشغيل الخادم
systemctl start nginx
systemctl restart php8.2-fpm
```

## التحقق من النسخ الاحتياطية

```bash
# فحص سلامة ملف النسخة
gunzip -t /backups/nizari_db.sql.gz && echo "OK" || echo "CORRUPTED"

# استعادة في بيئة اختبار
mysql -u root -p test_db < /backups/nizari_db.sql
```
