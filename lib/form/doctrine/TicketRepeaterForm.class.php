<?php

/**
 * TicketRepeater form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TicketRepeaterForm extends BaseTicketRepeaterForm
{
  public function configure()
  {
    unset (
      $this['created_at']
      , $this['updated_at']
      , $this['created_by']
      , $this['updated_by']
      , $this['deleted_at']
      , $this['repeater_id']
    );

    $user = sfContext::getInstance()->getUser()->getGuardUser() ?: new sfGuardUser();

    $this->getWidgetSchema()
      ->offsetSet('name', new sfWidgetFormInputText([], [
        'class' => "fluid",
      ]))

      ->offsetSet('company_id', new sfWidgetFormDoctrineChoice([
          'multiple' => false,
          'model' => 'sfGuardUser',
          'add_empty' => true,
          'query' => Doctrine_Query::create()
            ->from('sfGuardGroup a')
            ->addWhere('a.id in (select group_id from ref_company_responsible where user_id = ?)', $user->getId())
          ,
        ], [
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        ]))

      ->offsetSet('category_id', new sfWidgetFormDoctrineChoice([
          'multiple' => false,
          'model' => 'Category',
          'default' => 1,
        ], [
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        ]))

      ->offsetSet('description', new sfWidgetFormTextarea([], [
        'class' => 'fluid wysiwyg',
        'rows' => 15,
      ]))

      ->offsetSet('initiator_id', new sfWidgetFormDoctrineChoice([
          'multiple' => false,
          'model' => 'sfGuardUser',
          'add_empty' => true,
          'query' => Doctrine_Query::create()
            ->from('sfGuardUser a')
            ->addOrderBy('a.first_name, a.last_name')
          ,
        ], [
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        ]))

      ->offsetGet('repeated_every_days')
        ->setAttribute('class', 'RepeatedEveryDays')
        ->getParent()

      ->offsetSet('planned_start', new sfWidgetFormBootstrapDateTime2([
        'minView' => 0,
      ], [
        'placeholder' => '',
        'class' => 'span2',
        'type' => 'date',
      ]))

      ->offsetGet('create_before_days')
        ->setAttribute('class', 'RepeatedEveryDays')
        ->getParent()

      ->offsetGet('deadline_days')
        ->setAttribute('class', 'RepeatedEveryDays')
        ->getParent()

      ->offsetSet('responsibles_list', new sfWidgetFormDoctrineChoice([
          'multiple' => true,
          'model' => 'sfGuardUser',
          'query' => Doctrine_Query::create()
            ->from('sfGuardUser a')
            ->where('a.type = ?', 'it-admin'),
        ], [
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        ]))

      ->offsetSet('observers_list', new sfWidgetFormDoctrineChoice([
        'multiple' => true,
        'model' => 'sfGuardUser',
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser a')
          ->addOrderBy('a.first_name, a.last_name')
      ], [
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      ]))

      ->setLabels([
        'name' => 'Тема',
        'company_id' => 'На компанию',
        'category_id' => 'Категория',
        'description' => 'Описание',
        'initiator_id' => 'От чьего имени создавать заявки',
        'repeated_every_days' => 'Повторять заявку каждые',
        'planned_start' => 'Дата, начиная с которой будут создаваться заявки',
        'create_before_days' => 'Создавать заявку за N дней до даты выполнения',
        'deadline_days' => 'Срок для выполнения',
        'responsibles_list' => 'Ответственные',
        'observers_list' => 'Наблюдатели',
      ])
    ;
  }
}
