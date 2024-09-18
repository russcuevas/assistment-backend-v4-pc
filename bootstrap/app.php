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
        $middleware->redirectGuestsTo('/home');
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'users' => \App\Http\Middleware\IsUser::class,
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

