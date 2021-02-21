<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateVariantsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('variants',['id' => false]);
        $table->addColumn('id', 'string', ['limit' => 36,'comment' => 'uuid of variant'])
        ->addColumn('color', 'string', ['limit' => 100,'comment' => 'color variant of product'])
        ->addColumn('size', 'string', ['limit' => 100,'comment' => 'size variant of product'])
        ->create();
    }
    
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('variants')->drop()->save();
    }
}
