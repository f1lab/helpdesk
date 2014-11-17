<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version1 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('comment', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'text' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'ticket_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'created_by' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'updated_by' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
        $this->createTable('ticket', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'name' => 
             array(
              'type' => 'string',
              'notnull' => '1',
              'length' => '255',
             ),
             'description' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'created_by' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'updated_by' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
        $this->addColumn('sf_guard_group', 'isexecutor', 'boolean', '25', array(
             ));
        $this->addColumn('sf_guard_group', 'isclient', 'boolean', '25', array(
             ));
    }

    public function down()
    {
        $this->dropTable('comment');
        $this->dropTable('ticket');
        $this->removeColumn('sf_guard_group', 'isexecutor');
        $this->removeColumn('sf_guard_group', 'isclient');
    }
}