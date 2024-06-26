<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class RolesPermissionsSeed20240616000004 extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'permission_id' => 1,
        'role_id' => 1,
    ],
    1 => [
        'permission_id' => 2,
        'role_id' => 1,
    ],
    2 => [
        'permission_id' => 3,
        'role_id' => 1,
    ],
    3 => [
        'permission_id' => 4,
        'role_id' => 1,
    ],
    4 => [
        'permission_id' => 5,
        'role_id' => 1,
    ],
    5 => [
        'permission_id' => 6,
        'role_id' => 1,
    ],
    6 => [
        'permission_id' => 7,
        'role_id' => 1,
    ],
    7 => [
        'permission_id' => 8,
        'role_id' => 1,
    ],
    8 => [
        'permission_id' => 9,
        'role_id' => 1,
    ],
    9 => [
        'permission_id' => 10,
        'role_id' => 1,
    ],
    10 => [
        'permission_id' => 11,
        'role_id' => 1,
    ],
    11 => [
        'permission_id' => 12,
        'role_id' => 1,
    ],
    12 => [
        'permission_id' => 13,
        'role_id' => 1,
    ],
    13 => [
        'permission_id' => 14,
        'role_id' => 1,
    ],
    14 => [
        'permission_id' => 15,
        'role_id' => 1,
    ],
    15 => [
        'permission_id' => 16,
        'role_id' => 1,
    ],
    16 => [
        'permission_id' => 17,
        'role_id' => 1,
    ],
    17 => [
        'permission_id' => 18,
        'role_id' => 1,
    ],
    18 => [
        'permission_id' => 19,
        'role_id' => 1,
    ],
    19 => [
        'permission_id' => 20,
        'role_id' => 1,
    ],
    20 => [
        'permission_id' => 21,
        'role_id' => 1,
    ],
    21 => [
        'permission_id' => 22,
        'role_id' => 1,
    ],
    22 => [
        'permission_id' => 23,
        'role_id' => 1,
    ],
    23 => [
        'permission_id' => 24,
        'role_id' => 1,
    ],
    24 => [
        'permission_id' => 25,
        'role_id' => 1,
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('permissions_roles');
        }
        
    }
}