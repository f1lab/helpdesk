<?php

/**
 * sfGuardGroup form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupForm extends PluginsfGuardGroupForm
{
  public function configure()
  {
    unset (
      $this['permissions_list'],
      $this['isExecutor'],
      $this['isClient']
    );

    $this->getWidgetSchema()
      ->offsetSet('description', new sfWidgetFormInputText(array()))
      ->offsetSet('responsibles_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardUser',
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser a')
          ->leftJoin('a.Groups b')
          ->where('b.isExecutor = ?', true),
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
      ->offsetSet('users_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardUser',
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))

      ->offsetSet('notify_sms_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardUser',
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser u')
          ->where('u.type = ?', 'it-admin')
        ,
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))

      ->offsetSet('notify_email_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardUser',
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser u')
          ->where('u.type = ?', 'it-admin')
        ,
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
    ;

    $this->validatorSchema['description'] = new sfValidatorString(array(
      'max_length' => 128,
      'required' => false,
    ));

    $this->validatorSchema['name'] = new sfValidatorString(array(
      'max_length' => 32,
      'required' => true,
    ));

    $this->widgetSchema->setLabels(array(
      'name' => 'Alias',
      'users_list' => 'Пользователи',
      'description' => 'Наименование',
      'responsibles_list' => 'Ответственные',
      'notify_sms_list' => 'Кого оповещать по SMS',
      'notify_email_list' => 'Кого оповещать по Email',
      'sms_title' => 'Заголовок смс-сообщения',
      'deadline_for_setting_responsible' => 'Время для назначения ответственного, в секундах',
      'deadline_for_approving' => 'Время для принятия в работу, в секундах',
    ));
  }
}
