<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use HackbartPR\Tools\Hash;

final class UsersTable extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('name', 'string', ['limit' => 254])
              ->addColumn('email', 'string', ['limit' => 254])
              ->addColumn('password', 'string', ['limit' => 254])              
              ->create();

        if ($this->isMigratingUp()) {
            $email = $this->getAdapter()->getOption('email_default');
            $password = Hash::passwordHash($this->getAdapter()->getOption('password_default'));

            $table->insert([['id' => 1, 'name' => 'developer', 'email' => $email, 'password' => $password]])
                  ->save();
        }
    }
}
