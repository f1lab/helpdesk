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
      ->from('TicketRepeater t')
      ->addWhere('t.isClosed = ?', false)
      ->addWhere('t.planned_start <= ?', date('Y-m-d H:i:s'))
      ->leftJoin('t.Responsibles r')
      ->leftJoin('t.Observers o')
      ->execute()
    ;

    $this->logSection('repeater', sprintf('Found %d repeaters', count($repeaters)));
    foreach ($repeaters as $repeater) {
      $this->logSection('repeater', sprintf('Processing #%d', $repeater->getId()));

      $plannedStart = $repeater->getPlannedStart();
      $createBeforeDays = $repeater->getCreateBeforeDays();
      $deadlineDays = $repeater->getDeadlineDays();

      // FIXME: check if it's time to create new ticket
      if (true) {
        $this->logSection('repeater', sprintf('Creating task for #%d', $repeater->getId()));
        $nextStartDate = time();

        $createBeforeTime = strtotime('-' . $createBeforeDays . ' days', $nextStartDate);
        $plannedStartTime = $nextStartDate;
        $deadlineTime = strtotime('+' . $deadlineDays . ' days', $nextStartDate);

        $ticket = Ticket::createFromArray([
          'name' => $repeater->getName(),
          'company_id' => $repeater->getCompanyId(),
          'category_id' => $repeater->getCategoryId(),
          'description' => $repeater->getDescription(),
          'planned_start' => date('Y-m-d H:i:s', $plannedStartTime),
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

        $this->logSection('repeater', sprintf('Created task #%d for Repeater #%d', $ticket->getId(), $repeater->getId()));
      }
    }
  }
}
