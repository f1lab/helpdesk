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
      if (!$isRepeater and true == ($dl = $ticket->getDeadline())):
        $dl = str_replace('00:00:00', '', $dl);
        if (substr($dl, -3) == ':00'):
          $dl = substr($dl, 0, -3);
        endif;
    ?>
      <p><span class="label label-warning">deadline: <?php echo $dl ?></span></p>
    <?php endif ?>
    <div class="content">
      <?php if (!$isRepeater and $ticket->getRealSender()): ?>
        <div class="alert alert-info">Письмо пришло от <a href="mailto:<?php echo $ticket->getRealSender() ?>"><?php echo $ticket->getRealSender() ?></a>.</div>
      <?php endif ?>
      <h1 class="page-header">
        <?php echo $ticket->getName() ?>

        <?php if (!$isRepeater): ?><small><?php $applier = $ticket->getApplier(); ?>
          <?php if ($applier): ?>
            в работе с <?php echo date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) ?>
          <?php elseif (!$ticket->getIsClosed() and $sf_user->getGuardUser()->getType() === 'it-admin' and Helpdesk::checkIfImInList($sf_user->getRawValue()->getGuardUser(), $ticket->getRawValue()->getResponsibles())): ?>
            <a href="<?php echo url_for('tickets/apply?id=' . $ticket->getId()) ?>" class="btn">принять в работу</a>
          <?php else: ?>
            ещё не обработана
          <?php endif ?>
        </small><?php endif ?>
      </h1>

    <?php if (true == ($description = $sf_data->getRaw('ticket')->getDescription())): ?>
      <?php echo $description ?>
    <?php else: ?>
      <div class="alert alert-info">No description given.</div>
    <?php endif ?>
    </div>
  <?php if (!$isRepeater and true == ($attach = $ticket->attach)): ?>
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

  <?php if (!$isRepeater): ?><div>
    Комментариев: <?php echo $ticket->getComments()->count() ?>
  </div><?php endif ?>

  <?php if ($canManipulateThisTicket): ?>
    <div class="center">
      <a href="<?php echo url_for('ticketRepeater/edit?id=' . $ticket->getId()) ?>" class="btn btn-mini">
        <i class="icon icon-pencil"></i>
        Редактировать
      </a>
    </div>
  <?php endif ?>

  <?php if ($sf_user->hasCredential('delete_tickets')): ?>
    <div class="center">
      <a href="#" class="btn btn-mini ticket-deleter" data-delete-uri="<?php echo url_for('ticketRepeater/delete?id=' . $ticket->getId()) ?>">
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

<?php if (!$isRepeater and $ticket->getComments()->count()): use_helper('Text');?>
<div class="comments">
<?php foreach ($ticket->getComments() as $comment): ?>
  <div class="row-fluid" id="ticketcomment-<?php echo $comment->getId() ?>">
    <!-- //userpics are not implemented for now
    <div class="span">
      <img width="48" src="http://placehold.it/140" alt="userpic of <?php //echo $comment->getCreator()->getUsername() ?>" />
    </div>-->
    <div class="comment" >
      <?php $comment->isRead();?>

      <div class="header">
        <span class="who"><a href="#"><?php echo ($commentCreator = $comment->getCreator()) == true ? $commentCreator->getUsername() : 'unknown' ?></a></span>
        <span class="what">
          добавил комментарий
          <?php if ($comment->getChangedTicketStateTo()): ?>
            и <strong><?php echo $comment->getChangedTicketStateToLabel(); ?> </strong>
          <?php endif ?>
          <?php echo $comment->getIsRemote() ? 'удалённо' : 'на месте' ?>
        </span>
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
        <?php echo Helpdesk::replaceTicketMentionsWithLinks(simple_format_text($comment->getText())) ?>

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

<?php if (!$isRepeater): ?>
<form action="<?php echo url_for('@comments-create?id=' . $ticket->getId()) ?>" method="post" class="well form-fluid add-comment" enctype="multipart/form-data">
  <h3>Добавить комментарий к заявке</h3>
  <?php echo $form->renderUsing('bootstrap') ?>

  <div class="btn-toolbar">
    <div class="btn-group">
      <button type="submit" class="btn btn-success">
        <i class="icon-comment"></i>
        Комментировать
      </button>
    </div>

    <div class="btn-group" ng-controller="TicketShowPageController">
      <?php if ($sf_user->hasCredential('can mark tickets as duplicates')): ?>
        <button class="btn" ng-click="closeAsDup(<?php echo $ticket->getId() ?>)">Закрыть как дубликат</button>
      <?php endif ?>

      <?php if (Helpdesk::checkIfImInList($sf_user->getRawValue()->getGuardUser(), $ticket->getRawValue()->getResponsibles())): ?>
        <button class="btn" ng-click="iAmNotResponsibleForThis(<?php echo $ticket->getId() ?>)">Отказаться от заявки</button>
      <?php endif ?>
    </div>

    <div class="btn-group pull-right">
      <?php if ($canManipulateThisTicket): ?>
        <?php if ($ticket->getIsClosed()): ?>
          <button type="submit" class="btn ticket-open">
            <i class="icon-ok"></i>
            Комментировать и открыть
          </button>
        <?php elseif ($ticket->getCategoryId() === null): ?>
          <div class="alert alert-info" style="display: inline-block; font-size: 14px; padding: 4px 15px;">Нельзя закрыть заявку без категории.</div>
        <?php elseif (!$applier): ?>
          <div class="alert alert-info" style="display: inline-block; font-size: 14px; padding: 4px 15px;">Нельзя закрыть заявку не принимая её в работу.</div>
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
<?php endif ?>

<script type="text/ng-template" id="/i-am-not-responsible.html">
  <div class="modal top am-fade-and-slide-top" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" ng-click="$hide()">×</button>
          <h3 class="modal-title">Отказаться от заявки</h3>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="control-group">
              <label class="control-label" for="name">Причина</label>
              <div class="controls">
                <textarea name="name" id="name" cols="30" rows="10" class="fluid" ng-model="reasonForDecline" required></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="confirmDecline(reasonForDecline)">Отказаться</button>
          <button type="button" class="btn btn-default" ng-click="$hide()">Отмена</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/ng-template" id="/close-as-dup.html">
  <div class="modal top am-fade-and-slide-top" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" ng-click="$hide()">×</button>
          <h3 class="modal-title">Закрыть как дубликат</h3>
        </div>
        <div class="modal-body">
          <div className="alert alert-error" ng-show="error">Ошибка загрузки списка заявок, попробуйте ещё раз</div>
          <form action="">
            <div class="control-group">
              <label class="control-label" for="name">Исходная заявка</label>
              <div class="controls">
                <select name="name" id="name" ng-options="ticket.id as (ticket.id + ': ' + ticket.name) for ticket in tickets" ng-model="parentId" class="fluid">
                  <option value="">Выберите</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" ng-click="confirmClose(parentId)">Закрыть</button>
          <button type="button" class="btn btn-default" ng-click="$hide()">Отмена</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/coffeescript" src="/js/angular-TicketShowPageController.coffee"></script>

<script>
  (function() {
    var possibleMentions = <?php echo json_encode(Doctrine_Query::create()
      ->from('sfGuardUser u')
      ->addOrderBy('u.last_name, u.first_name, u.username')
      ->select('u.last_name, u.first_name, u.username')
      ->execute([], Doctrine_Core::HYDRATE_ARRAY), JSON_UNESCAPED_UNICODE);
    ?>;

    $('#comment_text').textcomplete([{
      'match': /@([\w\.]+)/
      , index: 1

      , search: function (term, callback) {
        term = term.toLowerCase();

        callback($.map(possibleMentions, function (mention) {
          return mention.username.toLowerCase().indexOf(term) === 0 ? mention : null;
        }));
      }

      , replace: function (mention) {
        return '@' + mention.username + ' ';
      }

      , template: function (mention) {
        console.log(mention);
        return mention.first_name.length > 1 && mention.last_name.length > 1
          ? mention.first_name + ' ' + mention.last_name + ' (' + mention.username + ')'
          : mention.username
        ;
      }
    }]);
  })();
</script>
