<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
    unset (
      $this['username']
      , $this['categories_list']
      , $this['notify_sms_for_company_list']
      , $this['notify_email_for_company_list']
      , $this['observer_for_tickets_list']
    );

    $this->widgetSchema->setLabels([
      'first_name' => 'Имя',
      'last_name' => 'Фамилия',
      'email_address' => 'Email',
      'password' => 'Пароль',
      'password_again' => 'Пароль ещё раз',
      'phone' => 'Номер телефона',
      'position' => 'Должность',
      'phone' => 'Телефон',
      'work_phone' => 'Рабочий телефон',
    ]);
  }
}

