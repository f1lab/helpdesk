<?php

/**
 * Shedule form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Saritskyi Roman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SheduleForm extends sfForm
{
  public function configure()
  {
	 $this->getWidgetSchema()
      ->offsetSet('date', new sfWidgetFormBootstrapDateTime(array(), array(
        'placeholder' => '',
        'class' => 'span2',
        'type' => 'date',
      )))
      ->offsetSet('employee', new sfWidgetFormDoctrineChoice(array(
          'multiple' => false,
          'model' => 'sfGuardUser',
          'query' => Doctrine_Query::create()
            ->from('sfGuardUser a')
            ->leftJoin('a.Groups b')
            ->where('b.isExecutor = ?', true),
        ), array(
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        )))
    ;
     $this->setDefault('date', date("d-m-Y"));
     $this->setValidators(array(
     'date' => new sfValidatorDateTime(array('required' => false)),
     'employee' => new sfValidatorPass(),));
    $this->disableLocalCSRFProtection();
    
    
    $this->widgetSchema->setLabels(array(
     'date' => 'Дата',
     'employee' => 'Сотрудник',
    ));
  }
}
