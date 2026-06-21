<?php
// Run migrations - DELETE THIS FILE AFTER USE
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

define('LARAVEL_START', microtime(true));
require_once __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:14px;">';

ob_start();
$status = $kernel->call('migrate', ['--force' => true]);
$out = ob_get_clean();
echo "=== migrate ===\n" . htmlspecialchars($out) . "\n";

ob_start();
$status2 = $kernel->call('db:seed', ['--force' => true]);
$out2 = ob_get_clean();
echo "=== db:seed ===\n" . htmlspecialchars($out2) . "\n";

ob_start();
$kernel->call('optimize:clear');
ob_get_clean();
echo "=== cache cleared ===\n";

ob_start();
$kernel->call('optimize');
ob_get_clean();
echo "=== optimize done ===\n";

echo '</pre>';

if ($status === 0 && $status2 === 0) {
    echo '<div style="background:#052e16;color:#86efac;padding:20px;font-size:18px;margin-top:10px;">
        ✅ تمت الـ migrations والـ seeding بنجاح!<br>
        ⚠️ احذف هذا الملف (run_migrate.php) من السيرفر فوراً.
    </div>';
    @unlink(__FILE__);
} else {
    echo '<div style="background:#2d0808;color:#fca5a5;padding:20px;font-size:18px;margin-top:10px;">
        ❌ فشل — راجع الخطأ أعلاه
    </div>';
}
