<?php
// Shows compiled PHP for app.blade.php + scans ALL if-generating directives
// DELETE AFTER USE
define('BASE', realpath(__DIR__));

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo '<pre style="background:#0f172a;color:#e2e8f0;padding:20px;font-size:12px;direction:ltr;">';

// ── 1. Scan source files for ALL directives that compile to PHP if() ──────────
$directives = [
    '@if\s*\('     => '@if',
    '@endif'        => '@endif',
    '@unless\s*\('  => '@unless',
    '@endunless'    => '@endunless',
    '@isset\s*\('   => '@isset',
    '@endisset'     => '@endisset',
    '@empty\s*\('   => '@empty',
    '@endempty'     => '@endempty',
    '@auth'         => '@auth',
    '@endauth'      => '@endauth',
    '@guest'        => '@guest',
    '@endguest'     => '@endguest',
    '@can\s*\('     => '@can',
    '@endcan'       => '@endcan',
    '@cannot\s*\('  => '@cannot',
    '@endcannot'    => '@endcannot',
];

$files = [
    'app.blade.php'  => BASE . '/resources/views/layouts/app.blade.php',
    'home.blade.php' => BASE . '/resources/views/frontend/home.blade.php',
];

foreach ($files as $label => $path) {
    if (!file_exists($path)) { echo "NOT FOUND: $path\n"; continue; }
    $content = file_get_contents($path);
    echo "═══ $label ═══\n";
    foreach ($directives as $pattern => $name) {
        $count = preg_match_all('/' . $pattern . '/', $content);
        if ($count > 0) echo "  $name: $count\n";
    }
    echo "\n";
}

// ── 2. Bootstrap Laravel and get compiled path ────────────────────────────────
require_once BASE . '/vendor/autoload.php';
$app = require BASE . '/bootstrap/app.php';

try {
    $blade    = $app->make('blade.compiler');
    $viewPath = BASE . '/resources/views/layouts/app.blade.php';
    $compiled = $blade->getCompiledPath($viewPath);

    echo "═══ Compiled path ═══\n$compiled\n\n";

    // Force recompile
    $blade->compile($viewPath);
    echo "Compiled OK.\n\n";

    if (file_exists($compiled)) {
        $lines   = file($compiled);
        $total   = count($lines);
        echo "Compiled PHP: $total lines\n\n";

        // Show lines 450–end (where the error is)
        echo "═══ Compiled PHP lines 450–end ═══\n";
        foreach ($lines as $i => $line) {
            $n = $i + 1;
            if ($n >= 450) {
                echo sprintf("%4d | %s", $n, $line);
            }
        }
        echo "\n";

        // Count PHP if/endif in compiled output
        $compiled_content = file_get_contents($compiled);
        $phpIf    = preg_match_all('/\bif\s*\(/i', $compiled_content);
        $phpEndif = preg_match_all('/\bendif\s*;/i', $compiled_content);
        echo "═══ Compiled PHP if/endif count ═══\n";
        echo "if(  : $phpIf\n";
        echo "endif: $phpEndif\n";
        echo "Balance: " . ($phpIf === $phpEndif ? "✅ OK" : "❌ MISMATCH (diff: " . ($phpIf - $phpEndif) . ")") . "\n";
    }
} catch (Throwable $e) {
    echo "ERROR during compile:\n" . $e->getMessage() . "\n\n";
    echo $e->getTraceAsString() . "\n";
}

@unlink(__FILE__);
echo "\n=== debug_compiled.php deleted ===\n";
echo '</pre>';
