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
        ->addColumn('sku', 'integer', ['limit' => 10])
        ->addColumn('title', 'string', ['limit' => 190])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
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
