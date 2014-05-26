<?php

use Phinx\Migration\AbstractMigration;

class CreatePlane extends AbstractMigration
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
        $users = $this->table('plane');
        $users->addColumn('user', 'integer', array('limit' => 11))
              ->addColumn('make', 'integer', array('limit' => 11))
              ->addColumn('title', 'string', array('limit' => 200))
              ->addColumn('description', 'text')              
              ->addColumn('created', 'datetime')
              ->addColumn('updated', 'timestamp', array('default' => 'CURRENT_TIMESTAMP','update' => 'CURRENT_TIMESTAMP'))
              ->create();

        $trigger = $this->execute("CREATE TRIGGER plane_created_datetime BEFORE INSERT ON plane FOR EACH ROW SET NEW.created = NOW()");
    
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('plane');
    }
}