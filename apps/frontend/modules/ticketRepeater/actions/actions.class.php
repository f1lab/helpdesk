<?php

/**
 * ticketRepeater actions.
 *
 * @package    helpdesk
 * @subpackage ticketRepeater
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketRepeaterActions extends sfActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TicketRepeaterForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new TicketRepeaterForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($ticket_repeater = Doctrine_Core::getTable('TicketRepeater')->find(array($request->getParameter('id'))), sprintf('Object ticket_repeater does not exist (%s).', $request->getParameter('id')));
    $this->form = new TicketRepeaterForm($ticket_repeater);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless(
      $this->getUser()->hasCredential('can_edit_tickets')
      and true == ($this->form = new TicketRepeaterForm(Doctrine_Core::getTable('TicketRepeater')->find($request->getParameter('id'))))
    );

    $this->processForm($request, $this->form, array('success', 'Отлично!', 'Изменения сохранены.'));

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('delete_tickets')) {
      if (Doctrine_Core::getTable('TicketRepeater')->find($request->getParameter('id'))->delete()) {
        $this->getUser()->setFlash('message', array('success', 'Отлично!', 'Одной заявкой меньше.'));
      }
    }

    $this->redirect('tickets/v2');
  }

    public function processForm(
      sfWebRequest $request,
      sfForm $form,
      $flash = array(
        'success',
        'Отлично!',
        'Заявка добавлена.',
      ),
      $redirect = false
  ) {
    $form->bind(
      $request->getParameter($form->getName()),
      $request->getFiles($form->getName())
    );

    if ($form->isValid()) {
      $ticket = $form->save();
      $this->getUser()->setFlash('message', $flash);
      $this->redirect($redirect ? $redirect : '@tickets-show?repeater=true&id=' . $ticket->getId());
    }
  }
}
