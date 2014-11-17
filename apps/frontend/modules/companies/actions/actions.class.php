<?php

/**
 * companies actions.
 *
 * @package    helpdesk
 * @subpackage companies
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class companiesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->companies = Doctrine_Core::getTable('sfGuardGroup')->createQuery('a')->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->company = Doctrine_Core::getTable('sfGuardGroup')->find($request->getParameter('id'));
    $this->forward404Unless($this->company);
    $this->users = $this->company->getUsers();
  }

  public function executeNew(sfWebRequest $request)
  {
    $form = new sfGuardGroupForm();
    $form->getWidgetSchema()->offsetSet('users_list', new sfWidgetFormInputHidden());

    $this->form = $form;
  }

  public function executeCreate(sfWebRequest $request)
  {
    $form = new sfGuardGroupForm();
    $form->getWidgetSchema()->offsetSet('users_list', new sfWidgetFormInputHidden());
    $this->form = $form;

    $this->processForm($request, $this->form, array('success', 'Отлично!', 'Одной компанией больше.'), '@companies');
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->company = Doctrine_Core::getTable('sfGuardGroup')->find($request->getParameter('id'));
    $this->form = new sfGuardGroupForm($this->company);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->company = Doctrine_Core::getTable('sfGuardGroup')->find($request->getParameter('id'));
    $this->form = new sfGuardGroupForm($this->company);

    $this->processForm($request, $this->form, array('success', 'Отлично!', 'Компания сохранена.'), '@companies-show?id=' . $this->company->getId());
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('can_delete_companies')) {
      if (Doctrine_Core::getTable('sfGuardGroup')->find($request->getParameter('id'))->delete()) {
        $this->getUser()->setFlash('message', array('success', 'Отлично!', 'Одной компанией меньше.'));
      }
    }

    $this->redirect('@companies');
  }

  public function processForm(sfWebRequest $request, sfForm $form, $flash=false, $redirect=false)
  {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid())
    {
      $object = $form->save();
      if ($flash and is_array($flash)) {
        $this->getUser()->setFlash('message', $flash);
      }
      if ($redirect) {
        $this->redirect($redirect);
      }
    }
  }
  public function executeUserEdit(sfWebRequest $request)
  {
    $form = new sfGuardUserAdminForm(Doctrine_Core::getTable('sfGuardUser')->find($request->getParameter('id')));
    unset($form['groups_list']);
    unset($form['permissions_list']);
    unset($form['responsible_for_company_list']);
    unset($form['responsible_for_tickets_list']);
    unset($form['is_active']);
    unset($form['is_super_admin']);
    $this->form = $form;
    if($request->isMethod('put'))
    {
      $this->form->bind(
        $request->getParameter($this->form->getName()),
        $request->getFiles($this->form->getName())
      );

      if ($this->form->isValid())
      {
        $object = $this->form->save();
        $flash = array('success','','настройки сохранены');
        if ($flash and is_array($flash)) {
          $this->getUser()->setFlash('message', $flash);
        }
      }
      else {
        $flash = array('error','','настройки не сохранены');
        if ($flash and is_array($flash)) {
          $this->getUser()->setFlash('message', $flash);
        }
      }
    }
  }
}
