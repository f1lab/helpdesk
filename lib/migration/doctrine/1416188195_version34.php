<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version34 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('ref_company_notify', 'ref_company_notify_group_id_sf_guard_group_id', array(
             'name' => 'ref_company_notify_group_id_sf_guard_group_id',
             'local' => 'group_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_group',
             ));
        $this->createForeignKey('ref_company_notify', 'ref_company_notify_user_id_sf_guard_user_id', array(
             'name' => 'ref_company_notify_user_id_sf_guard_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             ));
        $this->addIndex('ref_company_notify', 'ref_company_notify_group_id', array(
             'fields' =>
             array(
              0 => 'group_id',
             ),
             ));
        $this->addIndex('ref_company_notify', 'ref_company_notify_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('ref_company_notify', 'ref_company_notify_group_id_sf_guard_group_id');
        $this->dropForeignKey('ref_company_notify', 'ref_company_notify_user_id_sf_guard_user_id');
        $this->removeIndex('ref_company_notify', 'ref_company_notify_group_id', array(
             'fields' =>
             array(
              0 => 'group_id',
             ),
             ));
        $this->removeIndex('ref_company_notify', 'ref_company_notify_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
    }
}
