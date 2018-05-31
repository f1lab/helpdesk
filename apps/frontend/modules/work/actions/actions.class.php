<?php

/**
 * work actions.
 *
 * @package    helpdesk
 * @subpackage work
 * @author     Anatoly Pashin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class workActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $this->works = Doctrine_Query::create()
            ->from('Work w')
            ->execute();
    }

    public function executeShow(sfWebRequest $request)
    {
        $this->work = Doctrine_Core::getTable('Work')->find([$request->getParameter('id')]);
        $this->forward404Unless($this->work);
    }

    public function executeNew(sfWebRequest $request)
    {
        $work = new Work();
        if ($request->getParameter('ticket_id')) {
            $work->setTicketId($request->getParameter('ticket_id'));
        }
        $this->form = new WorkForm($work);
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new WorkForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
        $this->forward404Unless($work = Doctrine_Core::getTable('Work')->find([$request->getParameter('id')]),
            sprintf('Object work does not exist (%s).', $request->getParameter('id')));
        $this->form = new WorkForm($work);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($work = Doctrine_Core::getTable('Work')->find([$request->getParameter('id')]),
            sprintf('Object work does not exist (%s).', $request->getParameter('id')));
        $this->form = new WorkForm($work);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        $this->forward404Unless($work = Doctrine_Core::getTable('Work')->find([$request->getParameter('id')]),
            sprintf('Object work does not exist (%s).', $request->getParameter('id')));
        $work->delete();

        $this->redirect('work/index');
    }

    protected function processForm(sfWebRequest $request, WorkForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $work = $form->save();

            $this->redirect('@tickets-show?id=' . $work->getTicketId());
        }
    }

    public function executePrint(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
    }
}
