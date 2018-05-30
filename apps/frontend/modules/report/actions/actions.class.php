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
        $this->form = new TicketsReportForm();
    }

    public function executeGet(sfWebRequest $request)
    {
        $this->tickets = [];

        $this->form = new TicketsReportForm();
        $this->form->bind($request->getParameter('filter'));

        if ($this->form->isValid()) {
            $query = Doctrine_Query::create()
                ->from('Ticket t')
                ->leftJoin('t.Creator')
                ->leftJoin('t.Category')
                ->leftJoin('t.Company')
                ->leftJoin('t.RefTicketResponsible ref')
                ->leftJoin('ref.Creator')
                ->leftJoin('ref.User')
                ->leftJoin('t.CommentsForApplier applier with applier.changed_ticket_state_to = ?', 'applied')
                ->leftJoin('t.CommentsForCloser closer with closer.changed_ticket_state_to = ?', 'closed')
                ->leftJoin('applier.Creator')
                ->leftJoin('closer.Creator')
                ->addOrderBy('t.id asc, ref.created_at asc');

            list ($createdType, $closedType) = explode('+', $this->form->getValue('type'));
            $query->addWhere('t.isClosed = ?', $closedType === 'closed');

            if ($createdType === 'createdIn') {
                if ($this->form->getValue('from')) {
                    $query->addWhere('t.created_at >= ?', $this->form->getValue('from'));
                }

                if ($this->form->getValue('to')) {
                    $query->addWhere('t.created_at <= ?', (new DateTime($this->form->getValue('to')))->modify('+1 day')->format('Y-m-d'));
                }
            } else {
                $query->addWhere('t.created_at <= ?', (new DateTime($this->form->getValue('from')))->modify('+1 day')->format('Y-m-d'));
                if ($closedType === 'closed') {
                    $query->addWhere('closer.created_at >= ?', $this->form->getValue('from'));
                    $query->addWhere('closer.created_at <= ?', (new DateTime($this->form->getValue('to')))->modify('+1 day')->format('Y-m-d'));
                }
            }

            if ($this->form->getValue('company_id')) {
                $query->andWhereIn('t.company_id', (array)$this->form->getValue('company_id'));
            }

            if ($this->form->getValue('category_id')) {
                $query->andWhereIn('t.category_id', (array)$this->form->getValue('category_id'));
            }

            if ($this->form->getValue('responsible_id')) {
                $query->andWhereIn('closer.created_by', (array)$this->form->getValue('responsible_id'));
            }

            $this->tickets = $query->execute();
        }
    }

    public function executeWorks(sfWebRequest $request)
    {
        $this->form = new BaseReportForm();
        $this->works = [];


        if ($request->getParameter('filter')) {
            $this->form->bind($request->getParameter('filter'));
            if ($this->form->isValid()) {
                $query = Doctrine_Query::create()
                    ->from('Work t')
                    ->leftJoin('t.Ticket ticket')
                    ->leftJoin('t.Responsible responsible')
                    ->addOrderBy('t.started_at');

                if ($this->form->getValue('from')) {
                    $query->addWhere('t.started_at >= ?', $this->form->getValue('from'));
                }

                if ($this->form->getValue('to')) {
                    $query->addWhere('t.started_at <= ?', (new DateTime($this->form->getValue('to')))->modify('+1 day')->format('Y-m-d'));
                }

                if ($this->form->getValue('company_id')) {
                    $query->andWhereIn('ticket.company_id', (array)$this->form->getValue('company_id'));
                }

                if ($this->form->getValue('category_id')) {
                    $query->andWhereIn('ticket.category_id', (array)$this->form->getValue('category_id'));
                }

                if ($this->form->getValue('responsible_id')) {
                    $query->andWhereIn('responsible.created_by', (array)$this->form->getValue('responsible_id'));
                }

                $this->works = $query->execute();
            }
        }
    }
}
