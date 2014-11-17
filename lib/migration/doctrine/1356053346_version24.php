<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version24 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('shedule', 'shedule_company_id_sf_guard_group_id', array(
             'name' => 'shedule_company_id_sf_guard_group_id',
             'local' => 'company_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_group',
             ));
        $this->addIndex('shedule', 'shedule_company_id', array(
             'fields' => 
             array(
              0 => 'company_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('shedule', 'shedule_company_id_sf_guard_group_id');
        $this->removeIndex('shedule', 'shedule_company_id', array(
             'fields' => 
             array(
              0 => 'company_id',
             ),
             ));
    }
}