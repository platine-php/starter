<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class ProductCategoriesSeed20240616000001 extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'id' => 1,
        'name' => 'Computer',
        'description' => NULL,
        'created_at' => '2023-12-07 05:54:51',
        'updated_at' => '2023-12-07 05:57:41',
    ],
    1 => [
        'id' => 2,
        'name' => 'Cars',
        'description' => NULL,
        'created_at' => '2023-12-07 05:55:06',
        'updated_at' => NULL,
    ],
    2 => [
        'id' => 3,
        'name' => 'Medicaments ',
        'description' => NULL,
        'created_at' => '2023-12-07 05:55:19',
        'updated_at' => NULL,
    ],
    3 => [
        'id' => 4,
        'name' => 'Others',
        'description' => NULL,
        'created_at' => '2023-12-07 05:55:28',
        'updated_at' => NULL,
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('product_categories');
        }
        
    }
}