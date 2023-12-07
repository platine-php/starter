<?php

declare(strict_types=1);

namespace Platine\App\Provider;

use Platine\App\Http\Action\Product\CategoryAction;
use Platine\App\Http\Action\Product\ProductAction;
use Platine\App\Model\Repository\ProductCategoryRepository;
use Platine\App\Model\Repository\ProductRepository;
use Platine\Framework\Service\ServiceProvider;
use Platine\Route\Router;

/**
* @class ProductServiceProvider
* @package Platine\App\Provider
*/
class ProductServiceProvider extends ServiceProvider
{
    /**
    * {@inheritdoc}
    */
    public function register(): void
    {
        $this->app->bind(ProductCategoryRepository::class);
        $this->app->bind(ProductRepository::class);

        $this->app->bind(CategoryAction::class);
        $this->app->bind(ProductAction::class);
    }


    /**
    * {@inheritdoc}
    */
    public function addRoutes(Router $router): void
    {
        $router->group('/product', function (Router $router) {
            $router->resource('', ProductAction::class, 'product');
            $router->resource('/category', CategoryAction::class, 'product_category');
        });
    }
}
