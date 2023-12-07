<?php

declare(strict_types=1);

namespace Platine\App\Model\Repository;

use Platine\Orm\EntityManager;
use Platine\Orm\Repository;
use Platine\App\Model\Entity\ProductCategory;

/**
* @class ProductCategoryRepository
* @package Platine\App\Model\Repository
* @extends Repository<ProductCategory>
*/
class ProductCategoryRepository extends Repository
{
    /**
    * Create new instance
    * @param EntityManager<ProductCategory> $manager
    */
    public function __construct(EntityManager $manager)
    {
        parent::__construct($manager, ProductCategory::class);
    }
}
