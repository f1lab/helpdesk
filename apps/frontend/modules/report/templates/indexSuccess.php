<?php slot('title', 'Tickets List') ?>

<h1 class="page-header">
  Отчёт
</h1>

<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
      <th>Id</th>
      <th>Category</th>
      <th>Is closed</th>
      <th>Name</th>
      <th>Description</th>
      <th>Attach</th>
      <th>Deadline</th>
      <th>Planned start</th>
      <th>Planned finish</th>
      <th>Period</th>
      <th>Company</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Created by</th>
      <th>Updated by</th>
      <th>Deleted at</th>
    </tr>
  </thead>
  <tbody><?php foreach ($tickets as $ticket): ?>
    <tr>
      <td><a href="<?php echo url_for('report/edit?id='.$ticket->getId()) ?>"><?php echo $ticket->getId() ?></a></td>
      <td><?php echo $ticket->getCategoryId() ?></td>
      <td><?php echo $ticket->getIsClosed() ?></td>
      <td><?php echo $ticket->getName() ?></td>
      <td><?php echo $ticket->getDescription() ?></td>
      <td><?php echo $ticket->getAttach() ?></td>
      <td><?php echo $ticket->getDeadline() ?></td>
      <td><?php echo $ticket->getPlannedStart() ?></td>
      <td><?php echo $ticket->getPlannedFinish() ?></td>
      <td><?php echo $ticket->getPeriodId() ?></td>
      <td><?php echo $ticket->getCompanyId() ?></td>
      <td><?php echo $ticket->getCreatedAt() ?></td>
      <td><?php echo $ticket->getUpdatedAt() ?></td>
      <td><?php echo $ticket->getCreatedBy() ?></td>
      <td><?php echo $ticket->getUpdatedBy() ?></td>
      <td><?php echo $ticket->getDeletedAt() ?></td>
    </tr>
  <?php endforeach; ?></tbody>
</table>
