<div class="row-fluid">
  <div class="">
    <div class="alert alert-info">
      <strong>Не паниковать!</strong><br />
      Если у вас нет аккаунта или вы потеряли пароль, то, пожалуйста, <a href="http://f1lab.ru/contacts/" target="_blank">свяжитесь с нами</a>.
    </div>

    <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" class="form-horizontal">
    <?php echo $form->renderUsing('bootstrap') ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary btn-large" value="Войти" />
    </div>
  </form>
  </div>
</div>