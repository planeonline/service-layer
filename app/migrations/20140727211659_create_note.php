<?php

use Phinx\Migration\AbstractMigration;

class CreateNote extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $make = $this->table('note');
        $make->addColumn('title', 'string', array('limit' => 200))
            ->addColumn('description', 'text')
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'timestamp', array('default' => 'CURRENT_TIMESTAMP','update' => 'CURRENT_TIMESTAMP'))
            ->addColumn('status','integer', array('limit' => 2, 'default'=>0))
            ->create();

        $trigger = $this->execute("CREATE TRIGGER note_created_datetime BEFORE INSERT ON note FOR EACH ROW SET NEW.created = NOW()");

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('note');
    }
}