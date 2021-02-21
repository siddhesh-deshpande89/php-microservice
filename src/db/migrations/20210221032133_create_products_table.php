<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('products',['id' => false]);
        $table->addColumn('id', 'string', ['limit' => 36,'comment' => 'uuid of product'])
        ->addColumn('sku', 'integer', ['limit' => 10,'comment' => 'sku of product'])
        ->addColumn('title', 'string', ['limit' => 190,'comment' => 'title of product'])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP','comment' => 'timestamp of product creation'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP','comment' => 'timestamp when product was updated'])
        ->create();
    }
    
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('products')->drop()->save();
    }
}
