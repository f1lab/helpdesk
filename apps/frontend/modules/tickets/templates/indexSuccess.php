<div class="page-header">
  <h1>Мои заявки</h1>
</div>

<div class="btn-toolbar">
  <div class="btn-group"><?php $state = $sf_context->getRequest()->getParameter('state') ?>
    <a class="btn<?php if ('opened' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-my') ?>">Открытые</a>
    <a class="btn<?php if ('closed' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-my?state=closed') ?>">Закрытые</a>
    <a class="btn<?php if ('all' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-my?state=all') ?>">Все</a>
  </div>
  <div class="btn-group">
    <a href="<?php echo url_for('@tickets-new') ?>" class="btn btn-primary">
      <i class="icon-plus icon-white"></i>
      Добавить заявку
    </a>
  </div>
</div>

<?php
  if (isset($tickets['created_by_me'])):
?>
  <section>
    <h2>Созданы мной</h2>
    <?php include_partial('global/tickets-list', array(
      'tickets' => $tickets['created_by_me'],
      'showDate' => true,
      'showDeadline' => true,
      'showCompanyName' => false,
      'showUserName' => false
    )) ?>
  </section>
<?php
  endif;
  if (isset($tickets['assigned_to_me']) and count($tickets['assigned_to_me'])):
?>
  <section>
    <h2>Я назначен ответственным</h2>
    <?php include_partial('global/tickets-list', array(
      'tickets' => $tickets['assigned_to_me'],
      'showDate' => true,
      'showDeadline' => true,
      'showCompanyName' => true,
      'showUserName' => true,
      'showCategories' => true,
    )) ?>
  </section>
<?php
  endif;
  if (isset($tickets['auto_assigned_to_me']) and count($tickets['auto_assigned_to_me'])):
?>
  <section>
    <h2>От моих компаний</h2>
    <?php include_partial('global/tickets-list', array(
      'tickets' => $tickets['auto_assigned_to_me'],
      'showDate' => true,
      'showDeadline' => true,
      'showCompanyName' => true,
      'showUserName' => true,
      'showCategories' => true,
    )) ?>
  </section>
<?php
  endif;
