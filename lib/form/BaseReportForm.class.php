<?php

class BaseReportForm extends sfFormSymfony
{
  public function configure()
  {
    $this->disableLocalCSRFProtection();

    $this->setDefault('from', (new DateTime('first day of this month'))->getTimestamp());

    $companyIds = Doctrine_Query::create()
      ->from('RefCompanyResponsible')
      ->select('group_id')
      ->addWhere('user_id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId())
      ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
    ;

    $seesCategories = Doctrine_Query::create()
      ->from('RefUserCategory ref')
      ->select('ref.category_id')
      ->addWhere('ref.user_id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId())
      ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
    ;

    $this->getWidgetSchema()
      ->offsetSet('from', new sfWidgetFormBootstrapDate([
        'label' => 'Период',
      ], [
        'placeholder' => 'От',
      ]))
      ->offsetSet('to', new sfWidgetFormBootstrapDate([], [
        'placeholder' => 'До',
      ]))

      ->offsetSet('category_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'Category',
        'label' => 'Категории',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('Category c')
          ->andWhereIn('c.id', $seesCategories)
      ), ['class' => 'chzn-select']))

      ->offsetSet('company_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardGroup',
        'label' => 'Компания',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardGroup')
          ->andWhereIn('id', $companyIds)
      ), ['class' => 'chzn-select']))

      ->offsetSet('responsible_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'label' => 'Выполнивший заявку',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser')
          ->addWhere('type = ?', 'it-admin')
      ), ['class' => 'chzn-select']))

      ->setNameFormat('filter[%s]')
    ;

    $this->getValidatorSchema()
      ->offsetSet('from', new sfValidatorDate(array(
        'required' => false,
      )))
      ->offsetSet('to', new sfValidatorDate(array(
        'required' => false,
      )))
      ->offsetSet('category_id', new sfValidatorDoctrineChoice(array(
        'model' => 'Category',
        'required' => false,
        'multiple' => true,
      )))
      ->offsetSet('company_id', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardGroup',
        'required' => true,
        'multiple' => true,
      )))
      ->offsetSet('responsible_id', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'required' => false,
        'multiple' => true,
      )))
    ;
  }
}



