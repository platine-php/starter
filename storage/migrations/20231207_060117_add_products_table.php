<?php
declare(strict_types=1);

namespace Platine\Framework\Migration;

use Platine\Database\Schema\CreateTable;
use Platine\Framework\Migration\AbstractMigration;

class AddProductsTable20231207060117 extends AbstractMigration
{

    public function up(): void
    {
        //Action when migrate up
        $this->create('products', function (CreateTable $table) {
            $table->integer('id')
                  ->autoincrement()
                 ->primary();
            
            $table->string('name')
                 ->description('The product name')
                 ->notNull();
            
            $table->string('description')
                 ->description('The product description');
            
            $table->float('price')
                   ->defaultValue(0)
                   ->description('The product price')
                   ->notNull();
            
            $table->float('quantity')
                   ->defaultValue(0)
                   ->description('The product quantity')
                   ->notNull();
            
            $table->integer('product_category_id')
                 ->description('Product category')
                  ->notNull();
            
            $table->timestamps();
            
            $table->foreign('product_category_id')
                  ->references('product_categories', 'id')
                  ->onDelete('NO ACTION');

            $table->engine('INNODB');
        });
    }

    public function down(): void
    {
        //Action when migrate down
        $this->drop('products');
    }
}