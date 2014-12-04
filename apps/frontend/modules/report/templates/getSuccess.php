<?php slot('title', 'Tickets List') ?>

<?php function formatTime($seconds) {
  $hours = floor($seconds / 3600);
  $mins = floor(($seconds - ($hours*3600)) / 60);
  $secs = floor($seconds % 60);

  return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
} ?>

<h1 class="page-header">
  Отчёт
</h1>

<?php include_partial('form', ['form' => $form]) ?>

<?php if (count($tickets)): ?>
  <table class="table table-condensed table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Автор</th>
        <th>Тема</th>
        <th>Категория</th>
        <th>Время создания</th>
        <th>Deadline</th>
        <th>Закрыта?</th>

        <th>Общее время выполнения</th>

        <th>Регламентированные сроки</th>

      </tr>
    </thead>
    <tbody><?php foreach ($tickets as $ticket):
      $closer = $ticket->getIsClosed() ? ($ticket->getCloser() ?: null) : null;
      $applier = $ticket->getApplier();
      $worked = strtotime($closer ? $closer->getCreatedAt() : date('d.m.Y H:i:s')) - strtotime($applier ? $applier->getCreatedAt() : $ticket->getCreatedAt());
      $reglamented = $ticket->getDeadline() ? (strtotime($ticket->getDeadline()) - strtotime($ticket->getCreatedAt())) : null;
    ?>
      <tr class="<?php echo (!$ticket->getDeadline() or $worked < $reglamented) ? 'success' : 'error' ?>">
        <td><a href="<?php echo url_for('tickets/show?id='.$ticket->getId()) ?>"><?php echo $ticket->getId() ?></a></td>
        <td>@<?php echo $ticket->getCreator()->getUsername() ?></td>
        <td><?php echo $ticket->getName() ?></td>
        <td><?php echo $ticket->getCategory() ?></td>
        <td><?php echo date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) ?></td>
        <td><?php echo $ticket->getDeadline() ? date('d.m.Y H:i:s', strtotime($ticket->getDeadline())) : '—' ?></td>
        <td><?php if ($ticket->getIsClosed()): ?>
          Заявку закрыл @<?php echo $closer ? $closer->getCreator()->getUsername() : 'неизвестно кто' ?>
          <?php echo $closer ? date('d.m.Y H:i:s', strtotime($closer->getCreatedAt())) : 'неизвестно когда' ?>
        <?php else: ?>
          не закрыта
        <?php endif ?></td>

        <td>
          <?php echo formatTime($worked) ?>
        </td>

        <td><?php if ($reglamented): ?>
          <?php echo formatTime($reglamented) ?>
        <?php else: ?>
          —
        <?php endif ?></td>

      </tr>
    <?php endforeach; ?></tbody>
  </table>

<?php else: ?>
  <div class="alert alert-info">Ничего не найдено.</div>
<?php endif ?>
