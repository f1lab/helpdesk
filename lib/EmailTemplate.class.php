<?php

final class EmailTemplate {
  static public function newTicket($ticket) {
    $message = '
В системе зарегистрирована заявка № ' . $ticket->getId() . '
Время создания: ' . date('d.m.Y H:i:s', strtotime($ticket->getCreatedAt())) . '
Тема: ' . $ticket->getName() . '
Описание: ' . str_replace(['--', '<br/>'], '', $ticket->getDescription()) . '

В ближайшее время Заявка будет рассмотрена!


Если вы хотите что-то дополнить, то можете ответить на это письмо
или сделать это онлайн на странице http://helpdesk.f1lab.ru/tickets/' . $ticket->getId() . '

--
С уважением, команда F1 Lab
';
    return $message;
  }

  static public function newComment($comment, $reason = null) {
    if ($comment->getChangedTicketStateTo() === 'applied') {
      $text = '';
      $result = [];
      $responsibles = $this->getTicket()->getResponsibles();
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
Время создания: ' . date('d.m.Y H:i:s', strtotime($comment->getCreatedAt())) . '

Если вы хотите что-то дополнить, то можете ответить на это письмо
или сделать это онлайн на странице http://helpdesk.f1lab.ru/tickets/' . $comment->getTicket()->getId() . '

--
С уважением, команда F1 Lab
';

    $reasons = [
      null => 'К заявке добавлен комментарий.'
      , 'mention' => 'Вас упомянули в комментарии.'
      , 'closed' => 'Заявка закрыта.'
      , 'opened' => 'Заявка переоткрыта.'
      , 'applied' => 'Заявка принята в работу.'
    ];

    return ($reasons[$reason ?: $comment->getChangedTicketStateTo()] . "\n") . $message;
  }
}
