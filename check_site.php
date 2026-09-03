<?php
// فحص: أين يشير النطاق؟ وأين ملفات المشروع؟ (قراءة فقط — لا يحذف شيئاً)
// DELETE AFTER USE

function h($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:13px;direction:ltr;white-space:pre-wrap;">';
echo "=== فحص إعداد النطاق ===\n\n";

// ── 1. أين نحن؟ ───────────────────────────────────────────────────────────────
$here = __DIR__;
echo "HTTP_HOST      : " . h($_SERVER['HTTP_HOST']      ?? '?') . "\n";
echo "DOCUMENT_ROOT  : " . h($_SERVER['DOCUMENT_ROOT']  ?? '?') . "\n";
echo "هذا الملف موجود في: " . h($here) . "\n";
echo "SERVER_SOFTWARE: " . h($_SERVER['SERVER_SOFTWARE'] ?? '?') . "\n";
echo "PHP            : " . PHP_VERSION . "\n\n";

// ── 2. هل ملفات Laravel هنا؟ ──────────────────────────────────────────────────
echo "=== هل مشروع Laravel موجود في هذا المجلد؟ ===\n";
$needed = [
    'index.php'        => 'نقطة الدخول',
    'vendor/autoload.php' => 'مكتبات Composer',
    '.env'             => 'الإعدادات',
    'bootstrap/app.php' => 'إقلاع Laravel',
    'resources/views/layouts/app.blade.php' => 'القالب',
    'storage/framework/views' => 'كاش القوالب',
];
$found = 0;
foreach ($needed as $rel => $label) {
    $ok = file_exists($here . '/' . $rel);
    if ($ok) $found++;
    echo ($ok ? "  ✅ " : "  ❌ ") . str_pad($rel, 40) . " ($label)\n";
}
echo "\n" . ($found === count($needed)
    ? "  → المشروع موجود بالكامل هنا.\n\n"
    : "  → المشروع غير موجود (أو ناقص) في هذا المجلد!\n\n");

// ── 3. ماذا يوجد فعلاً هنا؟ ───────────────────────────────────────────────────
echo "=== محتويات هذا المجلد ===\n";
$items = @scandir($here);
if ($items === false) {
    echo "  (تعذّرت القراءة)\n";
} else {
    $n = 0;
    foreach ($items as $it) {
        if ($it === '.' || $it === '..') continue;
        $p = $here . '/' . $it;
        echo "  " . (is_dir($p) ? "[DIR ] " : "[FILE] ") . h($it) . "\n";
        if (++$n >= 40) { echo "  ... (والمزيد)\n"; break; }
    }
    if ($n === 0) echo "  (المجلد فارغ!)\n";
}
echo "\n";

// ── 4. أين المجلدات الأخرى للنطاقات؟ ──────────────────────────────────────────
echo "=== مجلدات النطاقات على الحساب ===\n";
$domainsDir = null;
$probe = $here;
for ($i = 0; $i < 6; $i++) {
    $probe = dirname($probe);
    if (basename($probe) === 'domains' && is_dir($probe)) { $domainsDir = $probe; break; }
    if ($probe === '/' || $probe === '.') break;
}

if ($domainsDir === null) {
    echo "  لم أعثر على مجلد domains/ فوق هذا المسار.\n";
} else {
    echo "  المسار: " . h($domainsDir) . "\n\n";
    foreach ((@scandir($domainsDir) ?: []) as $d) {
        if ($d === '.' || $d === '..') continue;
        $root = $domainsDir . '/' . $d . '/public_html';
        if (!is_dir($root)) continue;
        $hasLaravel = file_exists($root . '/index.php')
                   && (is_dir($root . '/vendor') || is_dir($root . '/bootstrap'));
        $count = count(array_diff(@scandir($root) ?: [], ['.', '..']));
        echo "  " . ($hasLaravel ? "✅ Laravel " : "⬜ فارغ/عادي") . " | "
           . str_pad(h($d), 28) . " | $count عنصر\n";
        echo "        " . h($root) . "\n";
    }
}

echo "\n=== انتهى الفحص (لم يُحذف أو يُعدَّل أي شيء) ===\n";
echo "امسح هذا الملف بعد قراءة النتيجة.\n";
echo '</pre>';
