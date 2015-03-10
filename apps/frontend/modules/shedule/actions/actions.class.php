<?php

/**
 * shedule actions.
 *
 * @package    helpdesk
 * @subpackage shedule
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sheduleActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->filter = new sfForm();
    $this->filter->getWidgetSchema()
      ->offsetSet('company_id', new sfWidgetFormDoctrineChoice(array(
        'multiple' => true,
        'model' => 'sfGuardGroup',
        'add_empty' => false,
        'query' => Doctrine_Query::create()
          ->from('sfGuardGroup a')
          ->addWhere('a.id in (select group_id from ref_company_responsible where user_id = ?)', $this->getUser()->getGuardUser()->getId())
        ,
      ), array(
        'class' => 'chzn-select',
        'data-placeholder' => 'Выберите…',
      )))
      ->setLabels([
        'company_id' => 'Компания',
      ])
    ;
  }

  // Назначение времени заявке при перетаскивании
  public function executeReplan($request)
  {
    $event = $request->getParameter('event');
    $ticket = Doctrine_Core::getTable('Ticket')->find($event['id']);
    $ticket
      ->setPlannedStart(date('Y-m-d H:i:s', $event['start']))
      ->save()
    ;

    return sfView::NONE;
  }

  // Модальное окно для подробного просмотра заявки в календаре
  public function executeModal($request)
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

  public function executeEventsource($request)
  {
    $query = Doctrine_Query::create()
      ->from('Ticket t')
      ->andWhere('t.isClosed = ?', false)
      ->andWhere('(t.planned_start >= ? and t.planned_start <= ?) or t.repeated_every_days > 0', [
        date('Y-m-d H:i:s', $request->getParameter('start')),
        date('Y-m-d H:i:s', $request->getParameter('end')),
      ])
    ;

    parse_str($request->getParameter("filter"), $filter);
    if (isset($filter['company_id'])) {
      $query->andWhereIn('t.company_id', $filter['company_id']);
    } else {
      $query->addWhere('t.company_id in (select group_id from ref_company_responsible where user_id = ?)', $this->getUser()->getGuardUser()->getId());
    }

    $tickets = $query->execute([], Doctrine_Core::HYDRATE_ARRAY);

    $repeated = [];
    foreach ($tickets as $ticket) {
      $period = $ticket['repeated_every_days'];
      if ($period > 0) {
        $upper = $period;
        for ($i = 0; $i < 7; $i++) {
          $element = array(
            'id'=> $ticket['id'],
            'planned_start' => date('Y-m-d H:i:s', strtotime('+' . $upper . ' day', strtotime($ticket['planned_start']))),
            'name' => $ticket['name'],
            'editable' => false ,
            'ref' => true,
            'repeated' => 'event-repeated',
            'isClosed' => $ticket['isClosed'],
          );

          array_push($repeated, $element);
          $upper += $period;
        }
      }
    }

    $result = array_merge($tickets, $repeated);

    die(json_encode(array_map(function($ticket) {
      $editeble = true;
      $repeated = ' ';
      if (isset($ticket['editable'])){
        $editeble = $ticket['editable'];
      }
      else {
        $editeble = !$ticket['isClosed'];
      }
      if (isset($ticket['repeated'])){
        $repeated = $ticket['repeated'];
      }
      return [
        'id' => $ticket['id'],
        'title' => $ticket['name'],
        'start' => $ticket['planned_start'],
        'end' => date('Y-m-d H:i:s', strtotime('+ 1 hour', strtotime($ticket['planned_start']))),
        'className' => join(' ', ['event-completed-' . ($ticket['isClosed'] ? 'yes' : 'no'),$repeated
        ]),
        'editable' => $editeble,
        'allDay' => false,
      ];
    }, $result), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  }
}
