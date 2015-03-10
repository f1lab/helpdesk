<div class="page-header">
  <h1>Добавить заявку</h1>
</div>

<form action="<?php echo url_for('@tickets-create') ?>" method="post" enctype="multipart/form-data" class="well form-fluid">
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

<script type="text/coffeescript" src="/js/angular-RepeatedEveryDays.coffee" />
