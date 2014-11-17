<div class="page-header">
  <h1>Заявки компании</h1>
</div>

<div class="btn-toolbar">
  <div class="btn-group"><?php $state = $sf_context->getRequest()->getParameter('state') ?>
    <a class="btn<?php if ('opened' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-company') ?>">Открытые</a>
    <a class="btn<?php if ('closed' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-company?state=closed') ?>">Закрытые</a>
    <a class="btn<?php if ('all' == $state): ?> active<?php endif ?>" href="<?php echo url_for('@tickets-company?state=all') ?>">Все</a>
  </div>
  <!--<div class="btn-group">
    <a href="<?php echo url_for('@tickets-new') ?>" class="btn btn-primary">
      <i class="icon-plus icon-white"></i>
      Добавить заявку
    </a>
  </div>-->
</div>

<?php include_partial('global/tickets-list', array('tickets' => $tickets, 'showDate' => true, 'showCompanyName' => false, 'showUserName' => true, 'showDeadline' => false)) ?>