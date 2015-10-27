<?php

class ticketRepeaterTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'ticket';
    $this->name             = 'repeater';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ticket:repeater|INFO] task does things.
Call it with:

  [php symfony ticket:repeater|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $this->logSection('repeater', 'Getting repeaters');
    $repeaters = Doctrine_Query::create()
      ->from('TicketRepeater r')
      ->addWhere('r.deleted_at is null')
      ->addWhere('r.isClosed = ?', false)
      ->addWhere('r.next_start <= ?', date('Y-m-d H:i:s'))
      ->leftJoin('r.Responsibles')
      ->leftJoin('r.Observers')
      ->execute()
    ;
    $this->logSection('repeater', sprintf('Found %d possibly not resheduled repeaters', count($repeaters)));

    foreach ($repeaters as $repeater) {
      $this->logSection('repeater', sprintf('Processing repeater #%d', $repeater->getId()));

      $ticketNextPlannedStartTime = strtotime($repeater->getNextStart());
      $createBeforeDays = $repeater->getCreateBeforeDays();

      $isTicketAlreadyCreated = Doctrine_Query::create()
        ->from('Ticket t')
        ->addWhere('t.repeater_id = ?', $repeater->getId())
        ->addWhere('t.planned_start = ?', $repeater->getNextStart())
        ->count() === 1
      ;
      $createBeforeDaysFulfilled = strtotime('-' . $createBeforeDays . ' days', $ticketNextPlannedStartTime) <= time();

      if (!$isTicketAlreadyCreated and $createBeforeDaysFulfilled) {
        $this->logSection('repeater', sprintf('Creating ticket for #%d', $repeater->getId()));

        $deadlineDays = $repeater->getDeadlineDays();
        $deadlineTime = strtotime('+' . $deadlineDays . ' days', $ticketNextPlannedStartTime);

        $ticket = Ticket::createFromArray([
          'name' => $repeater->getName(),
          'company_id' => $repeater->getCompanyId(),
          'category_id' => $repeater->getCategoryId(),
          'description' => $repeater->getDescription(),
          'planned_start' => date('Y-m-d H:i:s', $ticketNextPlannedStartTime),
          'deadline' => date('Y-m-d H:i:s', $deadlineTime),
          'repeater_id' => $repeater->getId(),
          'created_by' => $repeater->getInitiatorId(),
        ]);
        $ticket->save();

        $ticket->link('Responsibles', array_map(function($user) {
          return $user['id'];
        }, $repeater->getResponsibles()->toArray()));
        $ticket->link('Observers', array_map(function($user) {
          return $user['id'];
        }, $repeater->getObservers()->toArray()));
        $ticket->save();

        $this->logSection('repeater', sprintf('Created ticket #%d for repeater #%d', $ticket->getId(), $repeater->getId()));

        $comment = Comment::createFromArray([
          'skip_notification' => true,
          'ticket_id' => $ticket->getId(),
          'text' => 'Заявка относится к регламентной работе ##' . $repeater->getId(),
          'created_by' => $repeater->getInitiatorId(),
        ]);
        $comment->save();

        $nextStartTime = strtotime('+' . $repeater->getRepeatedEveryDays() . ' days', strtotime($repeater->getNextStart()));
        $repeater->setNextStart(date('Y-m-d H:i:s', $nextStartTime));
        $repeater->save();
        $this->logSection('repeater', sprintf('Sheduled repeater #%d to %s', $repeater->getId(), $repeater->getNextStart()));
      }
    }
  }
}
