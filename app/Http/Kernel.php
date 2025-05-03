<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ... الميدلويرز الموجودة
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... الميدلويرز الموجودة
        ],

        'api' => [
            // ... الميدلويرز الموجودة
        ],
    ];
    protected $middlewareAliases = [
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        // 'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        // 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        // 'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        // 'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
    ];
    
}