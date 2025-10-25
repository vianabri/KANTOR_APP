<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias Spatie
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handler exception default
    })
    ->create();

// 🔹 Logging setelah Application siap
try {
    $app->useStoragePath(__DIR__ . '/../storage');
    file_put_contents($app->storagePath() . '/logs/bootstrap_check.log', now() . " - app booted\n", FILE_APPEND);
} catch (Throwable $e) {
    // Abaikan error logging ini, jangan ganggu bootstrap
}

return $app;
