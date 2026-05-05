<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'encoding' => 'utf8mb4',
        ]);

        $table->addColumn('id', 'integer', [
            'identity' => true,
            'signed' => false,
        ]);

        $table->addColumn('email', 'string', [
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('password', 'string', [
            'limit' => 255,
            'null' => false,
        ]);

        $table->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
        ]);

        $table->addIndex(['email'], ['unique' => true]);
        
        $table->create();
    }
}
