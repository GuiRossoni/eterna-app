<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: '/account/login',
            users: '/account/profile',
            //notFound: '/404',
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

/*
|--------------------------------------------------------------------------
| Application Locale
|--------------------------------------------------------------------------
|
| The application locale determines the default locale that will be used by
| the translation service provider. You are free to set this value to
| any of the locales which are supported by the PHP installation on
| your server or you may set this value to "en" for the default.
|
*/

