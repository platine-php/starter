<?php

namespace Platine\Framework\Migration;

use Platine\Database\Schema\CreateTable;
use Platine\Framework\Migration\AbstractMigration;

class AddUsersTable20210705065248 extends AbstractMigration
{
    public function up(): void
    {
      //Action when migrate up
        $this->create('users', function (CreateTable $table) {
            $table->integer('id')
                  ->autoincrement()
                 ->primary();

            $table->string('username')
                 ->description('The user username')
                 ->unique()
                 ->notNull();

            $table->string('email')
                 ->description('The user email')
                 ->unique()
                 ->notNull();

            $table->string('password')
                 ->description('The user password')
                 ->notNull();

            $table->fixed('status', 1)
                 ->description('The user status, A=Active, D=Deactive')
                 ->defaultValue('D');

            $table->string('lastname')
                 ->description('The user lastname');

            $table->string('firstname')
                 ->description('The user firstname');

            $table->string('role')
                 ->description('The user role or function');

            $table->datetime('created_at')
                  ->description('created date')
                  ->notNull();

            $table->datetime('updated_at')
                  ->description('last updated date');

            $table->engine('INNODB');
        });
    }

    public function down(): void
    {
      //Action when migrate down
        $this->drop('users');
    }
}
