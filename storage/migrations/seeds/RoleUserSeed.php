<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class RoleUserSeed extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'user_id' => 1,
        'role_id' => 1,
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('roles_users');
        }
        
    }
}