<?php slot('title', 'Show Work') ?>

<h1 class="page-header">
  Show Work
</h1>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('work/edit?id='.$work->getId()) ?>" class="btn btn-primary">Edit</a>
  </div>
  <div class="btn-group">
    <a href="<?php echo url_for('work/index') ?>" class="btn">Back to List</a>
  </div>
</div>

<table class="table table-condensed table-bordered">
  <tbody>
    <tr>
      <th scope="row" class="span3">Id:</th>
      <td><?php echo $work->getId() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Ticket:</th>
      <td><?php echo $work->getTicketId() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Started at:</th>
      <td><?php echo $work->getStartedAt() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Finished at:</th>
      <td><?php echo $work->getFinishedAt() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Description:</th>
      <td><?php echo $work->getDescription() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Responsible:</th>
      <td><?php echo $work->getResponsibleId() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Created at:</th>
      <td><?php echo $work->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Updated at:</th>
      <td><?php echo $work->getUpdatedAt() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Created by:</th>
      <td><?php echo $work->getCreatedBy() ?></td>
    </tr>
    <tr>
      <th scope="row" class="span3">Updated by:</th>
      <td><?php echo $work->getUpdatedBy() ?></td>
    </tr>
  </tbody>
</table>
