<?php

/**
 * users actions.
 *
 * @package    helpdesk
 * @subpackage users
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usersActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->sf_guard_users = Doctrine_Core::getTable('sfGuardUser')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->sf_guard_user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->sf_guard_user);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new sfGuardUserForm();
    if ($request->hasParameter('company')) {
      $this->form->setDefault('groups_list', $request->getParameter('company'));
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new sfGuardUserForm();

    $this->processForm($request, $this->form, ['success', '', 'Пользователь добавлен.']);

    $groups = $this->form->getObject()->getGroups();
    if (count($groups) and true == ($group = $groups->getFirst())) {
      $redirect = 'companies/show?id=' . $group->getId();
    } else {
      $redirect = false;
    }

    if ($redirect) {
      $this->redirect($redirect);
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($sf_guard_user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));
    $this->form = new sfGuardUserForm($sf_guard_user);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($sf_guard_user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));
    $this->form = new sfGuardUserForm($sf_guard_user);

    $groups = $this->form->getObject()->getGroups();
    if (count($groups) and true == ($group = $groups->getFirst())) {
      $redirect = 'companies/show?id=' . $group->getId();
    } else {
      $redirect = false;
    }

    $this->processForm($request, $this->form, ['success', '', 'Пользователь обновлен.'], $redirect);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($sf_guard_user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object sf_guard_user does not exist (%s).', $request->getParameter('id')));
    $sf_guard_user->delete();

    $this->redirect('users/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form, $flash=false, $redirect=false)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $sf_guard_user = $form->save();
      if ($flash and is_array($flash)) {
        $this->getUser()->setFlash('message', $flash);
      }

      if ($redirect) {
        $this->redirect($redirect);
      }
      //$this->redirect('@users');
    }
  }
}
