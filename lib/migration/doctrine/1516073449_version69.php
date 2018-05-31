<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version69 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('work', 'work_responsible_id_sf_guard_user_id', array(
             'name' => 'work_responsible_id_sf_guard_user_id',
             'local' => 'responsible_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             ));
        $this->createForeignKey('work', 'work_created_by_sf_guard_user_id', array(
             'name' => 'work_created_by_sf_guard_user_id',
             'local' => 'created_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('work', 'work_updated_by_sf_guard_user_id', array(
             'name' => 'work_updated_by_sf_guard_user_id',
             'local' => 'updated_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('work', 'work_responsible_id', array(
             'fields' =>
             array(
              0 => 'responsible_id',
             ),
             ));
        $this->addIndex('work', 'work_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->addIndex('work', 'work_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('work', 'work_responsible_id_sf_guard_user_id');
        $this->dropForeignKey('work', 'work_created_by_sf_guard_user_id');
        $this->dropForeignKey('work', 'work_updated_by_sf_guard_user_id');
        $this->removeIndex('work', 'work_responsible_id', array(
             'fields' =>
             array(
              0 => 'responsible_id',
             ),
             ));
        $this->removeIndex('work', 'work_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->removeIndex('work', 'work_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
    }
}