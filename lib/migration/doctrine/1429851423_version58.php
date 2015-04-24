<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version58 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('ref_ticket_responsible', 'created_at', 'timestamp', '25', array(
             'default' => '2015-01-01 00:00:00',
             'notnull' => '1',
             ));
        $this->addColumn('ref_ticket_responsible', 'updated_at', 'timestamp', '25', array(
             'default' => '2015-01-01 00:00:00',
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('ref_ticket_responsible', 'created_at');
        $this->removeColumn('ref_ticket_responsible', 'updated_at');
    }
}