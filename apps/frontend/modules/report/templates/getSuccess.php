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
        <th>Время создания</th>
        <th>Закрыта?</th>

        <th>Deadline</th>
      </tr>
    </thead>
    <tbody><?php foreach ($tickets as $ticket): ?>
      <tr>
        <td><a href="<?php echo url_for('tickets/show?id='.$ticket->getId()) ?>"><?php echo $ticket->getId() ?></a></td>
        <td>@<?php echo $ticket->getCreator()->getUsername() ?></td>
        <td><?php echo $ticket->getName() ?></td>
        <td><?php echo $ticket->getCategory() ?></td>
        <td><?php echo $ticket->getCreatedAt() ?></td>
        <td><?php if ($ticket->getIsClosed()): $closingComment = $ticket->getClosingComments()->getFirst(); ?>
          Заявку закрыл @<?php echo $closingComment->getCreator()->getUsername() ?>
          <?php echo date('d.m.Y H:i:s', strtotime($closingComment->getCreatedAt())) ?>
        <?php else: ?>
          не закрыта
        <?php endif ?></td>

        <td><?php echo $ticket->getDeadline() ?></td>
      </tr>
    <?php endforeach; ?></tbody>
  </table>

<?php else: ?>
  <div class="alert alert-info">Ничего не найдено.</div>
<?php endif ?>
