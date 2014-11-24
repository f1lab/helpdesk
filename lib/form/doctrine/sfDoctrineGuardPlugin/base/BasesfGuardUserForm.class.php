<?php

/**
 * sfGuardUser form base class.
 *
 * @method sfGuardUser getObject() Returns the current form's model object
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                           => new sfWidgetFormInputHidden(),
      'first_name'                   => new sfWidgetFormInputText(),
      'last_name'                    => new sfWidgetFormInputText(),
      'email_address'                => new sfWidgetFormInputText(),
      'username'                     => new sfWidgetFormInputText(),
      'algorithm'                    => new sfWidgetFormInputText(),
      'salt'                         => new sfWidgetFormInputText(),
      'password'                     => new sfWidgetFormInputText(),
      'is_active'                    => new sfWidgetFormInputCheckbox(),
      'is_super_admin'               => new sfWidgetFormInputCheckbox(),
      'last_login'                   => new sfWidgetFormDateTime(),
      'type'                         => new sfWidgetFormChoice(array('choices' => array('it-admin' => 'it-admin', 'user' => 'user'))),
      'phone'                        => new sfWidgetFormInputText(),
      'created_at'                   => new sfWidgetFormDateTime(),
      'updated_at'                   => new sfWidgetFormDateTime(),
      'groups_list'                  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'             => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
      'categories_list'              => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
      'responsible_for_company_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'notify_for_company_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'responsible_for_tickets_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Ticket')),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'first_name'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'last_name'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email_address'                => new sfValidatorString(array('max_length' => 255)),
      'username'                     => new sfValidatorString(array('max_length' => 128)),
      'algorithm'                    => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                         => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'                     => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'                    => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'               => new sfValidatorBoolean(array('required' => false)),
      'last_login'                   => new sfValidatorDateTime(array('required' => false)),
      'type'                         => new sfValidatorChoice(array('choices' => array(0 => 'it-admin', 1 => 'user'), 'required' => false)),
      'phone'                        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'                   => new sfValidatorDateTime(),
      'updated_at'                   => new sfValidatorDateTime(),
      'groups_list'                  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'             => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
      'categories_list'              => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
      'responsible_for_company_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'notify_for_company_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'responsible_for_tickets_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Ticket', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['permissions_list']))
    {
      $this->setDefault('permissions_list', $this->object->Permissions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['responsible_for_company_list']))
    {
      $this->setDefault('responsible_for_company_list', $this->object->ResponsibleForCompany->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['notify_for_company_list']))
    {
      $this->setDefault('notify_for_company_list', $this->object->NotifyForCompany->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['responsible_for_tickets_list']))
    {
      $this->setDefault('responsible_for_tickets_list', $this->object->ResponsibleForTickets->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGroupsList($con);
    $this->savePermissionsList($con);
    $this->saveCategoriesList($con);
    $this->saveResponsibleForCompanyList($con);
    $this->saveNotifyForCompanyList($con);
    $this->saveResponsibleForTicketsList($con);

    parent::doSave($con);
  }

  public function saveGroupsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['groups_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Groups->getPrimaryKeys();
    $values = $this->getValue('groups_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  public function savePermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Permissions->getPrimaryKeys();
    $values = $this->getValue('permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Permissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Permissions', array_values($link));
    }
  }

  public function saveCategoriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categories_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Categories->getPrimaryKeys();
    $values = $this->getValue('categories_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categories', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }

  public function saveResponsibleForCompanyList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['responsible_for_company_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->ResponsibleForCompany->getPrimaryKeys();
    $values = $this->getValue('responsible_for_company_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('ResponsibleForCompany', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('ResponsibleForCompany', array_values($link));
    }
  }

  public function saveNotifyForCompanyList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['notify_for_company_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->NotifyForCompany->getPrimaryKeys();
    $values = $this->getValue('notify_for_company_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('NotifyForCompany', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('NotifyForCompany', array_values($link));
    }
  }

  public function saveResponsibleForTicketsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['responsible_for_tickets_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->ResponsibleForTickets->getPrimaryKeys();
    $values = $this->getValue('responsible_for_tickets_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('ResponsibleForTickets', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('ResponsibleForTickets', array_values($link));
    }
  }

}
