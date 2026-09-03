<?php
// Blade directive counter & cache cleaner - DELETE AFTER USE
define('BASE', __DIR__);

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:13px;direction:ltr;">';

// 1. Count @if/@endif in app.blade.php on the SERVER
$appBlade = BASE . '/resources/views/layouts/app.blade.php';
if (!file_exists($appBlade)) {
    echo "ERROR: app.blade.php not found!\n";
} else {
    $lines = file($appBlade);
    $totalLines = count($lines);
    $ifCount = 0;
    $endifCount = 0;
    $depth = 0;
    $issues = [];

    foreach ($lines as $lineNo => $line) {
        $num = $lineNo + 1;
        // Count @if (but not @ifdef or similar)
        $ifs = preg_match_all('/@if\s*\(/', $line);
        $endifs = preg_match_all('/@endif/', $line);
        $elses = preg_match_all('/@else(?!if)/', $line);
        $elseifs = preg_match_all('/@elseif/', $line);

        for ($i = 0; $i < $ifs; $i++) {
            $depth++;
            $issues[] = "  L{$num}: @if opened → depth={$depth}";
        }
        for ($i = 0; $i < $endifs; $i++) {
            $depth--;
            $issues[] = "  L{$num}: @endif closed → depth={$depth}";
        }

        $ifCount += $ifs;
        $endifCount += $endifs;
    }

    echo "=== app.blade.php ===\n";
    echo "Total lines : $totalLines\n";
    echo "@if count  : $ifCount\n";
    echo "@endif count: $endifCount\n";
    echo "Balance     : " . ($ifCount === $endifCount ? "✅ OK" : "❌ MISMATCH (unclosed: " . ($ifCount - $endifCount) . ")") . "\n\n";
    echo "--- @if/@endif trace ---\n";
    foreach ($issues as $issue) echo $issue . "\n";
    echo "\n";
}

// 2. Count @if/@endif in home.blade.php on the SERVER
$homeBlade = BASE . '/resources/views/frontend/home.blade.php';
if (!file_exists($homeBlade)) {
    echo "ERROR: home.blade.php not found!\n";
} else {
    $lines = file($homeBlade);
    $totalLines = count($lines);
    $ifCount = 0;
    $endifCount = 0;
    $depth = 0;

    foreach ($lines as $lineNo => $line) {
        $ifs    = preg_match_all('/@if\s*\(/', $line);
        $endifs = preg_match_all('/@endif/', $line);
        $ifCount    += $ifs;
        $endifCount += $endifs;
        for ($i = 0; $i < $ifs; $i++)    $depth++;
        for ($i = 0; $i < $endifs; $i++) $depth--;
    }

    echo "=== home.blade.php ===\n";
    echo "Total lines : $totalLines\n";
    echo "@if count  : $ifCount\n";
    echo "@endif count: $endifCount\n";
    echo "Balance     : " . ($ifCount === $endifCount ? "✅ OK" : "❌ MISMATCH (unclosed: " . ($ifCount - $endifCount) . ")") . "\n\n";
}

// 3. Clear ALL compiled views
$viewsDir = BASE . '/storage/framework/views';
$deleted = 0;
$failed = 0;
if (is_dir($viewsDir)) {
    foreach (glob($viewsDir . '/*') as $file) {
        if (is_file($file)) {
            if (@unlink($file)) $deleted++;
            else $failed++;
        }
    }
}
echo "=== cache cleared ===\n";
echo "Deleted: $deleted compiled view files\n";
if ($failed) echo "Failed to delete: $failed files (permission issue)\n";

// 4. Show last 30 lines of app.blade.php (to check ending)
echo "\n=== Last 30 lines of app.blade.php ===\n";
$lines = file($appBlade);
$last = array_slice($lines, -30, 30, true);
foreach ($last as $lineNo => $line) {
    echo sprintf("%4d | %s", $lineNo + 1, $line);
}

// 5. Self-delete
@unlink(__FILE__);
echo "\n=== debug_blade.php deleted ===\n";
echo '</pre>';
