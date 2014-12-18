<div class="page-header">
  <h1>
    <?php echo $company->getName() ?>
    <small><?php echo $company->getDescription() ?></small>

    <div class="pull-right">
    <?php if ($company->getIsExecutor()): ?>
      <span class="label label-success">Исполнитель</span>
    <?php else: ?>
      <span class="label label-success">Клиент</span>
    <?php endif ?>
    </div>
  </h1>
</div>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('@companies-edit?id=' . $company->getId()) ?>" class="btn">
      <i class="icon icon-pencil"></i>
      Редактировать компанию
    </a>
  <?php if ($sf_user->hasCredential('can_delete_companies')): ?>
    <a href="#" data-delete-uri="<?php echo url_for('@companies-delete?id=' . $company->getId()) ?>" class="btn confirm-delete">
      <i class="icon icon-remove"></i>
      Удалить компанию
    </a>
  <?php endif ?>
  </div>
  <div class="btn-group">
    <a href="<?php echo url_for('users/new?company=' . $company->getId()) ?>" class="btn btn-primary">
      <i class="icon-plus icon-white"></i>
      Добавить пользователя
    </a>
  </div>
</div>

<h3>Пользователи</h3>
<?php if ($users->count()): ?>
<table class="table table-striped table-bordered table-condensed rows-clickable">
  <thead>
    <tr>
      <th>№</th>
      <th>Username</th>
      <th>Имя</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $user): ?>
    <tr>
      <td><?php echo $user->getId() ?></td>
      <td>@<a href="<?php echo url_for('/users/edit?id=' . $user->getId()) ?>"><?php echo $user->getUsername() ?></a></td>
      <td><?php echo $user->getName() ?></td>
    </tr>
  <?php endforeach ?>
  </tbody>
</table>
<?php else: ?>
  <div class="alert alert-info">Нет пользователей.</div>
<?php endif ?>

<h3>Кого оповещать</h3>
<?php if ($company->getNotify()->count()): ?>
  <table class="table table-striped table-bordered table-condensed rows-clickable">
    <thead>
      <tr>
      <th>№</th>
      <th>Username</th>
      <th>Имя</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($company->getNotify() as $user): ?>
      <tr>
      <td><?php echo $user->getId() ?></td>
      <td>@<a href="<?php echo url_for('/users/edit?id=' . $user->getId()) ?>"><?php echo $user->getUsername() ?></a></td>
      <td><?php echo $user->getName() ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="alert alert-info">Никого.</div>
<?php endif ?>
