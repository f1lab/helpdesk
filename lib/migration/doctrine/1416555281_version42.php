<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version42 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('ref_user_category', array(
             'id' =>
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'category_id' =>
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'user_id' =>
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
        $this->dropTable('ref_user_category');
    }
}