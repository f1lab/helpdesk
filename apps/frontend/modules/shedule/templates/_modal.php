<div class="modal-header">
  <h4>Заявка №<?php echo $ticket->getId() ?> от компании «<?php echo $ticket->getToCompany() ? $ticket->getToCompany()->getName() : 'Без компании' ?>»</h4>
</div>

<div class="modal-body">
  <h2><?php echo $ticket->getName() ?></h2>

  <div class="well"><?php echo $ticket->getRawValue()->getDescription() ?></div>
</div>

<div class="modal-footer">
  <a class = "btn btn-default" href="<?php echo url_for('tickets/show?id=' . $ticket->getId()) ?>" target="_blank">Ещё подробнее</a>
  <a class = "btn btn-success" href="<?php echo url_for('ticketsApi/ticketDone?id=' . $ticket->getId()) ?>">Выполнена</a>
</div>
