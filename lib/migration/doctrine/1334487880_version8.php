<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version8 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('comment', 'changed_ticket_state_to', 'enum', '', array(
             'values' => 
             array(
              0 => 'opened',
              1 => 'closed',
             ),
             ));
    }

    public function down()
    {
        $this->removeColumn('comment', 'changed_ticket_state_to');
    }
}