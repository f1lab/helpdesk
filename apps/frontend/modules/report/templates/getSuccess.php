<?php slot('title', 'Tickets List') ?>

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
        <th>Дата поступления</th>


        <th>Дата назначения ответственного</th>
        <th>Дата принятия в работу</th>
        <th>Дата закрытия</th>
        <th>Кто закрыл</th>
      </tr>
    </thead>
    <tbody><?php foreach ($tickets as $ticket):
      $closer = $ticket->getIsClosed() ? ($ticket->getCloser() ?: null) : null;
      $applier = $ticket->getApplier();
      // $reglamented = $ticket->getDeadline() ? (strtotime($ticket->getDeadline()) - strtotime($ticket->getCreatedAt())) : null;
      $firstResponsibleRef = $ticket->getFirstResponsibleRef();
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

<?php else: ?>
  <div class="alert alert-info">Ничего не найдено.</div>
<?php endif ?>
