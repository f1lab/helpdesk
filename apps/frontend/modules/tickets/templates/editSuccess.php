<div class="page-header">
  <h1>Редактировать заявку</h1>
</div>

<form action="<?php echo url_for('@tickets-update?id=' . $form->getObject()->getId()) ?>" method="post" enctype="multipart/form-data" class="well form-fluid">
  <?php echo $form->renderUsing('bootstrap') ?>
  <div class="form-actions ">
    <button type="submit" class="btn btn-primary">
      <i class="icon-ok icon-white"></i>
      Сохранить
    </button>
    <a class="btn" href="<?php echo url_for('tickets/v2') ?>">
      Вернуться
    </a>
  </div>
</form>
