<?php
// Fix ParseError: @context in JSON-LD + clear view cache
// DELETE AFTER USE
define('BASE', realpath(__DIR__));

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:13px;direction:ltr;">';
echo "=== RoyalBakers Auto-Fix ===\n\n";

$errors = [];

// ── 1. Fix app.blade.php ──────────────────────────────────────────────────────
$viewFile = BASE . '/resources/views/layouts/app.blade.php';

if (!file_exists($viewFile)) {
    $errors[] = "NOT FOUND: $viewFile";
} else {
    $content = file_get_contents($viewFile);

    if (strpos($content, '"@@context"') !== false) {
        echo "✅ app.blade.php — already fixed (@@context present)\n";
    } elseif (strpos($content, '"@context"') !== false) {
        $fixed = str_replace('"@context"', '"@@context"', $content);
        if (file_put_contents($viewFile, $fixed) !== false) {
            echo "✅ app.blade.php — fixed: \"@context\" → \"@@context\"\n";
        } else {
            $errors[] = "WRITE ERROR: $viewFile";
        }
    } else {
        $errors[] = "WARNING: \"@context\" not found in app.blade.php — check manually";
    }
}

// ── 2. Clear compiled view cache ──────────────────────────────────────────────
$viewsDir = BASE . '/storage/framework/views';

if (!is_dir($viewsDir)) {
    $errors[] = "NOT FOUND: $viewsDir";
} else {
    $files  = glob($viewsDir . '/*.php');
    $count  = 0;
    $failed = 0;

    foreach ($files as $file) {
        if (@unlink($file)) {
            $count++;
        } else {
            $failed++;
            $errors[] = "COULD NOT DELETE: " . basename($file);
        }
    }

    echo "✅ View cache cleared: $count file(s) deleted" . ($failed ? ", $failed failed" : "") . "\n";
}

// ── 3. Result ─────────────────────────────────────────────────────────────────
echo "\n";
if (empty($errors)) {
    echo "✅ All done — visit the site now, it should work!\n";
} else {
    echo "⚠️  Errors:\n";
    foreach ($errors as $e) {
        echo "  - $e\n";
    }
}

@unlink(__FILE__);
echo "\n=== script deleted ===\n";
echo '</pre>';
