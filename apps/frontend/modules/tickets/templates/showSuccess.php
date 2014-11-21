<?php
  use_helper('Date');

  // редактировать, закрывать и переокрывать тикет может его создатель, обладатель прав или it-admin
  $canManipulateThisTicket = $ticket->getCreatedBy() === $sf_user->getGuardUser()->getId()
    || $sf_user->hasCredential('can_edit_tickets')
    || $sf_user->getGuardUser()->getType() === 'it-admin'
  ;
?>

<div class="row-fluid">

<div class="span10">
  <div class="well ticket">
    <div class="header">
      <span class="who"><a href="#"><?php echo $ticket->getCreator()->getUsername() ?></a></span>
      <span class="what">opened ticket</span>
      <span class="when"><?php echo $ticket->getCreatedAt() ?></span>
      <span class="muted pull-right">
        <?php echo $ticket->getCreator()->getFullName() ?>
        <?php echo $ticket->getCreator()->getEmailAddress() ?>
        <?php echo $ticket->getCreator()->getPhone() ?>
      </span>
    </div>
    <?php
      if (true == ($dl = $ticket->getDeadline())):
        $dl = str_replace('00:00:00', '', $dl);
        if (substr($dl, -3) == ':00'):
          $dl = substr($dl, 0, -3);
        endif;
    ?>
      <p><span class="label label-warning">deadline: <?php echo $dl ?></span></p>
    <?php endif ?>
    <div class="content">
      <h1 class="page-header">
        <?php echo $ticket->getName() ?>

        <small><?php $applier = $ticket->getApplier(); ?>
          <?php if ($applier): ?>
            в работе с <?php echo date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) ?>
          <?php elseif (!$ticket->getIsClosed() and $sf_user->getGuardUser()->getType() === 'it-admin'): ?>
            <a href="<?php echo url_for('tickets/apply?id=' . $ticket->getId()) ?>" class="btn">принять в работу</a>
          <?php else: ?>
            ещё не в работе
          <?php endif ?>
        </small>
      </h1>

    <?php if (true == ($description = $sf_data->getRaw('ticket')->getDescription())): ?>
      <?php echo $description ?>
    <?php else: ?>
      <div class="alert alert-info">No description given.</div>
    <?php endif ?>
    </div>
  <?php if ($attach=$ticket->attach): ?>
    <hr />
    <div class="attachment">
      Вложения:
      <ul>
        <li><a href="/uploads/ticket-attachments/<?php echo $attach ?>" target="_blank"><?php echo $attach ?></a></li>
      </ul>
    </div>
  <?php endif ?>
  </div>
</div>

<div class="span2 ticket-status">
  <div>
    Заявка №<a href="<?php echo url_for('@tickets-show?id=' . $ticket->getId()) ?>"><?php echo $ticket->getId() ?></a>
  </div>

  <?php if ($ticket->getIsClosed()): ?>
    <div class="label label-important label-large center">
      Закрыта
    </div>
  <?php else: ?>
    <div class="label label-success label-large center">
      Открыта
    </div>
  <?php endif ?>

  <div>
    Комментариев: <?php echo $ticket->getComments()->count() ?>
  </div>

  <?php if ($canManipulateThisTicket): ?>
    <div class="center">
      <a href="<?php echo url_for('@tickets-edit?id=' . $ticket->getId()) ?>" class="btn btn-mini">
        <i class="icon icon-pencil"></i>
        Редактировать
      </a>
    </div>
  <?php endif ?>

  <?php if ($sf_user->hasCredential('delete_tickets')): ?>
    <div class="center">
      <a href="#" class="btn btn-mini ticket-deleter" data-delete-uri="<?php echo url_for('@tickets-delete?id=' . $ticket->getId()) ?>">
        <i class="icon icon-remove"></i>
        Удалить
      </a>
    </div>
  <?php endif ?>

  <?php if (true == ($responsibles = $ticket->getResponsibles()) and count($responsibles)): ?>
    <div>
      Ответственные:
      <ul style="text-align:left"><?php foreach ($responsibles as $user): ?>
        <li><a href="#"><?php echo $user->getUsername() ?></a></li>
      <?php endforeach ?></ul>
    </div>
  <?php endif ?>

  <?php $company = $ticket->getCreator()->getGroups()->getFirst(); if ($company && true == ($responsibles = $company->getResponsibles()) and count($responsibles)): ?>
    <div>
      Ответственные за компанию:
      <ul style="text-align:left"><?php foreach ($responsibles as $user): ?>
        <li><a href="#"><?php echo $user->getUsername() ?></a></li>
      <?php endforeach ?></ul>
    </div>
  <?php endif ?>
</div>

</div>

<hr class="hidden"/>

<?php if ($ticket->getComments()->count()): use_helper('Text');?>
<div class="comments">
<?php foreach ($ticket->getComments() as $comment): ?>
  <div class="row-fluid" id="ticketcomment-<?php echo $comment->getId() ?>">
    <!-- //userpics are not implemented for now
    <div class="span">
      <img width="48" src="http://placehold.it/140" alt="userpic of <?php echo $comment->getCreator()->getUsername() ?>" />
    </div>-->
    <div class="comment" >
      <?php $comment->isRead();?>

      <div class="header">
        <span class="who"><a href="#"><?php echo $comment->getCreator()->getUsername() ?></a></span>
        <span class="what">commented<?php if ($state=$comment->getChangedTicketStateTo()): ?> and <?php echo $state; endif ?></span>
        <span class="when pull-right">
          <a href="#ticketcomment-<?php echo $comment->getId() ?>">
            <?php echo time_ago_in_words(strtotime($comment->getCreatedAt())) ?> назад
          </a>
        </span>
      </div>

      <div class="content">
      <?php if ($sf_user->hasCredential('can_delete_comments')): ?>
        <ul class="actions unstyled visible-phone visible-tablet hidden-desktop">
          <li class="pull-left">
            <a href="#" class="btn btn-mini" rel="tooltip" title="edit" onclick="alert('not implemented yet'); return false">
              <i class="icon-pencil"></i>
            </a>
          </li>
          <li class="pull-left">
            <a href="#" class="btn btn-mini comment-deleter" rel="tooltip" title="delete" data-delete-uri="<?php
              echo url_for('@comments-delete?id=' . $ticket->getId() . '&comment=' . $comment->getId()) ?>">
              <i class="icon-remove"></i>
            </a>
          </li>
        </ul>
      <?php endif ?>
        <?php echo simple_format_text($comment->getText()) ?>
      <?php if ($attachment=$comment->getAttachment()): ?>
        <hr />
        Вложения:
        <ul>
          <li><a href="/uploads/comment-attachments/<?php echo $attachment ?>" target="_blank"><?php echo $attachment ?></a></li>
        </ul>
      <?php endif ?>
      </div>
    </div>
  </div>
<?php endforeach ?>
</div>
<?php endif ?>

<hr class="hidden"/>

<form action="<?php echo url_for('@comments-create?id=' . $ticket->getId()) ?>" method="post" class="well form-fluid add-comment" enctype="multipart/form-data">
  <h3>Добавить комментарий к заявке</h3>
  <?php echo $form->renderUsing('bootstrap') ?>
  <div class="form-actions">
    <button type="submit" class="btn btn-success btn-large">
      <i class="icon-comment"></i>
      Комментировать
    </button>

<?php if ($canManipulateThisTicket): ?>
  <?php if ($ticket->getIsClosed()): ?>
    <button type="submit" class="btn pull-right ticket-open">
      <i class="icon-ok"></i>
      Комментировать и открыть
    </button>
  <?php else: ?>
    <button type="submit" class="btn pull-right ticket-close">
      <i class="icon-remove"></i>
      Комментировать и закрыть
    </button>
  <?php endif ?>
<?php endif ?>
  </div>
</form>
