<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version41 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('ticket', 'category_id', 'integer', '8', array(
             ));
    }

    public function down()
    {

    }
}