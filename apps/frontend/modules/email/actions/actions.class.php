<?php

/**
 * email actions.
 *
 * @package    helpdesk
 * @subpackage email
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class emailActions extends sfActions
{
  public function executePost(sfWebRequest $request)
  {
    if ($request->getParameter('sender') === 'support@helpdesk.f1lab.ru') {
      $this->forward404();
    }

    $from = Doctrine_Query::create()
      ->from('sfGuardUser u')
      ->addWhere('u.email_address = ?', $request->getParameter('sender'))
      ->fetchOne()
    ;

    $ticket = Ticket::createFromArray([
      'name' => $request->getParameter('subject')
      , 'description' =>
        ($from ? '' : '<div class="alert alert-info">Письмо пришло от ' . htmlspecialchars($request->getParameter('from')) . '</div>')
        . '<pre>' . $request->getParameter('body-plain') . '</pre>'
      , 'created_by' => $from ? $from->getId() : 82
    ]);

    $ticket->save();
    die;
  }
}
