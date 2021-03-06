<?php

/**
 * @method \sfGuardUser getCreator()
 * @method int getId()
 * @method string getCreatedAt()
 * @method int getCreatedBy()
 */
class Ticket extends BaseTicket
{
  public static function getAttachmentsPath()
  {
    return sfConfig::get('sf_upload_dir').'/ticket-attachments';
  }

  public function getCloser() {
    // just return if CommentsForCloser is joined
    if ($this->hasReference('CommentsForCloser')) {
       return $this->getCommentsForCloser()->getFirst();

    // query db if not
    } else {
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
  }

  public function getApplier() {
    // just return if CommentsForApplier is joined
    if ($this->hasReference('CommentsForApplier')) {
       return $this->getCommentsForApplier()->getFirst();

    // query db if not
    } else {
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
  }

  public function preInsert($event)
  {
    if ($this->getDeadline() === null) {
      $this->setDeadline(date('Y-m-d H:i:s', strtotime('+1 day')));
    }
  }

  public function postInsert($event) {
    $company = $this->getCompany();

    // notify it-admins
    if ($company) {
      // sms
      if (true == ($notify = $company->getNotifySms())) {
        $phones = [];
        foreach ($notify as $user) {
          if ($user->getPhone()) {
            $phones[] = $user->getPhone();
          }
        }

        $text = 'Заявка от компании ' . $company->getName(). ', пользователь '
                . $this->getCreator()->getUsername() . PHP_EOL
                . 'http://helpdesk.f1lab.ru/tickets/' . $this->getId()
        ;
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

        $subject = Email::generateSubject($this);
        $text = EmailTemplate::newTicketForCompany($this);
        Email::send($emails, $subject, $text, $this->getAttachmentsForEmail());
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
    if ($this->hasReference('RefTicketResponsible')) {
      return $this->getRefTicketResponsible()->getFirst();

    } else {
      return Doctrine_Query::create()
        ->from('RefTicketResponsible ref')
        ->addWhere('ref.ticket_id = ?', $this->getId())
        ->addOrderBy('ref.created_at asc')
        ->limit(1)
        ->fetchOne()
      ;
    }
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

  protected function getAttachmentsForEmail()
  {
    return $this->getAttach() ? [static::getAttachmentsPath() . DIRECTORY_SEPARATOR . $this->getAttach()] : [];
  }
}
