<?php

/**
 * Comment
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    helpdesk
 * @subpackage model
 * @author     Anatoly Pashin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Comment extends BaseComment
{
  public function isRead() {
    $userId = sfContext::getInstance()->getUser()->getGuardUser()->getId();

    $read = Doctrine_Query::create()
      ->from('ReadedComments read')
      ->addWhere('read.user_id = ?', $userId)
      ->addWhere('read.comment_id = ?', $this->getId())
      ->addWhere('read.ticket_id = ?', $this->getTicketId())
      ->count() !== 0
    ;

    if (!$read) {
      $readRecord = ReadedComments::createFromArray([
        'user_id' => $userId,
        'ticket_id' => $this->getTicketId(),
        'comment_id' => $this->getId(),
      ]);
      $readRecord->save();
    }

    return $read;
  }

  public function preInsert($event) {
    $states = [0 => 'opened', 1 => 'closed'];
    $statesRu = ['открыта' => 'opened', 'закрыта' => 'closed'];

    $comment = $event->getInvoker();
    $ticket = $this->getTicket();
    $user = sfContext::getInstance()->getUser();

    if (isset($comment['changed_ticket_state_to'])
      and in_array($comment['changed_ticket_state_to'], $states)
      and (
        $ticket->getCreatedBy() === $user->getGuardUser()->getId()
        || $user->hasCredential('can_edit_tickets')
        || $user->getGuardUser()->getType() === 'it-admin'
      )
    ) {
      $ticket
        ->setIsClosed((bool)array_search($comment['changed_ticket_state_to'], $states))
        ->setIsClosedRemotely((bool)$comment['is_remote'])
        ->save()
      ;

      $user->setFlash('message', [
        'success',
        'Отлично!',
        'Комментарий добавлен, заявка ' . array_search($comment['changed_ticket_state_to'], $statesRu) . '.'
      ]);
    }
  }

  public function postInsert($event) {
    // send message to ticket creator and observers
    if (!$this->getSkipNotification()) {
      // to creator
      if ($this->getCreatedBy() != $this->getTicket()->getCreatedBy()) {
        $to = $this->getTicket()->getRealSender() ?: $this->getTicket()->getCreator()->getEmailAddress();
        Email::send($to, Email::generateSubject($this->getTicket()), EmailTemplate::newComment($this));
      }

      // to observers and responsibles
      $usersToNotify = $this->getTicket()->getResponsiblesAndObserversForNotification();
      foreach ($usersToNotify as $user) {
        if ($user->getId() != $this->getCreatedBy() and $user->getId() != $this->getTicket()->getCreatedBy()) {
          Email::send($user->getEmailAddress(), Email::generateSubject($this->getTicket()), EmailTemplate::newComment($this));
        }
      }
    }

    // send messages to mentioned users
    $mentions = Helpdesk::findMentions($this->getText());
    if (count($mentions) > 0) {
      foreach ($mentions as $mention) {
        if ($mention->getId() != $this->getCreatedBy() and $mention->getId() != $this->getTicket()->getCreatedBy()) {
          Email::send($mention->getEmailAddress(), Email::generateSubject($this->getTicket()), EmailTemplate::newComment($this, 'mention'));

          $observingAlready = Doctrine_Query::create()
            ->from('RefTicketObserver ref')
            ->addWhere('ref.user_id = ?', $mention->getId())
            ->addWhere('ref.ticket_id = ?', $this->getTicket()->getId())
            ->count() !== 0
          ;
          if (!$observingAlready) {
            $observeRecord = RefTicketObserver::createFromArray([
              'user_id' => $mention->getId(),
              'ticket_id' => $this->getTicket()->getId(),
            ]);

            // workaround for mentions in comments created from email
            if (!sfContext::getInstance()->getUser()->getGuardUser()) {
              $observeRecord->setCreatedBy($this->getCreatedBy());
            }

            $observeRecord->save();
          }
        }
      }
    }
  }

  public function getChangedTicketStateToLabel()
  {
    static $states = [
      'applied' => 'принял в работу',
      'closed' => 'закрыл',
      'opened' => 'переоткрыл',
    ];

    return $states[ $this->getChangedTicketStateTo() ];
  }
}
