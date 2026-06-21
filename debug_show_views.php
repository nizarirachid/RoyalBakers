<?php
// Show full content of ALL compiled view files + search other locations
// DELETE AFTER USE
define('BASE', realpath(__DIR__));

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:11px;direction:ltr;word-wrap:break-word;">';

// Search in multiple possible cache locations
$locations = [
    BASE . '/storage/framework/views',
    BASE . '/bootstrap/cache',
    dirname(BASE) . '/storage/framework/views',  // one level up
];

foreach ($locations as $dir) {
    if (!is_dir($dir)) continue;
    $files = glob($dir . '/*.php');
    if (empty($files)) continue;

    echo "\n📁 DIR: $dir\n";
    echo "Files: " . count($files) . "\n";
    echo str_repeat('─', 60) . "\n";

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $lineCount = substr_count($content, "\n") + 1;
        $size = strlen($content);

        echo "\n▶ " . basename($file) . " ($size bytes, $lineCount lines)\n";

        // Show first 60 lines
        $lines = explode("\n", $content);
        $show = min(60, count($lines));
        for ($i = 0; $i < $show; $i++) {
            echo sprintf("%4d | %s\n", $i + 1, rtrim($lines[$i]));
        }
        if (count($lines) > 60) {
            echo "     ... (" . (count($lines) - 60) . " more lines) ...\n";
            // Show last 10 lines
            for ($i = count($lines) - 10; $i < count($lines); $i++) {
                echo sprintf("%4d | %s\n", $i + 1, rtrim($lines[$i]));
            }
        }

        // Check if/endif balance in compiled PHP
        $phpIf    = preg_match_all('/\bif\s*\(/i', $content);
        $phpEndif = preg_match_all('/\bendif\s*;/i', $content);
        $diff = $phpIf - $phpEndif;
        echo "\n  if(: $phpIf | endif: $phpEndif | " . ($diff === 0 ? "✅ balanced" : "❌ MISMATCH diff=$diff") . "\n";
        echo str_repeat('─', 60) . "\n";
    }
}

// Also try to find compiled views by recursively searching storage
echo "\n🔍 All .php files in storage/framework:\n";
$iter = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(BASE . '/storage/framework', RecursiveDirectoryIterator::SKIP_DOTS)
);
foreach ($iter as $file) {
    if ($file->getExtension() === 'php') {
        echo "  " . $file->getPathname() . " (" . $file->getSize() . " bytes)\n";
    }
}

@unlink(__FILE__);
echo "\n=== deleted ===\n";
echo '</pre>';
