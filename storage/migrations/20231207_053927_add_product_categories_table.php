<?php
declare(strict_types=1);

namespace Platine\Framework\Migration;

use Platine\Database\Schema\CreateTable;
use Platine\Framework\Migration\AbstractMigration;

class AddProductCategoriesTable20231207053927 extends AbstractMigration
{

    public function up(): void
    {
        //Action when migrate up
        $this->create('product_categories', function (CreateTable $table) {
            $table->integer('id')
                  ->autoincrement()
                 ->primary();
            
            $table->string('name')
                 ->description('The category name')
                 ->notNull();
            
            $table->string('description')
                 ->description('The category description');
            
            $table->timestamps();

            $table->engine('INNODB');
        });
    }

    public function down(): void
    {
        //Action when migrate down
        $this->drop('product_categories');
    }
}