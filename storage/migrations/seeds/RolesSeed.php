<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class RolesSeed extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'id' => 1,
        'name' => 'Administrator',
        'description' => NULL,
        'created_at' => '2023-12-06 00:37:48',
        'updated_at' => '2023-12-07 06:16:19',
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('roles');
        }
        
    }
}