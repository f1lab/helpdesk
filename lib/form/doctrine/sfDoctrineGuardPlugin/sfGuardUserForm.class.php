<?php

/**
 * sfGuardUser form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
    unset (
      $this['updated_at'],
      $this['created_at'],
      $this['salt'],
      $this['is_active'],
      $this['is_super_admin'],
      $this['algorithm'],
      $this['last_login'],
      $this['responsible_for_tickets_list']
    );

    $this->getWidgetSchema()
      ->offsetSet('password', new sfWidgetFormInputPassword(array()))
      ->offsetSet('groups_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => false,
        'model' => 'sfGuardGroup',
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
      ->offsetSet('permissions_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardPermission',
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
      ->offsetSet('responsible_for_company_list', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardGroup',
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
    ;

    $this->getWidgetSchema()->offsetGet('type')->setOption('choices', [
      'user' => 'Пользователь',
      'it-admin' => 'IT-администратор',
    ]);

    $this->getWidgetSchema()->offsetGet('phone')
      ->setAttribute('pattern', '\+7[0-9]{10}')
      ->setAttribute('placeholder', '+79147911019')
    ;

    $this->validatorSchema['password'] = new sfValidatorString(array('required' => true, 'max_length' => 32));

    $this->widgetSchema->setLabels(array(
      'first_name' => 'Имя',
      'last_name' => 'Фамилия',
      'email_address' => 'Email',
      'permissions_list' => 'Права',
      'password' => 'Пароль',
      'groups_list' => 'Компания',
      'responsible_for_company_list' => 'Отвечает за компанию',
      'type' => 'Тип пользователя',
      'phone' => 'Номер телефона',
    ));
  }
}
