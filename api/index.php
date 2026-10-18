<?php

// Auto-install composer dependencies if vendor folder is missing
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // Install composer in the tmp directory (writable)
    system('cd ' . __DIR__ . '/.. && composer install --optimize-autoloader --no-dev --no-interaction');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);