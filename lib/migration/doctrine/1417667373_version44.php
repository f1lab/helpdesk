<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version44 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('ticket', 'real_sender', 'string', '255', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('ticket', 'real_sender');
    }
}
