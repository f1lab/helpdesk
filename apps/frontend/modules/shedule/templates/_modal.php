<div class="clearfix">
  <h4 class="pull-right">
    <span class="alert alert-success">Заявка №<?php echo $ticket->getId() ?></span>
  </h4>
  <h3>Тема: <?php echo $ticket->getName() ?></h3>

</div>
<div class="row-fluid">
<h4>Описание:</h4><h5><?php echo $ticket->getDescription() ?></h5>
  <a class = "btn btn-default" href="<?php echo url_for('tickets/show?id=' . $ticket->getId()) ?>" target="_blank">Подробнее</a>
  <a class = "btn btn-success" href="<?php echo url_for('ticketsApi/ticketDone?id=' . $ticket->getId()) ?>">Выполнена</a>
</div>
