<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="page-header">
  <h1>Добавить компанию</h1>
</div>
<?php echo $form->renderFormTag(url_for('@companies-create'), array('class' => 'well form-fluid')) ?>
  <?php echo $form->renderUsing('bootstrap') ?>
  <div class="form-actions ">
    <button type="submit" class="btn btn-primary">
      <i class="icon-ok icon-white"></i>
      Добавить
    </button>
    <a class="btn" href="<?php echo url_for('@companies') ?>">
      Вернуться
    </a>
  </div>
</form>
