<?php

final class Helpdesk
{
  static public final function checkIfImInList(sfGuardUser $me, Doctrine_Collection $usersCollection) {
    foreach ($usersCollection as $user) {
      if ($user->getId() === $me->getId()) {
        return true;
      }
    }

    return false;
  }

  static public final function formatDuration($duration) {
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
}
