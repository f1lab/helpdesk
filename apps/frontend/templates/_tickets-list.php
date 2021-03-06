<?php use_helper('Date') ?>
<?php if ($tickets->count()): ?>
<table class="table  table-bordered table-condensed tickets">
  <thead>
    <tr>
      <th>№</th>
      <th>Тема</th>
      <?php if (isset($showCategories) and $showCategories): ?><th>Категория</th><?php endif ?>
      <?php if ($showDate): ?><th>Дата</th><?php endif ?>
      <?php if ($showCompanyName): ?><th>Компания</th><?php endif ?>
      <?php if ($showUserName): ?><th>Пользователь</th><?php endif ?>

      <?php if ($showDeadline): ?><th>Дедлайн</th><?php endif ?>
      <?php if (!isset($dontShowApply)): ?>
        <th>В работе</th>
      <?php endif ?>
      <th> </th>
    </tr>
  </thead>
  <tbody>

  <?php
    $existQuery = Doctrine_Core::getTable('ReadedTickets')->createQuery('a')
      ->where('a.user_id = ?')
      ->andWhere('a.ticket_id = ?')
    ;

    $commentsQuery = Doctrine_Core::getTable('ReadedComments')->createQuery('a')
      ->where('a.Comment.ticket_id = ?')
      ->andWhere('a.user_id = ?')
    ;
  ?>

  <?php foreach ($tickets as $ticket): ?>
    <?php
          $exist = $existQuery->count([$sf_user->getGuardUser()->getId(), $ticket->getId()]);
          $allReadedCommentsForTicket = $commentsQuery->count([$ticket->getId(), $sf_user->getGuardUser()->getId()]);
    ?>

    <tr class="<?= $exist === 0 ? 'alert-success' : ''?>">
      <td><?php echo $ticket->getId() ?></td>
      <td><a href="<?php echo url_for('@tickets-show?id=' . $ticket->getId()) ?>"><?php echo $ticket->getName() ?></a></td>

      <?php if (isset($showCategories) and $showCategories): ?><td><?php echo $ticket->getCategory() ?></td><?php endif ?>

      <?php if ($showDate): ?><td title="<?php echo $ticket->getCreatedAt() ?>">
        <?php echo date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) ?>
      </td><?php endif ?>

      <?php if ($showCompanyName): ?><td>#<?php
        $company = $ticket->getCreator()->getGroups()->getFirst();
        echo $company ? $company->getName() : 'компания неизвестна';
      ?></td><?php endif ?>

      <?php if ($showUserName): ?><td>@<?php echo $ticket->getCreator()->getUsername() ?></td><?php endif ?>

      <?php if ($showDeadline): ?><td title="<?php echo $ticket->getDeadline() ?>">
        ещё <?php
        echo ($ticket->getDeadline()
          ? time_ago_in_words(strtotime($ticket->getDeadline()))
          : 'есть время')
          . ($ticket->getDeadline() && strtotime($ticket->getDeadline()) < time() ? ' назад!' : '')
          ?>
      </td><?php endif ?>

      <?php if (!isset($dontShowApply)): $applier = $ticket->getApplier(); ?>
        <td>
          <?php if ($applier): ?>
            в работе с <?php echo date('d.m.Y H:i:s', strtotime($applier->getCreatedAt())) ?>
          <?php elseif (!$ticket->getIsClosed() and $sf_user->getGuardUser()->getType() === 'it-admin' and Helpdesk::checkIfImInList($sf_user->getRawValue()->getGuardUser(), $ticket->getRawValue()->getResponsibles())): ?>
            <a href="<?php echo url_for('tickets/apply?id=' . $ticket->getId()) ?>" class="btn">принять в работу</a>
          <?php else: ?>
            ещё не обработана
          <?php endif ?>
        </td>
      <?php endif ?>

      <td>
       <?php if ($allReadedCommentsForTicket < $ticket->getComments()->count() ){ ?>
            <span class="badge badge-warning"> <?php echo $ticket->getComments()->count() ?> </span>
        <?php } else{ ?>
             <span class="badge "> <?php echo $ticket->getComments()->count() ?> </span>
        <?php } ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <div class="alert alert-info">
    <strong>Нет заявок</strong>.
  </div>
<?php endif; ?>
