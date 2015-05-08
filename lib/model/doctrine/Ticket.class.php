<?php

/**
 * Ticket
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    helpdesk
 * @subpackage model
 * @author     Anatoly Pashin
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Ticket extends BaseTicket
{
  public function getCloser() {
    return $this->getCommentsForCloser()->getFirst();
    return Doctrine_Query::create()
      ->from('Comment c')
      ->addWhere('c.ticket_id = ?', $this->getId())
      ->addWhere('c.changed_ticket_state_to = ?', 'closed')
      ->leftJoin('c.Creator')
      ->limit(1)
      ->addOrderBy('c.created_at desc')
      ->fetchOne()
    ;
  }

  public function getApplier() {
    return $this->getCommentsForApplier()->getFirst();
    return Doctrine_Query::create()
      ->from('Comment c')
      ->addWhere('c.ticket_id = ?', $this->getId())
      ->addWhere('c.changed_ticket_state_to = ?', 'applied')
      ->leftJoin('c.Creator')
      ->limit(1)
      ->addOrderBy('c.created_at desc')
      ->fetchOne()
    ;
  }

  public function preInsert($event)
  {
    if ($event->getInvoker()->getPlannedStart() === null and $event->getInvoker()->getRepeatedEveryDays() > 0) {
      $this->setPlannedStart(date('Y-m-d H:i:s'));
    }
  }

  public function preUpdate($event)
  {
    if ($event->getInvoker()->getPlannedStart() === null and $event->getInvoker()->getRepeatedEveryDays() > 0) {
      $this->setPlannedStart(date('Y-m-d H:i:s'));
    }
  }

  public function postInsert($event) {
    $company = $this->getCreator()->getGroups()->getFirst();

    // notify it-admins
    if ($company) {
      $subject = Email::generateSubject($this);
      $text = 'Заявка от компании ' . $company->getName() . ', пользователь ' . $this->getCreator()->getUsername() . PHP_EOL
            . 'http://helpdesk.f1lab.ru/tickets/' . $this->getId()
      ;

      // sms
      if (true == ($notify = $company->getNotifySms())) {
        $phones = [];
        foreach ($notify as $user) {
          if ($user->getPhone()) {
            $phones[] = $user->getPhone();
          }
        }

        Sms::send($phones, $text);
      }

      // email
      if (true == ($notify = $company->getNotifyEmail())) {
        $emails = [];
        foreach ($notify as $user) {
          if ($user->getEmailAddress()) {
            $emails[] = $user->getEmailAddress();
          }
        }

        Email::send($emails, $subject, $text);
      }
    }

    // send email to creator
    $to = $this->getRealSender() ?: $this->getCreator()->getEmailAddress();
    Email::send($to, Email::generateSubject($this), EmailTemplate::newTicket($this));
  }

  public function planNextStart()
  {
    $this->setPlannedStart(
      date("Y-m-d H:i:s", strtotime('+' . $this->getRepeatedEveryDays() . ' day', strtotime($this->getPlannedStart())))
    );

    return $this;
  }

  public function getFirstResponsibleRef()
  {
    $firstRef = Doctrine_Query::create()
      ->from('RefTicketResponsible ref')
      ->addWhere('ref.ticket_id = ?', $this->getId())
      ->addOrderBy('ref.created_at asc')
      ->limit(1)
      ->fetchOne()
    ;

    return $firstRef;
  }

  public function getResponsiblesAndObserversForNotification()
  {
    static $result = null;

    if ($result === null) {
      $result = Doctrine_Query::create()
        ->from('sfGuardUser u')
        ->select('u.*')
        ->leftJoin('u.ResponsibleForTickets t1')
        ->leftJoin('u.ObserverForTickets t2')
        ->addWhere('t1.id = :ticket_id or t2.id = :ticket_id', ['ticket_id' => $this->getId()])
        ->execute()
      ;
    }

    return $result;
  }
}
