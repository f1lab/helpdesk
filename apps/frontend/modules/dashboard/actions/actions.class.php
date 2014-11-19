<?php

/**
 * dashboard actions.
 *
 * @package    helpdesk
 * @subpackage dashboard
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dashboardActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('@dashboard-tickets', 301);
  }

  public function executeTickets(sfWebRequest $request)
  {
    $query = Doctrine_Core::getTable('Ticket')->createQuery('a, a.Creator b, b.Groups')
      ->orderBy('created_at asc')
      ->orderBy('updated_at asc')
    ;

    if ('opened' == $request->getParameter('state')) {
      $query->andWhere('a.isClosed = ?', false);
    } elseif ('closed' == $request->getParameter('state')) {
      $query->andWhere('a.isClosed = ?', true);
    }

    $this->pager = new sfDoctrinePager(
      'Ticket',
      10
    );
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }

  public function executeComments(sfWebRequest $request)
  {
    $q = Doctrine_Core::getTable('Comment')->createQuery('a, a.Creator')
      ->orderBy('created_at desc')
      ->limit(20)
    ;

    if ('comments' == $request->getParameter('state')) {
      $q->andWhere('a.changed_ticket_state_to is null');
    }

    $this->comments = $q->execute();
  }

  public function executeSettings(sfWebRequest $request)
  {
    $this->form = new sfGuardUserAdminForm($this->getUser()->getGuardUser());

    unset (
      $this->form['groups_list']
      , $this->form['permissions_list']
      , $this->form['responsible_for_company_list']
      , $this->form['responsible_for_tickets_list']
      , $this->form['is_active']
      , $this->form['is_super_admin']
      , $this->form['notify_for_company_list']
      , $this->form['type']
    );

    $this->form->getWidgetSchema()->offsetGet('phone')
      ->setAttribute('pattern', '\+7[0-9]{10}')
      ->setAttribute('placeholder', '+71234567890')
    ;

    if ($request->isMethod('put')) {
      $this->form->bind(
        $request->getParameter($this->form->getName()),
        $request->getFiles($this->form->getName())
      );

      if ($this->form->isValid()) {
        $object = $this->form->save();
        $flash = array('success','','настройки сохранены');
        if ($flash and is_array($flash)) {
          $this->getUser()->setFlash('message', $flash);
        }
      } else {
        $flash = array('error','','настройки не сохранены');
        if ($flash and is_array($flash)) {
          $this->getUser()->setFlash('message', $flash);
        }
      }
    }

  }
}
