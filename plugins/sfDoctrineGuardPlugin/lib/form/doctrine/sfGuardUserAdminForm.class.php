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
  {$this->widgetSchema->setLabels(array(
      'first_name' => 'Имя',
      'last_name' => 'Фамилия',
      'username' => 'Имя пользователя',
      'email_address' => 'Почтовый ящик',
      'permissions_list' => 'Права',
      'password' => 'Пароль',
      'groups_list' => 'Компания',
      'responsible_for_company_list' => 'Отвечает за компанию',
      'password_again' => 'Повторите пароль',
    ));
  }
}

