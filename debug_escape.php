<?php
// Show ESCAPED content of the compiled app.blade.php - DELETE AFTER USE
$file = '/home/nizari/domains/rachid.nizari.net/public_html/storage/framework/views/49bb8ec14d1eded14b373f9275db49be.php';

if (!file_exists($file)) {
    echo "File not found. Try visiting / first to trigger compilation.";
    exit;
}

$content = file_get_contents($file);
$lines   = explode("\n", $content);
$total   = count($lines);

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:11px;direction:ltr;white-space:pre-wrap;">';
echo "File: " . basename($file) . "\n";
echo "Total lines: $total\n\n";

// 1. Show ALL lines that contain PHP if/endif (alternative syntax)
echo "═══ PHP if():/endif; lines (Blade-generated, excluding JS) ═══\n";
$phpIfCount   = 0;
$phpEndifCount = 0;
foreach ($lines as $i => $line) {
    $n = $i + 1;
    // Match PHP alternative syntax (not JS)
    if (preg_match('/\bif\s*\(.*\)\s*:/i', $line) || preg_match('/\bendif\s*;/i', $line)) {
        echo sprintf("%4d | %s\n", $n, htmlspecialchars($line));
        if (preg_match('/\bif\s*\(.*\)\s*:/i', $line)) $phpIfCount++;
        if (preg_match('/\bendif\s*;/i', $line)) $phpEndifCount++;
    }
}
echo "\nPHP if(): $phpIfCount | PHP endif;: $phpEndifCount | Diff: " . ($phpIfCount - $phpEndifCount) . "\n\n";

// 2. Show lines 440-end with FULL HTML escaping
echo "═══ Lines 440–end (HTML-escaped) ═══\n";
foreach ($lines as $i => $line) {
    $n = $i + 1;
    if ($n >= 440) {
        echo sprintf("%4d | %s\n", $n, htmlspecialchars($line));
    }
}

@unlink(__FILE__);
echo "\n=== deleted ===\n";
echo '</pre>';
