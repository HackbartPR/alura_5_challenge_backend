<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class VideosTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('videos');
        $table->addColumn('title', 'string', ['limit' => 254])
              ->addColumn('description', 'string', ['limit' => 254])
              ->addColumn('url', 'string', ['limit' => 254])
              ->addColumn('category_id', 'integer', ['limit' => 10])
              ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();        
    }
}