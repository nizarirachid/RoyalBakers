<?php
/**
 * NIZARI Rachid — Web Installer
 * URL: /install
 * This file deletes itself after successful installation.
 */

define('BASE', realpath(__DIR__));
define('ENV_FILE', BASE . '/.env');
define('ENV_EXAMPLE', BASE . '/.env.example');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
session_start();

if (file_exists(ENV_FILE) && !isset($_GET['force'])) {
    $env = file_get_contents(ENV_FILE);
    if (strpos($env, 'APP_KEY=base64:') !== false) {
        header('Location: /');
        exit;
    }
}

$D = [
    'APP_NAME'             => 'NIZARI Rachid - خطاط مغربي',
    'APP_URL'              => 'https://Rachid.nizari.net',
    'APP_ENV'              => 'production',
    'APP_DEBUG'            => 'false',
    'APP_LOCALE'           => 'ar',
    'APP_FALLBACK_LOCALE'  => 'fr',
    'DB_HOST'              => '127.0.0.1',
    'DB_PORT'              => '3306',
    'DB_DATABASE'          => 'nizari_rachid',
    'DB_USERNAME'          => 'nizari_rachid',
    'DB_PASSWORD'          => 'nizari_rachid',
    'MAIL_MAILER'          => 'smtp',
    'MAIL_HOST'            => 'localhost',
    'MAIL_PORT'            => '587',
    'MAIL_USERNAME'        => 'nizarirachid@gmail.com',
    'MAIL_PASSWORD'        => '',
    'MAIL_ENCRYPTION'      => 'tls',
    'MAIL_FROM_ADDRESS'    => 'nizarirachid@gmail.com',
    'SUPER_ADMIN_NAME'     => 'NIZARI Rachid',
    'SUPER_ADMIN_EMAIL'    => 'nizarirachid@gmail.com',
    'SUPER_ADMIN_PASSWORD' => 'Fatima@1977',
];

function phpBin(): string {
    foreach ([PHP_BINARY, 'php8.3', 'php83', 'php'] as $b) {
        if ($b && @shell_exec($b . ' -r "echo 1;" 2>/dev/null') === '1') return $b;
    }
    return 'php';
}

function runArtisan(string $cmd): array {
    $bin     = phpBin();
    $artisan = escapeshellarg(BASE . '/artisan');
    $full    = "$bin $artisan $cmd 2>&1";
    exec($full, $out, $code);
    return ['ok' => $code === 0, 'out' => implode("\n", $out)];
}

function execAvailable(): bool {
    if (!function_exists('exec')) return false;
    $disabled = array_map('trim', explode(',', (string)ini_get('disable_functions')));
    return !in_array('exec', $disabled, true);
}

function requirements(): array {
    $list = [];
    $list[] = ['n' => 'إصدار PHP ' . PHP_VERSION, 'ok' => version_compare(PHP_VERSION, '8.3.0', '>='), 'h' => 'PHP 8.3 أو أعلى مطلوب'];
    foreach (['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd', 'curl'] as $ext) {
        $list[] = ['n' => "امتداد PHP: $ext", 'ok' => extension_loaded($ext), 'h' => "تفعيل $ext في php.ini"];
    }
    foreach ([
        'storage/app'       => BASE . '/storage/app',
        'storage/framework' => BASE . '/storage/framework',
        'storage/logs'      => BASE . '/storage/logs',
        'bootstrap/cache'   => BASE . '/bootstrap/cache',
    ] as $name => $path) {
        $list[] = ['n' => "صلاحية الكتابة: $name", 'ok' => is_writable($path), 'h' => "chmod -R 775 $name"];
    }
    $list[] = ['n' => 'مجلد vendor موجود', 'ok' => file_exists(BASE . '/vendor/autoload.php'), 'h' => 'نفّذ: composer install --no-dev'];
    $list[] = ['n' => 'exec() مفعّل', 'ok' => execAvailable(), 'h' => 'يجب تفعيل exec() في PHP'];
    return $list;
}

function allOk(array $list): bool {
    foreach ($list as $r) { if (!$r['ok']) return false; }
    return true;
}

function writeEnv(array $p): bool {
    if (!file_exists(ENV_EXAMPLE)) return false;
    $env = file_get_contents(ENV_EXAMPLE);
    $quoted = ['APP_NAME', 'MAIL_FROM_ADDRESS', 'SUPER_ADMIN_NAME', 'SUPER_ADMIN_EMAIL', 'SUPER_ADMIN_PASSWORD'];
    foreach ($p as $key => $val) {
        $val = str_replace('"', '\\"', $val);
        if (in_array($key, $quoted, true)) {
            $env = preg_replace('/^' . preg_quote($key, '/') . '=.*/m', $key . '="' . $val . '"', $env);
        } else {
            $env = preg_replace('/^' . preg_quote($key, '/') . '=.*/m', $key . '=' . $val, $env);
        }
    }
    return file_put_contents(ENV_FILE, $env) !== false;
}

$step   = $_GET['step'] ?? 'check';
$result = [];
$log    = [];

if ($step === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $D;
    foreach (array_keys($D) as $k) {
        if (isset($_POST[$k])) $p[$k] = trim($_POST[$k]);
    }
    if (!writeEnv($p)) {
        $log[] = ['label' => 'كتابة ملف .env', 'ok' => false, 'out' => 'فشل — تأكد صلاحيات الكتابة (chmod 755)'];
        $step = 'done'; goto finish;
    }
    $log[] = ['label' => 'كتابة ملف .env', 'ok' => true, 'out' => 'تم بنجاح'];
    $r = runArtisan('key:generate --force');
    $log[] = ['label' => 'توليد APP_KEY', 'ok' => $r['ok'], 'out' => $r['out']];
    if (!$r['ok']) { $step = 'done'; goto finish; }
    $r = runArtisan('storage:link --force');
    $log[] = ['label' => 'ربط storage', 'ok' => $r['ok'], 'out' => $r['out']];
    $r = runArtisan('migrate --seed --force');
    $log[] = ['label' => 'قاعدة البيانات والبيانات الأولية', 'ok' => $r['ok'], 'out' => $r['out']];
    if (!$r['ok']) { $step = 'done'; goto finish; }
    $r = runArtisan('optimize');
    $log[] = ['label' => 'تحسين الأداء', 'ok' => $r['ok'], 'out' => $r['out']];
    @unlink(__FILE__);
    $log[] = ['label' => 'حذف install.php للأمان', 'ok' => true, 'out' => 'تم'];
    $result = ['ok' => true, 'url' => $p['APP_URL']];
    $step = 'done';
}

finish:
$checks     = requirements();
$canProceed = allOk($checks);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تنصيب موقع NIZARI Rachid</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Tahoma,Arial,sans-serif;background:#0f172a;color:#e2e8f0;min-height:100vh;padding:1.5rem}
.wrap{max-width:780px;margin:0 auto}
.logo{text-align:center;padding:2rem 0 1.5rem}
.logo h1{font-size:1.8rem;color:#f8c642}
.logo p{color:#94a3b8;font-size:.9rem;margin-top:.4rem}
.card{background:#1e293b;border-radius:14px;padding:2rem;margin-bottom:1.5rem;border:1px solid #334155}
.card h2{font-size:1.1rem;color:#f8c642;margin-bottom:1.2rem;padding-bottom:.6rem;border-bottom:1px solid #334155}
.steps{display:flex;gap:.5rem;margin-bottom:2rem;justify-content:center}
.step{flex:1;text-align:center;padding:.5rem;border-radius:8px;font-size:.8rem;background:#334155;color:#94a3b8;max-width:160px}
.step.active{background:#f8c642;color:#0f172a;font-weight:700}
.step.done{background:#10b981;color:#fff}
.req-list{list-style:none;display:grid;gap:.5rem}
.req-item{display:flex;align-items:center;gap:.8rem;padding:.6rem .8rem;border-radius:8px;background:#0f172a;font-size:.88rem}
.req-item .ico{flex-shrink:0;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700}
.ico.ok{background:#10b981;color:#fff}.ico.fail{background:#ef4444;color:#fff}
.req-item .hint{font-size:.75rem;color:#94a3b8;margin-top:.1rem}
.row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem}
@media(max-width:560px){.row{grid-template-columns:1fr}}
.field{display:flex;flex-direction:column;gap:.3rem}
.field label{font-size:.82rem;color:#94a3b8}
.field input,.field select{background:#0f172a;border:1px solid #475569;border-radius:8px;padding:.55rem .8rem;color:#e2e8f0;font-size:.9rem;outline:none;width:100%;direction:ltr;text-align:left}
.field input:focus,.field select:focus{border-color:#f8c642}
.field small{font-size:.72rem;color:#64748b}
.btn{display:inline-block;padding:.7rem 2rem;border-radius:8px;border:none;cursor:pointer;font-size:.95rem;font-weight:600;text-decoration:none;transition:.2s}
.btn-primary{background:#f8c642;color:#0f172a}.btn-primary:hover{background:#fbbf24}
.btn-secondary{background:#334155;color:#e2e8f0}
.btn-center{text-align:center;margin-top:1.5rem}
.log-item{padding:.7rem 1rem;border-radius:8px;margin-bottom:.5rem;font-size:.87rem;display:flex;gap:.8rem;align-items:flex-start;background:#0f172a}
.log-item .status{flex-shrink:0;padding:.15rem .5rem;border-radius:4px;font-size:.72rem;font-weight:700}
.log-item .status.ok{background:#10b981;color:#fff}.log-item .status.fail{background:#ef4444;color:#fff}
.log-item .detail{font-size:.75rem;color:#94a3b8;margin-top:.2rem;white-space:pre-wrap;word-break:break-all}
.alert{padding:1rem 1.2rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem}
.alert-warn{background:#422006;border:1px solid #92400e;color:#fcd34d}
.alert-success{background:#052e16;border:1px solid #166534;color:#86efac}
.alert-error{background:#2d0808;border:1px solid #991b1b;color:#fca5a5}
</style>
</head>
<body>
<div class="wrap">
  <div class="logo">
    <h1>&#x2728; تنصيب موقع النزاري رشيد</h1>
    <p>معالج التنصيب التلقائي — NIZARI Rachid Portfolio</p>
  </div>
  <div class="steps">
    <div class="step <?= $step==='check' ? 'active' : ($step!=='check' ? 'done':'') ?>">① المتطلبات</div>
    <div class="step <?= $step==='config' ? 'active' : ($step==='done' ? 'done':'') ?>">② الإعدادات</div>
    <div class="step <?= $step==='done' ? 'active':'' ?>">③ التنصيب</div>
  </div>
  <?php if ($step==='check'): ?>
  <div class="card">
    <h2>فحص المتطلبات</h2>
    <?php if (!$canProceed): ?><div class="alert alert-warn">⚠️ بعض المتطلبات غير مستوفاة.</div>
    <?php else: ?><div class="alert alert-success">✅ جميع المتطلبات مستوفاة — يمكنك المتابعة.</div><?php endif; ?>
    <ul class="req-list">
      <?php foreach ($checks as $c): ?>
      <li class="req-item">
        <span class="ico <?= $c['ok']?'ok':'fail' ?>"><?= $c['ok']?'✓':'✗' ?></span>
        <div><div><?= htmlspecialchars($c['n']) ?></div><?php if (!$c['ok']): ?><div class="hint"><?= htmlspecialchars($c['h']) ?></div><?php endif; ?></div>
      </li>
      <?php endforeach; ?>
    </ul>
    <div class="btn-center">
      <?php if ($canProceed): ?><a href="?step=config" class="btn btn-primary">التالي &rarr;</a>
      <?php else: ?><a href="?step=check" class="btn btn-secondary">إعادة الفحص</a><?php endif; ?>
    </div>
  </div>
  <?php elseif ($step==='config'): ?>
  <form method="POST" action="?step=install">
    <div class="card">
      <h2>إعدادات التطبيق</h2>
      <div class="row">
        <div class="field"><label>اسم الموقع</label><input name="APP_NAME" value="<?= htmlspecialchars($D['APP_NAME']) ?>"></div>
        <div class="field"><label>رابط الموقع</label><input name="APP_URL" value="<?= htmlspecialchars($D['APP_URL']) ?>"></div>
      </div>
      <div class="row">
        <div class="field"><label>البيئة</label><select name="APP_ENV"><option value="production" selected>production</option><option value="local">local</option></select></div>
        <div class="field"><label>اللغة</label><select name="APP_LOCALE"><option value="ar" selected>العربية</option><option value="fr">Français</option><option value="en">English</option></select></div>
      </div>
      <input type="hidden" name="APP_FALLBACK_LOCALE" value="fr">
      <input type="hidden" name="APP_DEBUG" value="false">
    </div>
    <div class="card">
      <h2>إعدادات قاعدة البيانات</h2>
      <div class="alert alert-warn" style="margin-bottom:1rem">ℹ️ تأكد أن قاعدة البيانات أُنشئت مسبقاً من cPanel.</div>
      <div class="row">
        <div class="field"><label>الخادم</label><input name="DB_HOST" value="<?= htmlspecialchars($D['DB_HOST']) ?>"></div>
        <div class="field"><label>المنفذ</label><input name="DB_PORT" value="<?= htmlspecialchars($D['DB_PORT']) ?>"></div>
      </div>
      <div class="row">
        <div class="field"><label>اسم قاعدة البيانات</label><input name="DB_DATABASE" value="<?= htmlspecialchars($D['DB_DATABASE']) ?>"></div>
        <div class="field"><label>اسم المستخدم</label><input name="DB_USERNAME" value="<?= htmlspecialchars($D['DB_USERNAME']) ?>"></div>
      </div>
      <div class="row"><div class="field"><label>كلمة السر</label><input name="DB_PASSWORD" type="password" value="<?= htmlspecialchars($D['DB_PASSWORD']) ?>"></div></div>
    </div>
    <div class="card">
      <h2>إعدادات البريد</h2>
      <div class="row">
        <div class="field"><label>خادم البريد</label><input name="MAIL_HOST" value="<?= htmlspecialchars($D['MAIL_HOST']) ?>"></div>
        <div class="field"><label>المنفذ</label><input name="MAIL_PORT" value="<?= htmlspecialchars($D['MAIL_PORT']) ?>"></div>
      </div>
      <div class="row">
        <div class="field"><label>المستخدم</label><input name="MAIL_USERNAME" value="<?= htmlspecialchars($D['MAIL_USERNAME']) ?>"></div>
        <div class="field"><label>كلمة سر البريد</label><input name="MAIL_PASSWORD" type="password" value=""><small>اتركها فارغة إن لم تكن مطلوبة</small></div>
      </div>
      <div class="row">
        <div class="field"><label>عنوان الإرسال</label><input name="MAIL_FROM_ADDRESS" value="<?= htmlspecialchars($D['MAIL_FROM_ADDRESS']) ?>"></div>
        <div class="field"><label>التشفير</label><select name="MAIL_ENCRYPTION"><option value="tls" selected>TLS</option><option value="ssl">SSL</option><option value="null">بدون</option></select></div>
      </div>
      <input type="hidden" name="MAIL_MAILER" value="smtp">
    </div>
    <div class="card">
      <h2>حساب Super Admin</h2>
      <div class="row">
        <div class="field"><label>الاسم</label><input name="SUPER_ADMIN_NAME" value="<?= htmlspecialchars($D['SUPER_ADMIN_NAME']) ?>"></div>
        <div class="field"><label>البريد</label><input name="SUPER_ADMIN_EMAIL" type="email" value="<?= htmlspecialchars($D['SUPER_ADMIN_EMAIL']) ?>"></div>
      </div>
      <div class="row"><div class="field"><label>كلمة السر</label><input name="SUPER_ADMIN_PASSWORD" type="password" value="<?= htmlspecialchars($D['SUPER_ADMIN_PASSWORD']) ?>"><small>احتفظ بها</small></div></div>
    </div>
    <div class="btn-center">
      <a href="?step=check" class="btn btn-secondary" style="margin-left:1rem">السابق</a>
      <button type="submit" class="btn btn-primary">&#x26A1; بدء التنصيب</button>
    </div>
  </form>
  <?php elseif ($step==='done'): ?>
  <div class="card">
    <h2>نتائج التنصيب</h2>
    <?php foreach ($log as $entry): ?>
    <div class="log-item">
      <span class="status <?= $entry['ok']?'ok':'fail' ?>"><?= $entry['ok']?'نجح':'فشل' ?></span>
      <div><div><?= htmlspecialchars($entry['label']) ?></div><?php if (!empty($entry['out'])): ?><div class="detail"><?= htmlspecialchars($entry['out']) ?></div><?php endif; ?></div>
    </div>
    <?php endforeach; ?>
    <?php if (!empty($result['ok'])): ?>
    <div class="alert alert-success" style="margin-top:1.5rem">✅ تم التنصيب بنجاح!</div>
    <div class="btn-center">
      <a href="<?= htmlspecialchars($result['url']??'/') ?>" class="btn btn-primary">&#x1F3E0; الذهاب للموقع</a>
      <a href="<?= htmlspecialchars($result['url']??'/') ?>/admin" class="btn btn-secondary" style="margin-right:.8rem">لوحة التحكم</a>
    </div>
    <?php else: ?>
    <div class="alert alert-error" style="margin-top:1.5rem">❌ فشل التنصيب.</div>
    <div class="btn-center"><a href="?step=config" class="btn btn-secondary">العودة</a></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <p style="text-align:center;color:#475569;font-size:.75rem;margin-top:2rem">NIZARI Rachid &copy; <?= date('Y') ?> — للاستخدام مرة واحدة</p>
</div>
</body>
</html>