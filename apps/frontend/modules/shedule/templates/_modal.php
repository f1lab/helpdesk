<div class="modal-header">
  <h4>Заявка №<?php echo $ticket->getId() ?> от компании «<?php echo $ticket->getCompany() ? $ticket->getCompany()->getName() : 'Без компании' ?>»</h4>
</div>

<div class="modal-body">
  <h2><?php echo $ticket->getName() ?></h2>

  <div class="well"><?php echo $ticket->getRawValue()->getDescription() ?></div>

  <div style="margin-top: 1em;">
    <?php if (true == ($responsibles = $ticket->getResponsibles()) and count($responsibles)): ?>
        Ответственные:
        <ul style="text-align:left"><?php foreach ($responsibles as $user): ?>
          <li><a href="#"><?php echo $user ?></a></li>
        <?php endforeach ?></ul>
    <?php else: ?>
      Ответственные не назначены.
    <?php endif ?>
  </div>
</div>

<div class="modal-footer">
  <a class = "btn btn-default" href="<?php echo url_for('tickets/show?id=' . $ticket->getId()) ?>" target="_blank">Ещё подробнее</a>
  <a class = "btn btn-success" href="<?php echo url_for('ticketsApi/ticketDone?id=' . $ticket->getId()) ?>">Выполнена</a>
</div>
