<?php
/**
 * @var sfContext $sf_context
 */
?><!DOCTYPE html>
<html lang="ru-RU">
<head>
  <meta charset="UTF-8">
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <title><?php echo get_slot('title') ?></title>

  <?php include_stylesheets() ?>

  <?php include_javascripts() ?>

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <link rel="shortcut icon" href="/favicon.ico" />
</head>

<body>
  <script src="/js/notifications.js"></script>
  <script type="text/coffeescript">
    window.API =
      getCounters: '<?php echo url_for('ticketsApi/getCounters') ?>'
      getTickets: '<?php echo url_for('ticketsApi/getTickets') ?>'
      iAmNotResponsibleForThis: '<?php echo url_for('ticketsApi/iAmNotResponsibleForThis') ?>'
      getTicketsList: '<?php echo url_for('ticketsApi/getTicketsList') ?>'
      closeAsDuplicate: '<?php echo url_for('ticketsApi/closeAsDuplicate') ?>'
  </script>

  <div class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand" href="<?php echo url_for('@homepage') ?>">F1 Lab → Helpdesk</a>

        <div class="nav-collapse"><?php if ($sf_user->isAuthenticated()) : ?>
          <ul class="nav"><?php $currentRoute = $sf_context->getRouting()->getCurrentRouteName() ?>
            <li <?php if ('homepage' == $currentRoute or 'tickets-my' == $currentRoute): ?> class="active"<?php endif ?>>
              <a href="<?php echo url_for('@homepage') ?>">Мои заявки</a>
            </li>

            <?php if ($sf_user->hasCredential('can_use_schedule')): ?>
              <li <?php  if ('shedule' == $sf_context->getModuleName()):  ?> class="active"<?php endif ?>>
                <a href="<?php echo url_for('shedule/index') ?>">Расписание</a>
              </li>
            <?php endif ?>

            <?php if ($sf_user->hasCredential('can create categories for tickets')): ?>
              <li <?php if ('category' == $sf_context->getModuleName()): ?> class="active"<?php endif ?>>
                <a href="<?php echo url_for('category/index') ?>">Категории заявок</a>
              </li>
            <?php endif ?>

            <?php if ($sf_user->hasCredential('can_edit_companies_and_users')): ?>
              <li <?php if ('companies' == $currentRoute or 'companies-show' == $currentRoute): ?> class="active"<?php endif ?>>
                <a href="<?php echo url_for('@companies') ?>">Компании и пользователи</a>
              </li>
            <?php endif ?>

            <?php if ($sf_user->hasCredential('can view report')): ?>
              <li <?php if ('report' == $sf_context->getModuleName() && 'works' !== $sf_context->getActionName()): ?> class="active"<?php endif ?>>
                <a href="<?php echo url_for('report/index') ?>">Отчёт по заявкам</a>
              </li>
            <?php endif ?>

            <?php if ($sf_user->hasCredential('can view report')): ?>
              <li <?php if ('report' == $sf_context->getModuleName() && 'works' === $sf_context->getActionName()): ?> class="active"<?php endif ?>>
                <a href="<?php echo url_for('report/works') ?>">Отчёт по работам</a>
              </li>
            <?php endif ?>
          </ul>

          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" href="#" data-toggle="dropdown">
              <i class="icon-user"></i>
              <?php echo $sf_user->getUsername() ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu fluid">
              <li><a href="<?php echo url_for('dashboard/settings') ?>">Настройки</a></li>
              <li><a href="<?php echo url_for('guard/logout') ?>">Выйти</a></li>
            </ul>
          </div>

          <form action="<?php echo url_for('tickets/show'); ?>" method="get" class="navbar-search pull-right">
            <input type="number" min="1" name="id" required class="span2 search-query" placeholder="Номер заявки + &#9166;">
          </form>
        <?php endif ?></div>
      </div>
    </div>
  </div>

  <div class="container" style = "width: auto;">
    <?php if ($sf_user->hasFlash('message') and list($type, $title, $content)=$sf_user->getFlash('message')): ?>
    <div class="alert alert-<?php echo $type ?>">
      <a href="#" class="close" data-dismiss="alert">×</a>
      <strong><?php echo $title ?></strong>
      <?php echo $content ?>
    </div>
    <?php endif ?>

    <?php echo $sf_content ?>
  </div>

  <script type="text/coffeescript" src="/js/angular-RepeatedEveryDays.coffee"></script>
</body>
</html>
