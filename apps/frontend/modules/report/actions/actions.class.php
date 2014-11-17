<?php

/**
 * report actions.
 *
 * @package    helpdesk
 * @subpackage report
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->tickets = Doctrine_Query::create()
      ->from('Ticket t')
      ->execute()
    ;
  }
}
