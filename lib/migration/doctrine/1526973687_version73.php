<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version73 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('work', 'responsible_id', 'integer', '8', array(
             ));
    }

    public function down()
    {

    }
}