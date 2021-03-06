<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version30 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('ticket', 'shedule_date');
        $this->removeColumn('ticket', 'shedule_time');
        $this->addColumn('ticket', 'planned_start', 'timestamp', '25', array(
             ));
        $this->addColumn('ticket', 'planned_finish', 'timestamp', '25', array(
             ));
    }

    public function down()
    {
        $this->addColumn('ticket', 'shedule_date', 'date', '25', array(
             ));
        $this->addColumn('ticket', 'shedule_time', 'time', '25', array(
             ));
        $this->removeColumn('ticket', 'planned_start');
        $this->removeColumn('ticket', 'planned_finish');
    }
}