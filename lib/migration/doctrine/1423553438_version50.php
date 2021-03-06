<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version50 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ref_ticket_observer', array(
             'id' =>
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'ticket_id' =>
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'user_id' =>
             array(
              'type' => 'integer',
              'length' => '8',
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
    }

    public function down()
    {
        $this->dropTable('ref_ticket_observer');
    }
}
