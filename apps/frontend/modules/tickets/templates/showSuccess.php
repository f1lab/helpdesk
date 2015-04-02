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
      <?php if ($ticket->getRealSender()): ?>
        <div class="alert alert-info">Письмо пришло от <a href="mailto:<?php echo $ticket->getRealSender() ?>"><?php echo $ticket->getRealSender() ?></a>.</div>
      <?php endif ?>
      <h1 class="page-header">
        <?php echo $ticket->getName() ?>

        <small><?php $applier = $ticket->getApplier(); ?>
          <?php if ($applier): ?>
            в работе с <?php echo date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) ?>
          <?php elseif (!$ticket->getIsClosed() and $sf_user->getGuardUser()->getType() === 'it-admin' and Helpdesk::checkIfImInList($sf_user->getRawValue()->getGuardUser(), $ticket->getRawValue()->getResponsibles())): ?>
            <a href="<?php echo url_for('tickets/apply?id=' . $ticket->getId()) ?>" class="btn">принять в работу</a>
          <?php else: ?>
            ещё не обработана
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

  <?php if (true == ($observers = $ticket->getObservers()) and count($observers)): ?>
    <div>
      Наблюдатели:
      <ul style="text-align:left"><?php foreach ($observers as $user): ?>
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

  <?php if ($ticket->getCreatedBy() != 82): $creator = $ticket->getCreator()->getRawValue(); ?>
    <div>
      Об авторе: <ul>
        <li>Имя: <?php echo $creator->getFirstName() ?: '—' ?></li>
        <li>Фамилия: <?php echo $creator->getLastName() ?: '—' ?></li>
        <li>Должность: <?php echo $creator->getPosition() ?: '—' ?></li>
        <li>Телефон: <?php echo $creator->getPhone() ?: '—' ?></li>
        <li>Телефон рабочий: <?php echo $creator->getWorkPhone() ?: '—' ?></li>
        <li>Username: <?php echo $creator->getUsername() ?: '—' ?></li>
        <li>Email: <?php echo $creator->getEmailAddress() ?: '—' ?></li>
      </ul>
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
        <span class="what">добавил комментарий<?php if ($comment->getChangedTicketStateTo()): ?> и <strong><?php echo $comment->getChangedTicketStateToLabel(); ?> </strong><?php endif ?></span>
        <span class="when pull-right">
          <a href="#ticketcomment-<?php echo $comment->getId() ?>">
            <?php echo date('d.m.Y H:i:s', strtotime($comment->getCreatedAt())) ?>
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

        <?php if ($attachment=$comment->getAttachment()): ?><p>
          <a href="/uploads/comment-attachments/<?php echo $attachment ?>" target="_blank" class="btn btn-link">
            <span class="icon icon-download"></span>
            <?php echo $attachment ?>
          </a>
        </p><?php endif ?>
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
    <button type="submit" class="btn btn-success">
      <i class="icon-comment"></i>
      Комментировать
    </button>

    <div class="pull-right">
      <?php if ($canManipulateThisTicket): ?>
        <?php if ($ticket->getIsClosed()): ?>
          <button type="submit" class="btn ticket-open">
            <i class="icon-ok"></i>
            Комментировать и открыть
          </button>
        <?php elseif ($ticket->getCategoryId() === null): ?>
          <div class="alert alert-info" style="display: inline-block">Нельзя закрыть заявку без категории.</div>
        <?php else: ?>
          <button type="submit" class="btn ticket-close">
            <i class="icon-remove"></i>
            Комментировать и закрыть
          </button>
        <?php endif ?>
      <?php endif ?>
    </div>
  </div>
</form>

<?php if ($sf_user->hasCredential('can mark tickets as duplicates')): ?>
  <form action="<?php echo url_for('ticketsApi/closeAsDuplicate') ?>" method="post" class="well">
    <h3>Закрыть как дубликат</h3>
    <input type="hidden" name="id" value="<?php echo $ticket->getId() ?>">

    <div class="control-group">
      <label class="control-label" for="parent_id">Исходная заявка</label>
      <div class="controls">
        <input type="number" min="1" step="1" name="parent_id" required>

        <button type="submit" class="btn" style="margin-bottom: 9px;">
          Закрыть как дубликат
        </button>
      </div>
    </div>
  </form>
<?php endif ?>
