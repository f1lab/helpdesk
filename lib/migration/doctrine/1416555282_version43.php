<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version43 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('ref_user_category', 'ref_user_category_category_id_category_id', array(
             'name' => 'ref_user_category_category_id_category_id',
             'local' => 'category_id',
             'foreign' => 'id',
             'foreignTable' => 'category',
             ));
        $this->addIndex('ref_user_category', 'ref_user_category_category_id', array(
             'fields' =>
             array(
              0 => 'category_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('ref_user_category', 'ref_user_category_category_id_category_id');
        $this->removeIndex('ref_user_category', 'ref_user_category_category_id', array(
             'fields' =>
             array(
              0 => 'category_id',
             ),
             ));
    }
}
