<?php slot('title', 'Ticket repeaters List') ?>

<h1 class="page-header">
  Ticket repeaters List
</h1>

<div class="btn-toolbar">
  <div class="btn-group">
    <a href="<?php echo url_for('ticketRepeater/new') ?>" class="btn btn-primary">New</a>
  </div>
</div>

<table class="table table-condensed table-bordered table-hover">
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Company</th>
      <th>Category</th>
      <th>Description</th>
      <th>Initiator</th>
      <th>Repeated every days</th>
      <th>Planned start</th>
      <th>Create before days</th>
      <th>Deadline days</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Created by</th>
      <th>Updated by</th>
      <th>Deleted at</th>
    </tr>
  </thead>
  <tbody><?php foreach ($ticket_repeaters as $ticket_repeater): ?>
    <tr>
      <td><a href="<?php echo url_for('ticketRepeater/edit?id='.$ticket_repeater->getId()) ?>"><?php echo $ticket_repeater->getId() ?></a></td>
      <td><?php echo $ticket_repeater->getName() ?></td>
      <td><?php echo $ticket_repeater->getCompanyId() ?></td>
      <td><?php echo $ticket_repeater->getCategoryId() ?></td>
      <td><?php echo $ticket_repeater->getDescription() ?></td>
      <td><?php echo $ticket_repeater->getInitiatorId() ?></td>
      <td><?php echo $ticket_repeater->getRepeatedEveryDays() ?></td>
      <td><?php echo $ticket_repeater->getPlannedStart() ?></td>
      <td><?php echo $ticket_repeater->getCreateBeforeDays() ?></td>
      <td><?php echo $ticket_repeater->getDeadlineDays() ?></td>
      <td><?php echo $ticket_repeater->getCreatedAt() ?></td>
      <td><?php echo $ticket_repeater->getUpdatedAt() ?></td>
      <td><?php echo $ticket_repeater->getCreatedBy() ?></td>
      <td><?php echo $ticket_repeater->getUpdatedBy() ?></td>
      <td><?php echo $ticket_repeater->getDeletedAt() ?></td>
    </tr>
  <?php endforeach; ?></tbody>
</table>
