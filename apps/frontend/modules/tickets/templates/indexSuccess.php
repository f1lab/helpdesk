<div class="page-header">
  <h1>Мои заявки</h1>
</div>

<div class="alert alert-block alert-info">
  <h4>Попробуйте «<a href="<?php echo url_for('tickets/v2') ?>">Мои заявки 2.0</a>»</h4>
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

<section>
  <h2>Созданы мной</h2>
  <?php include_partial('global/tickets-list', array(
    'tickets' => $tickets['created-by-me'],
    'showDate' => true,
    'showDeadline' => true,
    'showCompanyName' => false,
    'showUserName' => false
  )) ?>
</section>

<section>
  <h2>Я назначен ответственным</h2>
  <?php include_partial('global/tickets-list', array(
    'tickets' => $tickets['assigned-to-me'],
    'showDate' => true,
    'showDeadline' => true,
    'showCompanyName' => true,
    'showUserName' => true,
    'showCategories' => true,
  )) ?>
</section>

<section>
  <h2>Я назначен наблюдателем</h2>
  <?php include_partial('global/tickets-list', array(
    'tickets' => $tickets['observed-by-me'],
    'showDate' => true,
    'showDeadline' => true,
    'showCompanyName' => true,
    'showUserName' => true,
    'showCategories' => true,
  )) ?>
</section>

<section>
  <h2>От моих компаний</h2>
  <?php include_partial('global/tickets-list', array(
    'tickets' => $tickets['auto-assigned-to-me'],
    'showDate' => true,
    'showDeadline' => true,
    'showCompanyName' => true,
    'showUserName' => true,
    'showCategories' => true,
  )) ?>
</section>
