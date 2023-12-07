<?php

declare(strict_types=1);

namespace Platine\App\Model\Repository;

use Platine\Orm\EntityManager;
use Platine\Orm\Repository;
use Platine\App\Model\Entity\Product;

/**
* @class ProductRepository
* @package Platine\App\Model\Repository
* @extends Repository<Product>
*/
class ProductRepository extends Repository
{
    /**
    * Create new instance
    * @param EntityManager<Product> $manager
    */
    public function __construct(EntityManager $manager)
    {
        parent::__construct($manager, Product::class);
    }
}
