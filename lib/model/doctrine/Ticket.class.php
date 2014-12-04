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
  private function correctDeadline() {
    $deadline = $this->getDeadline();
    if ($deadline) {
      $this->setDeadline(date('Y.m.d 23:59', strtotime($deadline)));
    }
  }

  public function preInsert($event) {
    $this->correctDeadline();
  }

  public function preUpdate($event) {
    $this->correctDeadline();
  }

  public function getCloser() {
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

  public function postInsert($event) {
    $company = $this->getCreator()->getGroups()->getFirst();

    // send sms
    if ($company and true == ($notify = $company->getNotify())) {
      $phones = [];
      foreach ($notify as $user) {
        if ($user->getPhone()) {
          $phones[] = $user->getPhone();
        }
      }

      if (count($phones)) {
        file_get_contents("http://sms.ru/sms/send?api_id=ab037779-734f-3394-794f-7d532843ed95&to="
          . implode(',', $phones) . "&from=f1lab&text="
          . urlencode('Заявка от компании ' . $company->getName() . ', пользователь ' . $this->getCreator()->getUsername())
        );
      }
    }

    if ($this->getCreator()->getEmailAddress() !== 'support@helpdesk.f1lab.ru') {
      // send email to creator
      $mgClient = new Mailgun\Mailgun('key-8979ce7637d74052059dacc30b0ab30e');
      $domain = "helpdesk.f1lab.ru";

      $result = $mgClient->sendMessage($domain, array(
        'from'    => 'Helpdesk <support@helpdesk.f1lab.ru>',
        'to'      => $this->getCreator()->getEmailAddress(),
        'subject' => 'Re: ' . $this->getName(),
        'text'    => 'В системе зарегистрировано Обращение № ' . $this->getId() . '
Время создания: ' . date('d.m.Y H:i:s', strtotime($this->getCreatedAt())) . '
Тема: ' . $this->getName() . '
Описание: ' . $this->getDescription() . '

В ближайшее время Заявка будет рассмотрена!
С уважением, команда F1 Lab
',
      ));
    }
  }
}
