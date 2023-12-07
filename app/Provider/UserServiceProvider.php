<?php

declare(strict_types=1);

namespace Platine\App\Provider;

use Platine\App\Http\Action\Permission\PermissionAction;
use Platine\App\Http\Action\Role\RoleAction;
use Platine\App\Http\Action\User\AuthAction;
use Platine\App\Http\Action\User\LogoutAction;
use Platine\App\Http\Action\User\UserAction;
use Platine\App\Http\Action\User\UserProfileAction;
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
        // User
        $this->app->bind(UserAction::class);
        $this->app->bind(AuthAction::class);
        $this->app->bind(LogoutAction::class);
        $this->app->bind(UserProfileAction::class);

        // Permission
        $this->app->bind(PermissionAction::class);

        // Role
        $this->app->bind(RoleAction::class);
    }


    /**
    * {@inheritdoc}
    */
    public function addRoutes(Router $router): void
    {
        $router->group('/user', function (Router $router) {
            $router->add('/login', AuthAction::class, ['GET', 'POST'], 'user_login');
            $router->get('/logout', LogoutAction::class, 'user_logout');
            $router->resource('', UserAction::class, 'user');

            $router->group('/profile', function (Router $router) {
                $router->get('', sprintf('%s@detail', UserProfileAction::class), 'user_profile');
                $router->add(
                    '/update',
                    sprintf('%s@update', UserProfileAction::class),
                    ['GET', 'POST'],
                    'user_profile_update'
                );
            });
        });

        $router->resource('/permission', PermissionAction::class, 'permission');
        $router->resource('/role', RoleAction::class, 'role');
    }
}
