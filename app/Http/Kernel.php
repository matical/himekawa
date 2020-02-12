<?php

namespace himekawa\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \himekawa\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \himekawa\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            //            \himekawa\Http\Middleware\EncryptCookies::class,
            //            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            //            \Illuminate\Session\Middleware\StartSession::class,
            //            \Illuminate\Session\Middleware\AuthenticateSession::class,
            //            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //            \himekawa\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:30,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'   => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache'      => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'weak.cache' => \himekawa\Http\Middleware\WeakEtagCache::class,
        'can'        => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'      => \himekawa\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
