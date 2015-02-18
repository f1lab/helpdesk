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
  public function executePost(sfWebRequest $request) {
    // drop self sent emails
    if ($request->getParameter('sender') === 'support@helpdesk.f1lab.ru') {
      die('dropped self sent email');
    }

    $result = [];

    // get sender user
    $from = Doctrine_Query::create()
      ->from('sfGuardUser u')
      ->addWhere('u.email_address = ?', $request->getParameter('sender'))
      ->fetchOne()
    ;

    $ticket = null;
    $subject = $request->getParameter('subject');

    // search for ticket id in subject and search for such ticket in db
    if (preg_match('/\[F1LAB\-HLPDSK\-([\d]+)\]/', $subject, $mathed)) {
      $result[] = 'matched magic subject';
      $ticket = Doctrine_Query::create()
        ->from('Ticket t')
        ->addWhere('t.id = ?', $mathed[1])
        ->limit(1)
        ->fetchOne()
      ;

      if ($ticket) {
        $result[] = 'ticket found';
        // ticket found, reopen if needed and add comment
        $comment = Comment::createFromArray([
          'ticket_id' => $ticket->getId()
          , 'text' => $request->getParameter('body-plain')
          , 'created_by' => $from ? $from->getId() : 82
        ]);

        if ($ticket->getIsClosed()) {
          $comment->setChangedTicketStateTo('opened');
          $ticket->setIsClosed(false)->save();
        }

        $comment->save();
        $result[] = 'comment created';
      }
    }

    // no such ticket found, creating
    if (!$ticket) {
      $result[] = 'no such ticket';
      $ticket = Ticket::createFromArray([
        'name' => $subject
        , 'company_id' => $from && $from->getGroups() && $from->getGroups()->getFirst() ? $from->getGroups()->getFirst() : 1
        , 'description' => str_replace("\n", "<br/>\n", $request->getParameter('body-plain'))
        , 'created_by' => $from ? $from->getId() : 82
        , 'real_sender' => $from ? null : $request->getParameter('sender')
      ]);

      $ticket->save();
      $result[] = 'ticket created';
    }

    // adding files to ticket
    if (count($_FILES)) {
      $destination = sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . 'comment-attachments' . DIRECTORY_SEPARATOR;
      foreach ($_FILES as $file) {
        $nameParts = explode('.', $file['name']);
        $extension = array_pop($nameParts);
        $newName = uniqid() . '.' . $extension;
        if (move_uploaded_file($file['tmp_name'], $destination . $newName)) {
          $comment = Comment::createFromArray([
            'ticket_id' => $ticket->getId()
            , 'text' => 'Добавлен файл из письма: ' . $file['name']
            , 'attachment' => $newName
            , 'created_by' => $from ? $from->getId() : 82
            , 'skip_notification' => true
          ]);

          $comment->save();
          $result[] = 'comment with file created';
        }
      }
    }

    die(print_r($result, 1));
  }
}
