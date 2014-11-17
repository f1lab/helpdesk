<div class="page-header">
  <h1>Компании</h1>
</div>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('@companies-new') ?>" class="btn btn-primary">
      <i class="icon-plus icon-white"></i>
      Добавить компанию
    </a>
  </div>
</div>

<table class="table table-striped table-bordered table-condensed rows-clickable">
  <thead>
    <tr>
      <th>№</th>
      <th>Alias</th>
      <th>Наименование</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($companies as $company): ?>
    <tr>
      <td><?php echo $company->getId() ?></td>
      <td>#<a href="<?php echo url_for('@companies-show?id=' . $company->getId()) ?>"><?php echo $company->getName() ?></a></td>
      <td><?php echo $company->getDescription() ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>