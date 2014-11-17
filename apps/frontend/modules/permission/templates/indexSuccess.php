<?php slot('title', 'Sf guard permissions List') ?>

<h1 class="page-header">
  Sf guard permissions List
</h1>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('permission/new') ?>" class="btn btn-primary">New</a>
  </div>
</div>

<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Description</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody><?php foreach ($sf_guard_permissions as $sf_guard_permission): ?>
    <tr>
      <td><a href="<?php echo url_for('permission/edit?id='.$sf_guard_permission->getId()) ?>"><?php echo $sf_guard_permission->getId() ?></a></td>
      <td><?php echo $sf_guard_permission->getName() ?></td>
      <td><?php echo $sf_guard_permission->getDescription() ?></td>
      <td><?php echo $sf_guard_permission->getCreatedAt() ?></td>
      <td><?php echo $sf_guard_permission->getUpdatedAt() ?></td>
    </tr>
  <?php endforeach; ?></tbody>
</table>
