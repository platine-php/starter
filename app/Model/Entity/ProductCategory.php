<?php

declare(strict_types=1);

namespace Platine\App\Model\Entity;

use Platine\Orm\Entity;
use Platine\Orm\Mapper\EntityMapperInterface;

/**
* @class ProductCategory
* @package Platine\App\Model\Entity
* @extends Entity<ProductCategory>
*/
class ProductCategory extends Entity
{
    /**
    * @param EntityMapperInterface<ProductCategory> $mapper
    * @return void
    */
    public static function mapEntity(EntityMapperInterface $mapper): void
    {
        $mapper->table('product_categories');
        $mapper->useTimestamp();
        $mapper->casts([
           'created_at' => 'date',
           'updated_at' => '?date',
        ]);
    }
}
