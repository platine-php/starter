<?php
declare(strict_types=1);

namespace Platine\Framework\Migration\Seed;

use Platine\Framework\Migration\Seed\AbstractSeed;

class UsersSeed20240616000005 extends AbstractSeed
{

    public function run(): void
    {
      //Action when run the seed
      
        $data = [
    0 => [
        'id' => 1,
        'username' => 'admin',
        'email' => 'admin@admin.com',
        'password' => '$2y$10$Qi77pV.yPh22hDamxxzqW.gsAhQsmib2JABtLb5kZTSB9taEwm7au',
        'status' => 'A',
        'lastname' => 'Super',
        'firstname' => 'Admin',
        'role' => 'Super Administrator',
        'created_at' => '2023-12-05 15:02:58',
        'updated_at' => '2023-12-07 05:27:40',
    ],
];
        foreach ($data as $row) {
            $this->insert($row)->into('users');
        }
        
    }
}