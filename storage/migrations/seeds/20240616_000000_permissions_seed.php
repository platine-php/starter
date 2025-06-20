<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class PermissionsSeed20240616000000 extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'id' => 1,
        'code' => 'permission_list',
        'description' => 'View permissions',
        'created_at' => '2023-12-06 00:21:14',
        'updated_at' => NULL,
    ],
    1 => [
        'id' => 2,
        'code' => 'permission_create',
        'description' => 'Create permission',
        'created_at' => '2023-12-06 00:38:47',
        'updated_at' => NULL,
    ],
    2 => [
        'id' => 3,
        'code' => 'permission_detail',
        'description' => 'View detail permission',
        'created_at' => '2023-12-06 00:39:33',
        'updated_at' => NULL,
    ],
    3 => [
        'id' => 4,
        'code' => 'permission_update',
        'description' => 'Edit permission',
        'created_at' => '2023-12-06 00:39:54',
        'updated_at' => NULL,
    ],
    4 => [
        'id' => 5,
        'code' => 'permission_delete',
        'description' => 'Delete permission',
        'created_at' => '2023-12-06 00:40:11',
        'updated_at' => '2023-12-06 00:46:18',
    ],
    5 => [
        'id' => 6,
        'code' => 'role_list',
        'description' => 'View roles',
        'created_at' => '2023-12-06 00:21:14',
        'updated_at' => NULL,
    ],
    6 => [
        'id' => 7,
        'code' => 'role_create',
        'description' => 'Create role',
        'created_at' => '2023-12-06 00:38:47',
        'updated_at' => NULL,
    ],
    7 => [
        'id' => 8,
        'code' => 'role_detail',
        'description' => 'View detail role',
        'created_at' => '2023-12-06 00:39:33',
        'updated_at' => NULL,
    ],
    8 => [
        'id' => 9,
        'code' => 'role_update',
        'description' => 'Edit role',
        'created_at' => '2023-12-06 00:39:54',
        'updated_at' => NULL,
    ],
    9 => [
        'id' => 10,
        'code' => 'role_delete',
        'description' => 'Delete role',
        'created_at' => '2023-12-06 00:40:11',
        'updated_at' => '2023-12-06 00:46:18',
    ],
    10 => [
        'id' => 11,
        'code' => 'user_list',
        'description' => 'View users',        
        'created_at' => '2023-12-06 00:21:14',
        'updated_at' => NULL,
    ],
    11 => [
        'id' => 12,
        'code' => 'user_create',
        'description' => 'Create user',
        'created_at' => '2023-12-06 00:38:47',
        'updated_at' => NULL,
    ],
    12 => [
        'id' => 13,
        'code' => 'user_detail',
        'description' => 'View detail user',
        'created_at' => '2023-12-06 00:39:33',
        'updated_at' => NULL,
    ],
    13 => [
        'id' => 14,
        'code' => 'user_update',
        'description' => 'Edit user',
        'created_at' => '2023-12-06 00:39:54',
        'updated_at' => NULL,
    ],
    14 => [
        'id' => 15,
        'code' => 'user_delete',
        'description' => 'Delete user',
        'created_at' => '2023-12-06 00:40:11',
        'updated_at' => '2023-12-06 00:46:18',
    ],
    15 => [
        'id' => 16,
        'code' => 'product_category_list',
        'description' => 'View product categories',        
        'created_at' => '2023-12-06 00:21:14',
        'updated_at' => NULL,
    ],
    16 => [
        'id' => 17,
        'code' => 'product_category_create',
        'description' => 'Create product category',
        'created_at' => '2023-12-06 00:38:47',
        'updated_at' => NULL,
    ],
    17 => [
        'id' => 18,
        'code' => 'product_category_detail',
        'description' => 'View detail product category',
        'created_at' => '2023-12-06 00:39:33',
        'updated_at' => NULL,
    ],
    18 => [
        'id' => 19,
        'code' => 'product_category_update',
        'description' => 'Edit product category',
        'created_at' => '2023-12-06 00:39:54',
        'updated_at' => NULL,
    ],
    19 => [
        'id' => 20,
        'code' => 'product_category_delete',
        'description' => 'Delete product category',
        'created_at' => '2023-12-06 00:40:11',
        'updated_at' => '2023-12-06 00:46:18',
    ],
    20 => [
        'id' => 21,
        'code' => 'product_list',
        'description' => 'View products',        
        'created_at' => '2023-12-06 00:21:14',
        'updated_at' => NULL,
    ],
    21 => [
        'id' => 22,
        'code' => 'product_create',
        'description' => 'Create product',
        'created_at' => '2023-12-06 00:38:47',
        'updated_at' => NULL,
    ],
    22 => [
        'id' => 23,
        'code' => 'product_detail',
        'description' => 'View detail product',
        'created_at' => '2023-12-06 00:39:33',
        'updated_at' => NULL,
    ],
    23 => [
        'id' => 24,
        'code' => 'product_update',
        'description' => 'Edit product',
        'created_at' => '2023-12-06 00:39:54',
        'updated_at' => NULL,
    ],
    24 => [
        'id' => 25,
        'code' => 'product_delete',
        'description' => 'Delete product',
        'created_at' => '2023-12-06 00:40:11',
        'updated_at' => '2023-12-06 00:46:18',
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('permissions');
        }
        
    }
}