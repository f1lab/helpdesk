<?php slot('title', 'Tickets List') ?>

<h1 class="page-header">
  Отчёт
</h1>

<?php include_partial('form', ['form' => $form]) ?>

<?php if (count($tickets)): $remotelyClosed = 0; $deadlineForResponsibleOk = 0; $deadlineForApproveOk = 0; $deadlineOk = 0; ?>
  <table class="table table-condensed table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Автор</th>
        <th>Тема</th>
        <th>Категория</th>
        <th>Дата поступления</th>


        <th>Дата назначения ответственного</th>
        <th>Дата принятия в работу</th>
        <th>Дата закрытия</th>
        <th>Кто закрыл</th>
      </tr>
    </thead>
    <tbody><?php foreach ($tickets as $ticket):
      $closer = $ticket->getIsClosed() ? ($ticket->getCloser() ?: null) : null;
      $firstResponsibleRef = $ticket->getFirstResponsibleRef();
      $applier = $ticket->getApplier();

      if ($ticket->getIsClosed() and $ticket->getIsClosedRemotely()) {
        $remotelyClosed += 1;
      }

      if ($ticket->getToCompany()->getDeadlineForSettingResponsible() === 0 or (strtotime($firstResponsibleRef->getCreatedAt()) - strtotime($ticket->getCreatedAt()) < $ticket->getToCompany()->getDeadlineForSettingResponsible())) {
        $deadlineForResponsibleOk += 1;
      }

      if ($ticket->getToCompany()->getDeadlineForApproving() === 0 or (strtotime($applier->getCreatedAt()) - strtotime($ticket->getCreatedAt()) < $ticket->getToCompany()->getDeadlineForApproving())) {
        $deadlineForApproveOk += 1;
      }

      if ($ticket->getDeadline() === null or (strtotime($ticket->getDeadline()) - strtotime($ticket->getCreatedAt()) > 0)) {
        $deadlineOk += 1;
      }
    ?>
      <tr class="<?php //echo (!$ticket->getDeadline() or $worked < $reglamented) ? 'success' : 'error' ?>">
        <td><a href="<?php echo url_for('tickets/show?id='.$ticket->getId()) ?>"><?php echo $ticket->getId() ?></a></td>
        <td>@<?php echo $ticket->getCreator()->getUsername() ?></td>
        <td><?php echo $ticket->getName() ?></td>
        <td><?php echo $ticket->getCategory() ?></td>
        <td><?php echo date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) ?></td>


        <td><?php echo ($firstResponsibleRef && $firstResponsibleRef->getCreatedAt() !== '2015-01-01 00:00:00') ? date('d.m.Y H:i:s', strtotime($firstResponsibleRef->getCreatedAt())) : '—' ?></td>
        <td><?php echo $applier ? date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) : '—' ?></td>
        <td><?php echo $closer ? date('d.m.Y H:i:s', strtotime($closer->getCreatedAt())) : '—' ?></td>
        <td><?php echo $closer ? $closer->getCreator() : '—' ?></td>
      </tr>
    <?php endforeach; ?></tbody>
  </table>

  <div class="alert alert-info">
    <ul class="unstyled">
      <li>Всего заявок за указанный период: <?php $ticketsCount = count($tickets); echo $ticketsCount; ?></li>
      <li>Заявки, закрытые удалённо: <?php echo $remotelyClosed; ?> (<?php echo round($remotelyClosed / $ticketsCount * 100, 2) ?>%)</li>
      <li>Вовремя обработанные заявки: <?php echo $deadlineForResponsibleOk; ?> (<?php echo round($deadlineForResponsibleOk / $ticketsCount * 100, 2) ?>%)</li>

      <li>Вовремя принятые в работу заявки: <?php echo $deadlineForApproveOk; ?> (<?php echo round($deadlineForApproveOk / $ticketsCount * 100, 2) ?>%)</li>
      <li>Закрытые в указанный срок заявки: <?php echo $deadlineOk; ?> (<?php echo round($deadlineOk / $ticketsCount * 100, 2) ?>%)</li>
    </ul>
  </div>

<?php else: ?>
  <div class="alert alert-info">Ничего не найдено.</div>
<?php endif ?>
