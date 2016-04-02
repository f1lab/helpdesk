<?php

class ReportForm extends sfFormSymfony
{
  public function configure()
  {
    $this->disableLocalCSRFProtection();

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
      ->offsetSet('type', new sfWidgetFormChoice([
        'label' => 'Тип отчёта',
        'choices' => [
          'createdIn+closed' => 'Закрытые заявки, созданные в указанный период',
          'createdIn+notClosed' => 'Открытые заявки, созданные в указанный период',
          'createdBefore+closed' => 'Созданные ранее указанного периода и закрытые в указанный',
          'createdBefore+notClosed' => 'Созданные ранее указанного периода и до сих пор не закрытые',
        ],
      ], ['class' => 'span6']))
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
        // 'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardGroup')
          ->andWhereIn('id', $companyIds)
      )))

      ->offsetSet('responsible_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'label' => 'Выполнивший заявку',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser')
          ->addWhere('type = ?', 'it-admin')
      ), ['class' => 'chzn-select']))

      ->offsetSet('headers_drawer', new sfWidgetFormChoice([
        'label' => ' ',
        'choices' => [
          0 => 'Не повторять заголовки',
          15 => 'Повторить каждые 15 строк',
          30 => 'Повторить каждые 50 строк',
          60 => 'Повторить каждые 50 строк',
        ],
      ]))

      ->setNameFormat('filter[%s]')
    ;

    $this->getValidatorSchema()
      ->offsetSet('type', new sfValidatorPass(array(
        'required' => true,
      )))
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
      ->offsetSet('headers_drawer', new sfValidatorPass())
    ;
  }
}



