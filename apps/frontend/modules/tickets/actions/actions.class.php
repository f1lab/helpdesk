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
    $ticketExist = Doctrine_Core::getTable('ReadedTickets')->createQuery('a')
      ->where('a.user_id = ?', $this->getContext()->getUser()->getGuardUser()->getId())
      ->andWhere('a.ticket_id = ?', $this->ticket->getId())
      ->execute()
    ;

    if ($ticketExist->count() == 0)
    {
      $record = new ReadedTickets();
      $record->setUser_id($this->getContext()->getUser()->getGuardUser()->getId());
      $record->setTicket_id($this->ticket->getId());
      $record->save();
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
    $this->redirect('@tickets-my');
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

  /*
    Основная страница расписания
  */
  public function executeShedule(sfWebRequest $request){
    $user =  $this->getContext()->getUser()->getGuardUser()->getId();
    //НЕРАЗМЕЧЕННЫЕ ЗАЯВКИ
    $query = "select *
		from ticket t
		left join ref_ticket_responsible rftr on rftr.ticket_id = t.id
		where
		t.deleted_at IS NULL
    AND
    t.isclosed = false
    AND
		( t.planned_start IS NULL or t.planned_finish IS NULL )
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
		ORDER BY id)
    or
		t.created_by = ".$user."
    )
		";
    $this->unpartitioned = Doctrine_Manager::connection()
        ->execute($query)
        ->fetchAll(Doctrine_Core::HYDRATE_ARRAY)
    ;
    // print_r($this->unpartitioned);
    $this->form =  new sheduleForm();
  }

  /*
    Назначение времени заявке при перетаскивании
  */
  public function executeSheduleEvent($request)
  {
    $event = $request->getParameter('event');
    $ref = Doctrine_Core::getTable('Ticket')->find($event['id']);
    $ref->setPlannedStart(date('Y-m-d H:i:s', $event['start']));
    $ref->setPlannedFinish(date('Y-m-d H:i:s', $event['end'] ?: strtotime('+2 hour', $event['start'])));
    $ref->save();

    return sfView::NONE;
  }

  /*
    Создание новой заявки посредством календаря расписания
  */
  public function executeSheduleEventNew($request)
  {
    $event = $request->getParameter('event');
    $ref = new Ticket();
    $ref->setName($event['title']);
    $ref->setPlannedStart(date('Y-m-d H:i:s', $event['start']));
    $ref->setPlannedFinish(date('Y-m-d H:i:s', $event['end'] ?: strtotime('+2 hour', $event['start'])));
    $ref->save();

    return sfView::NONE;
  }

  /*
    Модальное окно для подробного просмотра заявки в календаре
  */
  public function executeSheduleModal($request)
  {
    $this->ticket = Doctrine_Query::create()
      ->from('ticket t')
      ->where('t.id = ?', $request->getParameter('id'))
      ->limit(1)
      ->fetchOne()
    ;
    $this->forward404Unless($this->ticket);
    return $this->renderPartial('modal', ['ticket' => $this->ticket]);
  }

  /*
    Заявки с установленными датами для размещения на календаре
  */
  public function executeEventSource($request)
  {
    $query = Doctrine_Query::create()
      ->from('Ticket a')
      ->leftJoin('a.Period p')
      ->andWhere('a.planned_start is not null and a.planned_finish is not null')
    ;
    $refs = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);
    foreach($refs as $refer){
      //если заявка периодическая создаем мнимые заявки увеличивая их дату на период
      if ($refer['period_id'] != NULL){
        if ($refer['Period']['length'] > 0 && $refer['isClosed'] != 1){
          $period = $refer['Period']['length'];
          $upper = $period;
          for ($i = 0; $i < 7; $i++){
            $element = array(
              'id'=>$refer['id'],
              'planned_start' => mktime(
                                    date("H", strtotime($refer['planned_start'])),
                                    date("i", strtotime($refer['planned_start'])),
                                    date("s", strtotime($refer['planned_start'])),
                                    date("m", strtotime($refer['planned_start'])),
                                    date("d",strtotime($refer['planned_start'])) + $upper,
                                    date("Y",strtotime($refer['planned_start']))),
              'planned_finish' => mktime(
                                    date("H", strtotime($refer['planned_finish'])),
                                    date("i", strtotime($refer['planned_finish'])),
                                    date("s", strtotime($refer['planned_finish'])),
                                    date("m",strtotime($refer['planned_finish'])),
                                    date("d",strtotime($refer['planned_finish'])) + $upper,
                                    date("Y",strtotime($refer['planned_finish']))),
              'name' => $refer['name'],
              'editable' => false ,
              'ref' => true,
              'repeated' => 'event-repeated',
              'isClosed' => $refer['isClosed'],
              );
            array_push($refs,$element);
             $upper += $period;
          }
        }
      }
    }
    die(json_encode(array_map(function($ref){
      $editeble = true;
      $repeated = ' ';
      if (isset($ref['editable'])){
        $editeble = $ref['editable'];
      }
      else {
        $editeble = !$ref['isClosed'];
      }
      if (isset($ref['repeated'])){
        $repeated = $ref['repeated'];
      }
      return [
        'id' => $ref['id'],
        'title' => $ref['name'],
        'start' => $ref['planned_start'],
        'end' => $ref['planned_finish'],
        'className' => join(' ', ['event-completed-' . ($ref['isClosed'] ? 'yes' : 'no'),$repeated
        ]),
        'editable' => $editeble,
        'allDay' => false,
      ];
    }, (array)$refs), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  }

  /*
    Для периодической заявки.
    Не закрывать заявку, а переносить ее на следующую дату, согласно периоду заявки.
  */
  public function executeTicketDone(sfWebRequest $request){
    $ticket = Doctrine_Query::create()
      ->from('ticket t')
      ->where('t.id = ?', $request->getParameter('id'))
      ->limit(1)
      ->fetchOne()
    ;
    if (($ticket->getPlannedStart()!= Null) &&
        ($ticket->getPlannedFinish()!= Null) &&
        ($ticket->getPeriodId()!==" ") &&
        ($ticket->Period->getLength() > 0)
        )
        {
      $ticket->setPlannedStart(
        date ("Y-m-d H:i:s",
          mktime(
            date("H", strtotime($ticket->getPlannedStart())),
            date("i", strtotime($ticket->getPlannedStart())),
            date("s", strtotime($ticket->getPlannedStart())),
            date("m", strtotime($ticket->getPlannedStart())),
            date("d",strtotime($ticket->getPlannedStart())) + $ticket->Period->getLength(),
            date("Y",strtotime($ticket->getPlannedStart())))
          )
        );
      $ticket->setPlannedFinish(
        date ("Y-m-d H:i:s",
          mktime(
            date("H", strtotime($ticket->getPlannedFinish())),
            date("i", strtotime($ticket->getPlannedFinish())),
            date("s", strtotime($ticket->getPlannedFinish())),
            date("m", strtotime($ticket->getPlannedFinish())),
            date("d",strtotime($ticket->getPlannedFinish())) + $ticket->Period->getLength(),
            date("Y",strtotime($ticket->getPlannedFinish())))
          )
        );
      $ticket->save();
    }
    $this->redirect('@shedule');
  }
  public function executeShedulePrint(sfWebRequest $request){

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
