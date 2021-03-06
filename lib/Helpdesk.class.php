<?php

final class Helpdesk
{
  static public final function checkIfImInList(sfGuardUser $me, Doctrine_Collection $usersCollection)
  {
    foreach ($usersCollection as $user) {
      if ($user->getId() === $me->getId()) {
        return true;
      }
    }

    return false;
  }

  static public final function formatDuration($duration)
  {
    if (is_string($duration)) {
      $remained = time() - strtotime($duration);
    } else {
      $remained = $duration;
    }

    $result = [];

    $months = floor($remained / 2635200);
    $remained -= $months * 2635200;
    if ($months > 0) {
      $result[] = 'месяцев: ' . $months;
    }

    $days = floor($remained / 86400);
    $remained -= $days * 86400;
    if ($days > 0) {
      $result[] = 'суток: ' . $days;
    }

    $hours = floor($remained / 3600);
    $remained -= $hours * 3600;
    if ($hours > 0) {
      $result[] = 'часов: ' . $hours;
    }

    $mins = floor($remained / 60);
    $remained -= $mins * 60;
    if ($mins > 0) {
      $result[] = 'минут: ' . $mins;
    }

    $secs = $remained;
    if ($secs > 0) {
      $result[] = 'секунд: ' . $secs;
    }

    return implode(', ', $result);
  }

  static public function formatDurationDigital($duration)
  {
    if (is_string($duration)) {
      $remained = time() - strtotime($duration);
    } else {
      $remained = $duration;
    }

    $result = [];

    $months = floor($remained / 2635200);
    $remained -= $months * 2635200;
    $result[] = sprintf('%d', $months);

    $days = floor($remained / 86400);
    $remained -= $days * 86400;
    $result[] = sprintf('%d', $days);

    $hours = floor($remained / 3600);
    $remained -= $hours * 3600;
    $result[] = sprintf('%02d', $hours);

    $mins = floor($remained / 60);
    $remained -= $mins * 60;
    $result[] = sprintf('%02d', $mins);

    $secs = $remained;
    $result[] = sprintf('%02d', $secs);

    $string = implode(':', $result);
    while (($prefix = substr($string, 0, 2)) === '0:' or ($prefix = substr($string, 0, 3)) === '00:') {
      $string = substr($string, strlen($prefix));
    }

    return strlen($string) === 2 ? ('00:' . $string) : $string;
  }

  static public final function findMentions($text)
  {
    preg_match_all('/@([a-zA-Z0-9\.\-_]+)/', $text, $matches);
    $usernames = $matches[1];

    $users = [];
    if (count($usernames)) {
      $users = Doctrine_Query::create()
        ->from('sfGuardUser u')
        ->andWhereIn('u.username', $usernames)
        ->execute()
      ;
    }

    return $users;
  }

  static public final function replaceTicketRepeaterMentionsWithLinks($text)
  {
    static $url = null;
    if ($url === null) {
      $url = sfContext::getInstance()->getController()->genUrl('tickets/show?id=', false);
    }

    static $query = null;
    if ($query === null) {
      $query = Doctrine_Query::create()
        ->from('TicketRepeater t')
        ->select('t.name, t.isClosed')
        ->addWhere('t.id = ?')
        ->limit(1)
      ;
    }

    static $formatOpen = '##<a href="%1$s%2$s?repeater=true" title="%3$s">%2$s</a>';
    static $formatClosed = '<del>##<a href="%1$s%2$s?repeater=true" title="%3$s">%2$s</a></del>';

    return preg_replace_callback('/##([0-9]+)/', function($matches) use ($url, $query, $formatOpen, $formatClosed) {
      $ticketId = $matches[1];
      $ticket = $query->fetchOne([$ticketId]);
      if ($ticket) {
        return sprintf($ticket->getIsClosed() ? $formatClosed : $formatOpen, $url, $ticketId, $ticket->getName());
      } else {
        return $matches[0];
      }
    }, $text);
  }

  static public final function replaceTicketMentionsWithLinks($text)
  {
    static $url = null;
    if ($url === null) {
      $url = sfContext::getInstance()->getController()->genUrl('tickets/show?id=', false);
    }

    static $query = null;
    if ($query === null) {
      $query = Doctrine_Query::create()
        ->from('Ticket t')
        ->select('t.name, t.isClosed')
        ->addWhere('t.id = ?')
        ->limit(1)
      ;
    }

    static $formatOpen = '#<a href="%1$s%2$s" title="%3$s">%2$s</a>';
    static $formatClosed = '<del>#<a href="%1$s%2$s" title="%3$s">%2$s</a></del>';

    return preg_replace_callback('/#([0-9]+)/', function($matches) use ($url, $query, $formatOpen, $formatClosed) {
      $ticketId = $matches[1];
      $ticket = $query->fetchOne([$ticketId]);
      if ($ticket) {
        return sprintf($ticket->getIsClosed() ? $formatClosed : $formatOpen, $url, $ticketId, $ticket->getName());
      } else {
        return $matches[0];
      }
    }, $text);
  }
}
