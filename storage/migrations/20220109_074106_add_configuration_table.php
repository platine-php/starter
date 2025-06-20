<?php

declare(strict_types=1);

namespace Platine\Framework\Migration;

use Platine\Database\Schema\CreateTable;
use Platine\Framework\Migration\AbstractMigration;

class AddConfigurationTable20220109074106 extends AbstractMigration
{
    public function up(): void
    {
      //Action when migrate up
        $this->create('configurations', function (CreateTable $table) {
            $table->integer('id')
                    ->autoincrement()
                    ->primary();

            $table->string('env')
                   ->description('The config environment')
                   ->index();

            $table->string('module')
                    ->description('The module')
                    ->index()
                    ->notNull();

            $table->string('name')
                  ->description('The config name')
                  ->index()
                  ->notNull();

            $table->text('value')
                    ->description('The config value');

            $table->string('type')
                   ->description('The config data type')
                   ->notNull();

            $table->text('comment')
                  ->description('The config description');

            $table->enum('status', ['Y', 'N'])
                 ->description('The config status')
                 ->defaultValue('Y')
                 ->notNull();

            $table->timestamps();
            
            $table->engine('INNODB');
        });
    }

    public function down(): void
    {
      //Action when migrate down
        $this->drop('configurations');
    }
}
