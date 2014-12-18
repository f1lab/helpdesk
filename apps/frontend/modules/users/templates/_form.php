<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script>
  $(function() {
    $('.chzn-select').chosen();
    $('.chzn-select-deselect').chosen({ allow_single_deselect: true });
  });
</script>

<form class="form-horizontal" action="<?php echo url_for('users/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <?php echo $form->renderUsing('bootstrap') ?>

  <div class="form-actions">
    <a class = "btn btn-link" href="<?php echo url_for('users/index') ?>">Все пользователи</a>
    <input class = "btn btn-primary" type="submit" value="Сохранить"/>

    <?php if (!$form->getObject()->isNew()): ?>
      <?php echo link_to('Удалить', 'users/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?', 'class' => 'btn btn-danger pull-right')) ?>
    <?php endif; ?>
  </div>
</form>
