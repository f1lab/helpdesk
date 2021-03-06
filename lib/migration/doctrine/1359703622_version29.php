<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version29 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->dropForeignKey('ticket', 'ticket_campany_id_sf_guard_group_id');
        $this->createForeignKey('ticket', 'ticket_company_id_sf_guard_group_id', array(
             'name' => 'ticket_company_id_sf_guard_group_id',
             'local' => 'company_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_group',
             ));
        $this->addIndex('ticket', 'ticket_company_id', array(
             'fields' => 
             array(
              0 => 'company_id',
             ),
             ));
    }

    public function down()
    {
        $this->createForeignKey('ticket', 'ticket_campany_id_sf_guard_group_id', array(
             'name' => 'ticket_campany_id_sf_guard_group_id',
             'local' => 'campany_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_group',
             ));
        $this->dropForeignKey('ticket', 'ticket_company_id_sf_guard_group_id');
        $this->removeIndex('ticket', 'ticket_company_id', array(
             'fields' => 
             array(
              0 => 'company_id',
             ),
             ));
    }
}