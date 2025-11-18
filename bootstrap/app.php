<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Kernel;
use App\Http\Middleware\App;



$app= Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
      //  apiPrefix: 'market/api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )    
    ->withMiddleware(function (Middleware $middleware) {        
         $middleware->append(App::class);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Kernel::class
);

return $app;
