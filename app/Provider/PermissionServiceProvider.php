<?php

declare(strict_types=1);

namespace Platine\App\Provider;

use Platine\App\Http\Action\Permission\PermissionAction;
use Platine\Framework\Service\ServiceProvider;
use Platine\Route\Router;

/**
* @class PermissionServiceProvider
* @package Platine\App\Provider
*/
class PermissionServiceProvider extends ServiceProvider
{
    /**
    * {@inheritdoc}
    */
    public function register(): void
    {
        $this->app->bind(PermissionAction::class);
    }


    /**
    * {@inheritdoc}
    */
    public function addRoutes(Router $router): void
    {
        $router->resource('/permission', PermissionAction::class, 'permission');
    }
}
