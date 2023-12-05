<?php

declare(strict_types=1);

namespace Platine\App\Provider;

use Platine\App\Http\Action\User\AuthAction;
use Platine\Framework\Service\ServiceProvider;
use Platine\Route\Router;

/**
* @class UserServiceProvider
* @package Platine\App\Provider
*/
class UserServiceProvider extends ServiceProvider
{
    /**
    * {@inheritdoc}
    */
    public function register(): void
    {
        $this->app->bind(AuthAction::class);
    }


    /**
    * {@inheritdoc}
    */
    public function addRoutes(Router $router): void
    {
        $router->group('/user', function (Router $router) {
            $router->add('/login', AuthAction::class, ['GET', 'POST'], 'user_login');
        });
    }
}
