<?php

// Set headers immediately to prevent browser from downloading
header('Content-Type: text/html; charset=utf-8');

// 1. Auto-install Composer dependencies if missing
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<h1>Installing Dependencies...</h1><pre>";
    // Redirect output so we can see it in logs/response
    $output = [];
    $return_var = 0;
    exec('cd ' . escapeshellarg(__DIR__ . '/..') . ' && composer install --optimize-autoloader --no-dev --no-interaction 2>&1', $output, $return_var);
    echo implode("\n", $output);
    echo "</pre>";
    
    if ($return_var !== 0 || !file_exists(__DIR__ . '/../vendor/autoload.php')) {
        echo "<h2 style='color:red'> Composer Install Failed</h2>";
        echo "<p>Return Code: $return_var</p>";
        echo "<p>Please check Vercel Function Logs for details.</p>";
        exit(1);
    }
}

// 2. Load Laravel
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
    // 3. Catch ALL errors and display them as HTML
    echo "<h1 style='color:red'>⚠️ Application Error</h1>";
    echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;'>";
    echo htmlspecialchars($e->getMessage()) . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "</pre>";
    echo "<p><small>Check Vercel Function Logs for full stack trace.</small></p>";
    exit(1);
}