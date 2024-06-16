<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class ProductsSeed20240616000002 extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'id' => 1,
        'name' => 'Toyota Corolla Verso 1.8',
        'description' => NULL,
        'price' => 3467.0,
        'quantity' => 2.0,
        'product_category_id' => 2,
        'created_at' => '2023-12-07 06:21:59',
        'updated_at' => NULL,
    ],
    1 => [
        'id' => 2,
        'name' => 'HP 840',
        'description' => NULL,
        'price' => 890.0,
        'quantity' => 14.0,
        'product_category_id' => 1,
        'created_at' => '2023-12-07 06:26:46',
        'updated_at' => '2023-12-07 14:18:29',
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('products');
        }
        
    }
}