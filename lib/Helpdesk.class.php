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
}
