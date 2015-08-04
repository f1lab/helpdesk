<div class="page-header">
  <h1>Добавить регламентную работу</h1>
</div>

<form action="<?php echo url_for('ticketRepeater/create') ?>" method="post" enctype="multipart/form-data" class="well form-fluid">
  <?php echo $form->renderUsing('bootstrap') ?>
  <div class="form-actions ">
    <button type="submit" class="btn btn-primary">
      <i class="icon-ok icon-white"></i>
      Добавить
    </button>
    <a class="btn" href="<?php echo url_for('tickets/v2') ?>">
      Вернуться
    </a>
  </div>
</form>
