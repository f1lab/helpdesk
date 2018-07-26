<?php

/**
 * ticketsApi actions.
 *
 * @package    helpdesk
 * @subpackage ticketsApi
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsApiActions extends sfActions
{
  static protected $filter = null;

  private static function fillFilter($request)
  {
    self::$filter = json_decode(urldecode($request->getParameter('filter', '{}')), true);
  }

  private static function returnJson($data)
  {
    die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  }

  private static function addFilterParametersToQuery($request, $query)
  {
    if (self::$filter === null) {
      self::fillFilter($request);
    }

    if (self::$filter['enabled']) {
      $query
        ->andWhereIn('t.category_id', self::$filter['category_id'])
        ->andWhereIn('t.company_id', self::$filter['company_id'])
        ->andWhereIn('r.id', self::$filter['responsible_id'])
      ;

      if (self::$filter['without_periodicals'] and explode(' ', $query->getDqlPart('from')[0])[0] === 'Ticket') {
        $query->addWhere('t.repeater_id is null');
      }
    }

    $query->addWhere('t.isClosed = ?', self::$filter['enabled'] ? self::$filter['closed'] : false  );

    if (trim(self::$filter['name'])) {
      $query->addWhere('t.name ilike ?', '%' . addcslashes(trim(self::$filter['name']), "%_") . '%');
    }

    if (self::$filter['without_works']) {
        $query->addWhere('t.created_at > ?', (new DateTime('-90 days'))->format('Y-m-d'));
    }

    return $query;
  }

  public function executeGetCounters(sfWebRequest $request)
  {
    $queries = $this->getUser()->getGuardUser()->getPreparedQueriesForTickets();
    $this->fillFilter($request);

    $result = [];
    if (self::$filter['without_responsibles'] || self::$filter['without_appliers'] || self::$filter['without_works']) {
      foreach ($queries as $tab => $query) {
        self::addFilterParametersToQuery($request, $query);
        $tickets = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);

        $counter = 0;
        foreach ($tickets as $ticket) {
          $alreadyCounted = false;

          if (self::$filter['without_responsibles']) {
            if (count($ticket['Responsibles']) === 0) {
              $counter++;
              $alreadyCounted = true;
            }
          }

          if (self::$filter['without_appliers'] and !$alreadyCounted) {
            if (count($ticket['CommentsAgain']) === 0) {
              $counter++;
              $alreadyCounted = true;
            }
          }
            if (self::$filter['without_works'] and !$alreadyCounted) {
                if (isset($ticket['Works']) && count($ticket['Works']) === 0) {
                    $counter++;
                    $alreadyCounted = true;
                }
            }
        }

        $result[ $tab ] = $counter;
      }

    } else {
      $result = array_map(function($query) use ($request) {
        return self::addFilterParametersToQuery($request, $query)->count();
      }, $queries);
    }

    self::returnJson($result);
  }

  public function executeGetTickets(sfWebRequest $request)
  {
    $this->fillFilter($request);
    $tab = self::$filter['tab'];
    $this->forward404Unless($tab);

    $queries = $this->getUser()->getGuardUser()->getPreparedQueriesForTickets();
    $query = $queries[ $tab ];
    $this->forward404Unless($query);

    self::addFilterParametersToQuery($request, $query);

    $tickets = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);

    $result = [];
    if (self::$filter['without_responsibles'] || self::$filter['without_appliers'] || self::$filter['without_works']) {
      foreach ($tickets as $ticket) {
        $alreadyCounted = false;
        if (self::$filter['without_responsibles']) {
          if (count($ticket['Responsibles']) === 0) {
            $result[] = $ticket;
            $alreadyCounted = true;
          }
        }

        if (self::$filter['without_appliers'] and !$alreadyCounted) {
          if (count($ticket['CommentsAgain']) === 0) {
            $result[] = $ticket;
            $alreadyCounted = true;
          }
        }

        if (self::$filter['without_works'] and !$alreadyCounted) {
            if (isset($ticket['Works']) && count($ticket['Works']) === 0) {
                $result[] = $ticket;
                $alreadyCounted = true;
            }
        }
      }

    } else {
      $result = $tickets;
    }

    self::returnJson($result);
  }

  // for shedule: mark as done and replan ticket for next period
  public function executeTicketDone(sfWebRequest $request)
  {
    $ticket = Doctrine_Query::create()
      ->from('Ticket t')
      ->where('t.id = ?', $request->getParameter('id'))
      ->limit(1)
      ->fetchOne()
    ;

    if ($ticket and $ticket->getPlannedStart() and $ticket->getRepeatedEveryDays() > 0) {
      $ticket
        ->planNextStart()
        ->save()
      ;

      $comment = Comment::createFromArray([
        'ticket_id' => $ticket->getId(),
        'text' => 'Выполнил регламентную работу.',
      ]);
      $comment->save();
    }

    $this->redirect('shedule/index');
  }

  public function executeCloseAsDuplicate(sfWebRequest $request)
  {
    $parent = Doctrine_Query::create()
      ->from('Ticket t')
      ->where('t.id = ?', $request->getParameter('parent_id'))
      ->limit(1)
      ->fetchOne()
    ;

    $duplicate = Doctrine_Query::create()
      ->from('Ticket t')
      ->where('t.id = ?', $request->getParameter('id'))
      ->limit(1)
      ->fetchOne()
    ;

    if ($parent and $duplicate) {
      $commentForParent = Comment::createFromArray([
        'ticket_id' => $parent->getId(),
        'text' => 'Заявка #' . $duplicate->getId() . ' помечена дубликатом этой заявки',
        'skip_notification' => true,
      ]);
      $commentForParent->save();

      $duplicate->setIsClosed(true)->save();
      $commentForDuplicate = Comment::createFromArray([
        'ticket_id' => $duplicate->getId(),
        'text' => 'Эта заявка помечена как дубликат заявки #' . $parent->getId() . ' и закрыта',
        'changed_ticket_state_to' => 'closed',
      ]);
      $commentForDuplicate->save();
    }

    $this->redirect($request->getReferer());
  }

  public function executeIAmNotResponsibleForThis(sfWebRequest $request)
  {
    $this->getResponse()->setHeaderOnly(true);

    $this->forward404Unless($this->getUser()->isAuthenticated());

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $this->forward404Unless($json and $data and $data['ticketId'] and $data['reason']);

    $ref = Doctrine_Query::create()
      ->from('RefTicketResponsible ref')
      ->addWhere('ref.ticket_id = ?', $data['ticketId'])
      ->addWhere('ref.user_id = ?', $this->getUser()->getGuardUser()->getId())
      ->limit(1)
      ->fetchOne()
    ;
    if ($ref) {
      $comment = Comment::createFromArray([
        'ticket_id' => $data['ticketId'],
        'text' => 'Пользователь @' . $this->getUser()->getGuardUser()->getUsername() . ' отказался от выполнения заявки по причине: ' . $data['reason'],
      ]);
      $ref->delete();
      $comment->save();
    }

    return sfView::NONE;
  }

  public function executeGetTicketsList(sfWebRequest $request)
  {
    $query = Doctrine_Query::create()
      ->from('Ticket t')
      ->select('t.id, t.name')
      ->addOrderBy('t.id desc')
    ;

    $of = $request->getParameter('of', null);
    if ($of !== null and true == ($ticket = Doctrine_Core::getTable('Ticket')->find($of))) {
      $query
        ->addWhere('t.company_id = ?', $ticket->getCompanyId())
        ->addWhere('t.id != ?', $of)
      ;
    }

    $tickets = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);

    self::returnJson($tickets);
  }
}
