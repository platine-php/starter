<?php
    declare(strict_types=1);

use Platine\Framework\Auth\Middleware\AuthenticationMiddleware;
use Platine\Framework\Auth\Middleware\AuthorizationMiddleware;
use Platine\Framework\Http\Middleware\CsrfMiddleware;
use Platine\Framework\Http\Middleware\ErrorHandlerMiddleware;
use Platine\Framework\Http\Middleware\RouteDispatcherMiddleware;
use Platine\Framework\Http\Middleware\RouteMatchMiddleware;

    return [
        ErrorHandlerMiddleware::class,
        RouteMatchMiddleware::class,
        CsrfMiddleware::class,
        AuthenticationMiddleware::class,
        AuthorizationMiddleware::class,
       // SecurityPolicyMiddleware::class,
        RouteDispatcherMiddleware::class,
    ];
