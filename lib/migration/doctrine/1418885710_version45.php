<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version45 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('ref_ticket_responsible', 'created_by', 'integer', '8', array(
             ));
        $this->addColumn('ref_ticket_responsible', 'updated_by', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('ref_ticket_responsible', 'created_by');
        $this->removeColumn('ref_ticket_responsible', 'updated_by');
    }
}