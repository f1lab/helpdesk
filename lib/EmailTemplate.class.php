<?php

final class EmailTemplate {
  static protected function getFooter($ticket) {
    return '

Если вы хотите что-то дополнить, то можете ответить на это письмо
или сделать это онлайн на странице http://helpdesk.f1lab.ru/tickets/' . $ticket->getId() . '

--
С уважением, команда F1 Lab
';
  }

  static public function newTicket($ticket) {
    $message = '
В системе зарегистрирована заявка № ' . $ticket->getId() . '
Время создания: ' . date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) . '
Тема: ' . $ticket->getName() . '
Описание: ' . str_replace(['--', '<br/>'], '', $ticket->getDescription()) . '

В ближайшее время Заявка будет рассмотрена!' . self::getFooter($ticket);
    return $message;
  }

  static public function newTicketForCompany($ticket) {
    $message = '
Зарегистрирована заявка № ' . $ticket->getId() . '
Компания: ' . $ticket->getCompany() . '
Автор: ' . $ticket->getCreator() . '
Ссылка: http://helpdesk.f1lab.ru/tickets/' . $ticket->getId() . '
' ;
    return $message;
  }

  static public function newComment($comment, $reason = null) {
    if ($comment->getChangedTicketStateTo() === 'applied') {
      $text = '';
      $result = [];
      $responsibles = $comment->getTicket()->getResponsibles();
      if (count($responsibles)) {
        foreach ($responsibles as $responsible) {
          $result[] = $responsible->getFullName();
        }
        $text = "\nОтветственные за выполнение: " . implode(', ', $result) . '.';
      }
      $comment->setText($text);
    }

    $message = '
' . ($comment->getText() ? "\nКомментарий: " . str_replace(['--', '<br/>'], '', $comment->getText()) . "\n" : '') . '
Автор: ' . $comment->getCreator()->getFirstName() . ' ' . $comment->getCreator()->getLastName() . ' (' . $comment->getCreator()->getUsername() . ')
Время создания: ' . date('d.m.Y H:i:s', strtotime($comment->getCreatedAt())) . self::getFooter($comment->getTicket());

    $reasons = [
      null => 'К заявке добавлен комментарий.'
      , 'mention' => 'Вас упомянули в комментарии.'
      , 'closed' => 'Заявка закрыта.'
      , 'opened' => 'Заявка переоткрыта.'
      , 'applied' => 'Заявка принята в работу.'
    ];

    return ($reasons[$reason ?: $comment->getChangedTicketStateTo()] . "\n") . $message;
  }

  static public function addResponsible($ticket) {
    $message = '
Вы были назначены ответственным за выполнение заявки № ' . $ticket->getId() . '

Время создания: ' . date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) . '
Тема: ' . $ticket->getName() . '
Описание: ' . str_replace(['--', '<br/>'], '', $ticket->getDescription()) . self::getFooter($ticket);

    return $message;
  }

  static public function removeResponsible($ticket) {
    $message = '
Вы были убраны из списка ответственных за выполнение заявки № ' . $ticket->getId() . self::getFooter($ticket);

    return $message;
  }
}
