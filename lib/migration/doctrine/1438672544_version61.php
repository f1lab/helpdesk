<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version61 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_ticket_id_ticket_id', array(
             'name' => 'ref_ticket_repeater_observer_ticket_id_ticket_id',
             'local' => 'ticket_id',
             'foreign' => 'id',
             'foreignTable' => 'ticket',
             ));
        $this->createForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_user_id_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_observer_user_id_sf_guard_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             ));
        $this->createForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_created_by_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_observer_created_by_sf_guard_user_id',
             'local' => 'created_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_updated_by_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_observer_updated_by_sf_guard_user_id',
             'local' => 'updated_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_ticket_id_ticket_id', array(
             'name' => 'ref_ticket_repeater_responsible_ticket_id_ticket_id',
             'local' => 'ticket_id',
             'foreign' => 'id',
             'foreignTable' => 'ticket',
             ));
        $this->createForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_user_id_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_responsible_user_id_sf_guard_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             ));
        $this->createForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_created_by_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_responsible_created_by_sf_guard_user_id',
             'local' => 'created_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_updated_by_sf_guard_user_id', array(
             'name' => 'ref_ticket_repeater_responsible_updated_by_sf_guard_user_id',
             'local' => 'updated_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('ticket_repeater', 'ticket_repeater_company_id_sf_guard_group_id', array(
             'name' => 'ticket_repeater_company_id_sf_guard_group_id',
             'local' => 'company_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_group',
             ));
        $this->createForeignKey('ticket_repeater', 'ticket_repeater_category_id_category_id', array(
             'name' => 'ticket_repeater_category_id_category_id',
             'local' => 'category_id',
             'foreign' => 'id',
             'foreignTable' => 'category',
             ));
        $this->createForeignKey('ticket_repeater', 'ticket_repeater_initiator_id_sf_guard_user_id', array(
             'name' => 'ticket_repeater_initiator_id_sf_guard_user_id',
             'local' => 'initiator_id',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             ));
        $this->createForeignKey('ticket_repeater', 'ticket_repeater_created_by_sf_guard_user_id', array(
             'name' => 'ticket_repeater_created_by_sf_guard_user_id',
             'local' => 'created_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('ticket_repeater', 'ticket_repeater_updated_by_sf_guard_user_id', array(
             'name' => 'ticket_repeater_updated_by_sf_guard_user_id',
             'local' => 'updated_by',
             'foreign' => 'id',
             'foreignTable' => 'sf_guard_user',
             'onUpdate' => 'CASCADE',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_ticket_id', array(
             'fields' =>
             array(
              0 => 'ticket_id',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_ticket_id', array(
             'fields' =>
             array(
              0 => 'ticket_id',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->addIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
        $this->addIndex('ticket_repeater', 'ticket_repeater_company_id', array(
             'fields' =>
             array(
              0 => 'company_id',
             ),
             ));
        $this->addIndex('ticket_repeater', 'ticket_repeater_category_id', array(
             'fields' =>
             array(
              0 => 'category_id',
             ),
             ));
        $this->addIndex('ticket_repeater', 'ticket_repeater_initiator_id', array(
             'fields' =>
             array(
              0 => 'initiator_id',
             ),
             ));
        $this->addIndex('ticket_repeater', 'ticket_repeater_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->addIndex('ticket_repeater', 'ticket_repeater_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_ticket_id_ticket_id');
        $this->dropForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_user_id_sf_guard_user_id');
        $this->dropForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_created_by_sf_guard_user_id');
        $this->dropForeignKey('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_updated_by_sf_guard_user_id');
        $this->dropForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_ticket_id_ticket_id');
        $this->dropForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_user_id_sf_guard_user_id');
        $this->dropForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_created_by_sf_guard_user_id');
        $this->dropForeignKey('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_updated_by_sf_guard_user_id');
        $this->dropForeignKey('ticket_repeater', 'ticket_repeater_company_id_sf_guard_group_id');
        $this->dropForeignKey('ticket_repeater', 'ticket_repeater_category_id_category_id');
        $this->dropForeignKey('ticket_repeater', 'ticket_repeater_initiator_id_sf_guard_user_id');
        $this->dropForeignKey('ticket_repeater', 'ticket_repeater_created_by_sf_guard_user_id');
        $this->dropForeignKey('ticket_repeater', 'ticket_repeater_updated_by_sf_guard_user_id');
        $this->removeIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_ticket_id', array(
             'fields' =>
             array(
              0 => 'ticket_id',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_observer', 'ref_ticket_repeater_observer_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_ticket_id', array(
             'fields' =>
             array(
              0 => 'ticket_id',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_user_id', array(
             'fields' =>
             array(
              0 => 'user_id',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->removeIndex('ref_ticket_repeater_responsible', 'ref_ticket_repeater_responsible_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
        $this->removeIndex('ticket_repeater', 'ticket_repeater_company_id', array(
             'fields' =>
             array(
              0 => 'company_id',
             ),
             ));
        $this->removeIndex('ticket_repeater', 'ticket_repeater_category_id', array(
             'fields' =>
             array(
              0 => 'category_id',
             ),
             ));
        $this->removeIndex('ticket_repeater', 'ticket_repeater_initiator_id', array(
             'fields' =>
             array(
              0 => 'initiator_id',
             ),
             ));
        $this->removeIndex('ticket_repeater', 'ticket_repeater_created_by', array(
             'fields' =>
             array(
              0 => 'created_by',
             ),
             ));
        $this->removeIndex('ticket_repeater', 'ticket_repeater_updated_by', array(
             'fields' =>
             array(
              0 => 'updated_by',
             ),
             ));
    }
}