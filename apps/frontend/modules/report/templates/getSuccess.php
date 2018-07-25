<?php slot('title', 'Tickets List') ?>

<h1 class="page-header">
  Отчёт по заявкам
</h1>

<?php include_partial('form', ['form' => $form]) ?>

<?php $rowWithHeaders = '
  <tr>
    <th>#</th>
    <th>Автор</th>
    <th>Тема</th>
    <th>Категория</th>
    <th>Дата поступления</th>

    <th>Дата назначения ответственного</th>
    <th>Кто ответственный</th>

    <th>Дата принятия в работу</th>
    <th>Кто принял в работу</th>

    <th>Дата закрытия</th>
    <th>Deadline</th>
    <th>Кто закрыл</th>

    <th>Закрыта удалённо?</th>
    <th>Обработана в срок?</th>
    <th>Принята в срок?</th>
    <th>Закрыта в срок?</th>
  </tr>
'; ?>

<?php if (count($tickets)): ?>
  <?php
    // counters for summary
    $remotelyClosed = 0; $deadlineForResponsibleOk = 0; $deadlineForApproveOk = 0; $deadlineOk = 0;

    // deadline settings
    $settingResponsibleDeadline = (int)$tickets->getFirst()->getCompany()->getDeadlineForSettingResponsible();
    $approvingDeadline = (int)$tickets->getFirst()->getCompany()->getDeadlineForApproving();
  ?>
  <div class="alert alert-info">
    <h4>Регламентированные сроки</h4>
    <p>Указание ответственного: <?php echo $settingResponsibleDeadline === 0 ? 'не ограничено' : Helpdesk::formatDurationDigital($settingResponsibleDeadline); ?> <br>
    Принятие в работу: <?php echo $approvingDeadline === 0 ? 'не ограничено' : Helpdesk::formatDurationDigital($approvingDeadline); ?></p>

    <p>Продолжительность времени выводится в формате месяцы:дни:часы:минуты:секунды</p>
  </div>

  <table class="table table-condensed table-bordered table-hover tablesorter">
    <thead>
      <?php echo $rowWithHeaders; ?>
    </thead>
    <tbody><?php $i = 0; foreach ($tickets as $ticket):
      $closer = $ticket->getIsClosed() ? ($ticket->getCloser() ?: null) : null;
      $firstResponsibleRef = $ticket->getFirstResponsibleRef();
      $applier = $ticket->getApplier();

      $isRemotelyClosed = $isDeadlineForResponsibleOk = $isDeadlineForApproveOk = $isDeadlineOk = false;
      $approvingTime = $settingResponsibleTime = null;

      if ($ticket->getIsClosed() and $ticket->getIsClosedRemotely()) {
        $isRemotelyClosed = true;
        $remotelyClosed += 1;
      }

      if (
        $settingResponsibleDeadline == 0
        or !$firstResponsibleRef
        or (($settingResponsibleTime = strtotime($firstResponsibleRef->getCreatedAt()) - strtotime($ticket->getCreatedAt())) < $settingResponsibleDeadline)
      ) {
        $isDeadlineForResponsibleOk = true;
        $deadlineForResponsibleOk += 1;
      }

      if (
        $approvingDeadline == 0
        or !$applier
        or (($approvingTime = strtotime($applier->getCreatedAt()) - strtotime($ticket->getCreatedAt())) < $approvingDeadline)
      ) {
        $isDeadlineForApproveOk = true;
        $deadlineForApproveOk += 1;
      }

      $deadlineTime = $closer ? (strtotime($closer->getCreatedAt()) - strtotime($ticket->getCreatedAt())) : false;
      if (
        $ticket->getDeadline() === null
        or !$closer
        or (strtotime($ticket->getDeadline()) - strtotime($closer->getCreatedAt()) > 0)
      ) {
        $isDeadlineOk = true;
        $deadlineOk += 1;
      }
    ?>
      <?php if ($i > 0 and $form->getValue('headers_drawer') > 0 and $i % $form->getValue('headers_drawer') === 0): ?>
        <?php echo $rowWithHeaders; ?>
      <?php endif ?>
      <tr class="<?php echo (!$isDeadlineForResponsibleOk or !$isDeadlineForApproveOk or !$isDeadlineOk) ? 'error' : '' ?>">
        <td><a href="<?php echo url_for('tickets/show?id='.$ticket->getId()) ?>"><?php echo $ticket->getId() ?></a></td>
        <td>@<?php echo $ticket->getCreator()->getUsername() ?></td>
        <td><?php echo $ticket->getName() ?></td>
        <td><?php echo $ticket->getCategory() ?></td>
        <td><?php echo date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) ?></td>

        <td><?php echo ($firstResponsibleRef && $firstResponsibleRef->getCreatedAt() !== '2015-01-01 00:00:00') ? date('d.m.Y H:i:s', strtotime($firstResponsibleRef->getCreatedAt())) : '—' ?></td>
        <td>
          <?php $refs = $ticket->getRefTicketResponsible(); ?>
          <?php if (count($refs) > 1): ?>
            <ul><?php foreach ($refs as $ref): ?>
              <li><?php echo $ref->getUser() ?>, назначен <?php echo $ref->getCreator() ?></li>
            <?php endforeach ?></ul>
          <?php elseif (count($refs) === 1): $ref = $refs->getFirst(); ?>
            <?php echo $ref->getUser() ?>, назначен <?php echo $ref->getCreator() ?>
          <?php else: ?>
            —
          <?php endif ?>
        </td>

        <td><?php echo $applier ? date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) : '—' ?></td>
        <td><?php echo $applier ? $applier->getCreator() : '—' ?></td>

        <td><?php echo $closer ? date('d.m.Y H:i:s', strtotime($closer->getCreatedAt())) : '—' ?></td>
        <td><?php echo $ticket->getDeadline() ? date('d.m.Y H:i:s', strtotime($ticket->getDeadline())) : '—' ?></td>
        <td><?php echo $closer ? $closer->getCreator() : '—' ?></td>

        <td><span class="icon icon-<?php echo $isRemotelyClosed ? 'ok' : 'remove' ?>"></span> <span class="tablesorter-value hidden"><?php echo $isRemotelyClosed ? 'да' : 'нет' ?></span></td>
        <td>
          <span class="icon icon-<?php echo $isDeadlineForResponsibleOk ? 'ok' : 'remove' ?>"></span>
          <?php echo isset($settingResponsibleTime) ? Helpdesk::formatDurationDigital($settingResponsibleTime) : ''; ?>
          <span class="tablesorter-value hidden"><?php echo $settingResponsibleTime ?></span>
        </td>
        <td>
          <span class="icon icon-<?php echo $isDeadlineForApproveOk ? 'ok' : 'remove' ?>"></span>
          <?php echo isset($approvingTime) ? Helpdesk::formatDurationDigital($approvingTime) : ''; ?>
          <span class="tablesorter-value hidden"><?php echo $approvingTime ?></span>
        </td>
        <td>
          <span class="icon icon-<?php echo $isDeadlineOk ? 'ok' : 'remove' ?>"></span>
          <?php echo Helpdesk::formatDurationDigital($deadlineTime); ?>
          <span class="tablesorter-value hidden"><?php echo $deadlineTime ?></span>
        </td>
      </tr>
    <?php $i++; endforeach; ?></tbody>
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
