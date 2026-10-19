<?php
// Capture ALL errors immediately
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();

try {
    // 1. Ensure writable directories exist
    $dirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions', '/tmp/logs'];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }

    // 2. Auto-install Composer if missing
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        echo "<pre>Installing Composer...</pre>";
        system('cd ' . escapeshellarg(__DIR__ . '/..') . ' && composer install --optimize-autoloader --no-dev --no-interaction 2>&1');
        if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
            throw new RuntimeException("Composer install failed");
        }
    }

    // 3. Load Laravel
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // 4. Handle Request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Illuminate\Http\Request::capture());
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    // 5. Display RAW error details
    ob_end_clean(); // Clear any partial output
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1 style='color:red'>⚠️ FATAL ERROR</h1>";
    echo "<pre style='background:#222;color:#fff;padding:15px;border-radius:5px;font-family:monospace;'>";
    echo "Message: " . htmlspecialchars($e->getMessage()) . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Trace:\n" . htmlspecialchars($e->getTraceAsString()) . "\n";
    echo "</pre>";
    echo "<p><small>Check Vercel Function Logs for server-side details.</small></p>";
    exit(1);
}