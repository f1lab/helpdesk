<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version47 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('sf_guard_user', 'work_phone', 'string', '255', array(
             ));
        $this->addColumn('sf_guard_user', 'position', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('sf_guard_user', 'work_phone');
        $this->removeColumn('sf_guard_user', 'position');
    }
}
