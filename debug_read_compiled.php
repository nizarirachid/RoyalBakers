<?php
// Reads the compiled PHP view for app.blade.php directly from disk
// DELETE AFTER USE
define('BASE', realpath(__DIR__));

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:12px;direction:ltr;">';

$viewsDir = BASE . '/storage/framework/views';

if (!is_dir($viewsDir)) {
    echo "ERROR: views dir not found\n"; exit;
}

// Find the compiled file that corresponds to app.blade.php
// It's the one containing unique content from the layout
$compiled_files = glob($viewsDir . '/*.php');

if (empty($compiled_files)) {
    // No compiled files yet - trigger compilation by visiting / first
    echo "No compiled files found.\n";
    echo "Visit https://rachid.nizari.net/ first, then re-run this script.\n";
    @unlink(__FILE__);
    echo '</pre>';
    exit;
}

echo "Found " . count($compiled_files) . " compiled files.\n\n";

$target = null;
$targetFile = null;

foreach ($compiled_files as $file) {
    $content = file_get_contents($file);
    // app.blade.php has unique string: "mainNav"
    if (strpos($content, 'mainNav') !== false || strpos($content, 'navbar-nizari') !== false) {
        $target = $content;
        $targetFile = $file;
        break;
    }
}

if (!$target) {
    echo "Could not find compiled app.blade.php among cached files.\n";
    echo "Files found:\n";
    foreach ($compiled_files as $f) {
        echo "  " . basename($f) . " (" . filesize($f) . " bytes, " . count(file($f)) . " lines)\n";
    }
    @unlink(__FILE__);
    echo '</pre>';
    exit;
}

$lines = explode("\n", $target);
$total = count($lines);

echo "Found compiled app.blade.php: " . basename($targetFile) . "\n";
echo "Lines: $total\n\n";

// Count PHP if/endif
$phpIf    = preg_match_all('/\bif\s*\(/i', $target);
$phpEndif = preg_match_all('/\bendif\s*;/i', $target);
echo "PHP if(  count: $phpIf\n";
echo "PHP endif count: $phpEndif\n";
$diff = $phpIf - $phpEndif;
echo "Balance: " . ($diff === 0 ? "✅ OK" : "❌ MISMATCH — $diff unclosed") . "\n\n";

// Show lines 440 to end
echo "═══ Compiled PHP — lines 440 to end ═══\n";
foreach ($lines as $i => $line) {
    $n = $i + 1;
    if ($n >= 440) {
        echo sprintf("%4d | %s\n", $n, rtrim($line));
    }
}

// Also show EVERY line that contains "if(" or "endif"
echo "\n═══ All if()/endif lines in compiled PHP ═══\n";
foreach ($lines as $i => $line) {
    $n = $i + 1;
    if (preg_match('/\bif\s*\(|\bendif\s*;/i', $line)) {
        echo sprintf("%4d | %s\n", $n, rtrim($line));
    }
}

@unlink(__FILE__);
echo "\n=== debug_read_compiled.php deleted ===\n";
echo '</pre>';
