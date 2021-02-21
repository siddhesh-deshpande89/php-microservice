<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTransactionsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('transactions',['id' => false]);
        $table->addColumn('id', 'string', ['limit' => 36,'comment' => 'uuid'])
        ->addColumn('sku', 'integer', ['limit' => 10,'comment' => 'sku of product'])
        ->addColumn('variant_id', 'string', ['limit' => 36,'comment' => 'uuid of variant'])
        ->addColumn('title', 'string', ['limit' => 190,'comment' => 'title of product'])
        ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP','comment' => 'timestamp when transaction was created'])
        ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP','comment' => 'timestamp when transaction was updated'])
        ->addIndex(['sku', 'variant_id'], ['unique' => true])
        ->addIndex('variant_id', ['unique' => true])
        ->create();
    }
    
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('transactions')->drop()->save();
    }
}
