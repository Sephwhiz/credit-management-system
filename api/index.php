<?php

// Set headers immediately to prevent browser from downloading
header('Content-Type: text/html; charset=utf-8');

// 1. Ensure writable directories exist in /tmp
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/logs'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Auto-install Composer dependencies if missing
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<h1>Installing Dependencies...</h1><pre>";
    $output = [];
    $return_var = 0;
    exec('cd ' . escapeshellarg(__DIR__ . '/..') . ' && composer install --optimize-autoloader --no-dev --no-interaction 2>&1', $output, $return_var);
    echo implode("\n", $output);
    echo "</pre>";
    
    if ($return_var !== 0 || !file_exists(__DIR__ . '/../vendor/autoload.php')) {
        echo "<h2 style='color:red'>Composer Install Failed</h2>";
        echo "<p>Return Code: $return_var</p>";
        exit(1);
    }
}

// 3. Load Laravel
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    // 4. Catch ALL errors and display them as HTML
    echo "<h1 style='color:red'>⚠️ Application Error</h1>";
    echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;'>";
    echo htmlspecialchars($e->getMessage()) . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "</pre>";
    exit(1);
}