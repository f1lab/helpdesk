<?php slot('title', 'Works List') ?>

<h1 class="page-header">
  Works List
</h1>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('work/new') ?>" class="btn btn-primary">New</a>
  </div>
</div>

<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
      <th>Id</th>
      <th>Ticket</th>
      <th>Started at</th>
      <th>Finished at</th>
      <th>Description</th>
      <th>Responsible</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Created by</th>
      <th>Updated by</th>
    </tr>
  </thead>
  <tbody><?php foreach ($works as $work): ?>
    <tr>
      <td><a href="<?php echo url_for('work/show?id='.$work->getId()) ?>"><?php echo $work->getId() ?></a></td>
      <td><?php echo $work->getTicketId() ?></td>
      <td><?php echo $work->getStartedAt() ?></td>
      <td><?php echo $work->getFinishedAt() ?></td>
      <td><?php echo $work->getDescription() ?></td>
      <td><?php echo $work->getResponsibleId() ?></td>
      <td><?php echo $work->getCreatedAt() ?></td>
      <td><?php echo $work->getUpdatedAt() ?></td>
      <td><?php echo $work->getCreatedBy() ?></td>
      <td><?php echo $work->getUpdatedBy() ?></td>
    </tr>
  <?php endforeach; ?></tbody>
</table>
