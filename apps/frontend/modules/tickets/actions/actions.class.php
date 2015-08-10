<?php

/**
 * tickets actions.
 *
 * @package    helpdesk
 * @subpackage tickets
 * @author     Saritskiy Roman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $state = 'getTickets' . mb_convert_case($request->getParameter('state'), MB_CASE_TITLE);
    $this->tickets = $this->getUser()->getGuardUser()->$state();
  }

  public function executeV2()
  {

  }

  public function executeCompany(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->hasCredential('view_company_tickets'));

    /* $state = 'getTickets' . mb_convert_case($request->getParameter('state'), MB_CASE_TITLE);
    $this->tickets = Doctrine_Core::getTable('Ticket')->$state(); */

    $usersIds = $this->getUser()->getGroups()->getFirst()->getUsersIds();

    $q = Doctrine_Core::getTable('Ticket')->createQuery('a, a.Creator')
      ->where('a.created_by in (' . join($usersIds, ', ') . ')')
    ;

    if ('opened' == $request->getParameter('state')) {
      $q->andWhere('a.isClosed = ?', false);
    } elseif ('closed' == $request->getParameter('state')) {
      $q->andWhere('a.isClosed = ?', true);
    }

    $this->tickets = $q->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->isRepeater = $request->getParameter('repeater', false);

    if (!$this->isRepeater) {
      $this->ticket = Doctrine_Query::create()
        ->from('Ticket t')
        ->leftJoin('t.Creator')
        ->leftJoin('t.Comments comments')
        ->leftJoin('comments.Creator')
        ->addOrderBy('comments.created_at asc')
        ->where('t.id = ?', $request->getParameter('id'))
        ->fetchOne()
      ;
      $this->forward404Unless($this->ticket);

      $this->form = new CommentForm();

      //проверка на прочтение пользователем заявки
      $isReaded = Doctrine_Core::getTable('ReadedTickets')->createQuery('a')
        ->where('a.user_id = ?', $this->getContext()->getUser()->getGuardUser()->getId())
        ->andWhere('a.ticket_id = ?', $this->ticket->getId())
        ->count() === true
      ;
      if (!$isReaded) {
        $record = ReadedTickets::createFromArray([
          'user_id' => $this->getUser()->getGuardUser()->getId(),
          'ticket_id' => $this->ticket->getId(),
        ]);
        $record->save();
      }

    } else {
      $this->ticket = Doctrine_Query::create()
        ->from('TicketRepeater t')
        ->leftJoin('t.Creator')
        ->where('t.id = ?', $request->getParameter('id'))
        ->fetchOne()
      ;
      $this->forward404Unless($this->ticket);
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TicketForm();
    if (!$this->getUser()->getGuardUser()->getGroups()->getFirst()->getIsExecutor()){
      $this->form->getWidgetSchema()->offsetUnset('responsibles_list');
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new TicketForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function processForm(
      sfWebRequest $request,
      sfForm $form,
      $flash = array(
        'success',
        'Отлично!',
        'Заявка добавлена.',
      ),
      $redirect = false
  ) {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid()) {
      $ticket = $form->save();
      $this->getUser()->setFlash('message', $flash);
      $this->redirect($redirect ? $redirect : '@tickets-show?id=' . $ticket->getId());
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getUser()->hasPermission('delete_tickets')) {
      if (Doctrine_Core::getTable('Ticket')->find($request->getParameter('id'))->delete()) {
        $this->getUser()->setFlash('message', array('success', 'Отлично!', 'Одной заявкой меньше.'));
      }
    }
    $this->redirect('tickets/v2');
  }

  public function executeComment(sfWebRequest $request)
  {
    $comment = new Comment();
    $comment->setTicketId($request->getParameter('id'));

    $form = new CommentForm($comment);
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid()) {
      $this->getUser()->setFlash('message', array('success', 'Отлично!', 'Комментарий добавлен.'));
      $comment = $form->save();
      $this->redirect('@tickets-show?id=' . $comment->getTicketId());
    } else {
      //$this->redirect('@tickets-show?id=' . $request->getParameter('id'));
      $this->form = $form;
    }
  }

  public function executeCommentDelete(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('can_delete_comments')) {
      Doctrine_Query::create()
        ->from('ReadedComments readed')
        ->addWhere('readed.comment_id = ?', $request->getParameter('comment'))
        ->execute()
        ->delete()
      ;

      if (Doctrine_Core::getTable('Comment')->find($request->getParameter('comment'))->delete()) {
        $this->getUser()->setFlash('message', array('success', 'Отлично!', 'Одним комментарием меньше.'));
      }
    }

    $this->redirect('@tickets-show?id=' . $request->getParameter('id'));
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless(
      $this->getUser()->hasCredential('can_edit_tickets')
      and true == ($this->form = new TicketForm(Doctrine_Core::getTable('Ticket')->find($request->getParameter('id'))))
    );

    unset($this->form['attach']);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless(
      $this->getUser()->hasCredential('can_edit_tickets')
      and true == ($this->form = new TicketForm(Doctrine_Core::getTable('Ticket')->find($request->getParameter('id'))))
    );

    unset($this->form['attach']);
    $this->processForm($request, $this->form, array('success', 'Отлично!', 'Изменения сохранены.'));

    $this->setTemplate('edit');
  }

  //Получение не прочитанных пользователем заявок за которые он ответственный
  public function executeUnread(sfWebRequest $request)
  {
    if ($this->getUser()->getGuardUser()->getGroups()->getFirst()->getIsExecutor()){
      $user = $this->getUser()->getGuardUser()->getId();
      $query = "
      select distinct t.id
      from ticket t
      left join ref_ticket_responsible rftr on rftr.ticket_id = t.id
      where
      t.deleted_at IS NULL
      AND
      t.isclosed != 1
      AND
      (rftr.user_id= ".$user."
      or
      t.created_by in (SELECT s3.id AS id
      FROM sf_guard_user s
      LEFT JOIN ref_company_responsible r ON (s.id = r.user_id)
      LEFT JOIN sf_guard_group s2 ON s2.id = r.group_id
      LEFT JOIN sf_guard_user_group s4 ON (s2.id = s4.group_id)
      LEFT JOIN sf_guard_user s3 ON s3.id = s4.user_id
      WHERE (s.id = ".$user.")
      ORDER BY id))
      AND
      t.id
      NOT in
      (select t.id
      from ticket t
      left join readed_tickets rt on rt.ticket_id = t.id WHERE rt.user_id="
      .$user.
      "
      )
      "
      ;

     $executedQuery = Doctrine_Manager::connection()
        ->execute($query)
        ->fetchAll(PDO::FETCH_COLUMN)
      ;
      $countUnreadedTickets = count($executedQuery);
	  }
    return $this->renderText(json_encode(array(
      'countUnreadedTickets' => $countUnreadedTickets,
    )));
  }

  public function executeApply(sfWebRequest $request) {
    $ticket = Doctrine_Core::getTable('Ticket')->findOneById($request->getParameter('id'));
    $this->forward404Unless($ticket);

    $applier = Comment::createFromArray([
      'changed_ticket_state_to' => 'applied'
      , 'ticket_id' => $ticket->getId()
      , 'text' => 'Принял в работу'
    ]);
    $applier->save() and $this->redirect($request->getReferer());
  }
}
