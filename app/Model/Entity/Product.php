<?php

declare(strict_types=1);

namespace Platine\App\Model\Entity;

use Platine\Orm\Entity;
use Platine\Orm\Mapper\EntityMapperInterface;
use Platine\Orm\Query\Query;

/**
* @class Product
* @package Platine\App\Model\Entity
* @extends Entity<Product>
*/
class Product extends Entity
{
    /**
    * @param EntityMapperInterface<Product> $mapper
    * @return void
    */
    public static function mapEntity(EntityMapperInterface $mapper): void
    {
         $mapper->useTimestamp();
         $mapper->casts([
            'created_at' => 'date',
            'updated_at' => '?date',
         ]);

         $mapper->relation('category')->belongsTo(ProductCategory::class);

         $mapper->filter('category', function (Query $q, $value) {
             $q->where('product_category_id')->is($value);
         });
    }
}
