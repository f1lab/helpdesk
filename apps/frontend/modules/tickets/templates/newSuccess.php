<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script>
  $(function() {
    $('.chzn-select').chosen();
    $('.chzn-select-deselect').chosen({ allow_single_deselect: true });
  });
</script>
<div class="page-header">
  <h1>Добавить заявку</h1>
</div>

<?php echo $form->renderFormTag(url_for('@tickets-create'), array('class' => 'well form-fluid')) ?>
  <?php //echo $form->renderUsing('bootstrap') ?>
  <?php echo $form['name']->renderRow()?>
  <?php echo $form['category_id']->renderRow()?>
  <?php echo $form['description']->renderRow()?>
  <?php echo $form['attach']->renderRow()?>
  <?php echo $form['deadline']->renderRow()?>
  <?php if ($sf_user->hasCredential('can_set_responsibles_for_tickets	'))
    echo $form['company_id']->renderRow();
  ?>
  <?php if ($sf_user->hasCredential('can_use_schedule')):?>
  <div class="btn-group ">
      <a class="btn toggler collapsed" data-toggle="collapse" href="#filterator">
        <i class="icon icon-list"></i> На расписание
      </a>
  </div></br>
  <div id="filterator" class="collapse">
    <?php echo $form['period_id']->renderRow()?>
  </div>
  <?php endif;?>
  <?php if (isset($form['responsibles_list'])){
    echo $form['responsibles_list']->renderRow();
  }
  ?>
  <?php echo $form->renderHiddenFields()?>
 <div class="form-actions ">
    <button type="submit" class="btn btn-primary">
      <i class="icon-ok icon-white"></i>
      Добавить
    </button>
    <a class="btn" href="<?php echo url_for('@tickets-my') ?>">
      Вернуться
    </a>
  </div>
</form>
