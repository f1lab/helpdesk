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
  public function preExecute()
  {
    $this->companyIds = Doctrine_Query::create()
      ->from('RefCompanyResponsible')
      ->select('group_id')
      ->addWhere('user_id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId())
      ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
    ;

    $seesCategories = Doctrine_Query::create()
      ->from('RefUserCategory ref')
      ->select('ref.category_id')
      ->addWhere('ref.user_id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId())
      ->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR)
    ;

    $this->form = new sfForm();
    $this->form->getWidgetSchema()
      ->offsetSet('from', new sfWidgetFormBootstrapDate(array(
        'label' => 'Дата создания',
      )))
      ->offsetSet('to', new sfWidgetFormBootstrapDate(array(
        //
      )))

      ->offsetSet('category_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'Category',
        'label' => 'Категории',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('Category c')
          ->andWhereIn('c.id', $seesCategories)
      ), ['class' => 'chzn-select']))

      ->offsetSet('company_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardGroup',
        'label' => 'Компании',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardGroup')
          ->andWhereIn('id', $this->companyIds)
      ), ['class' => 'chzn-select']))

      ->offsetSet('responsible_id', new sfWidgetFormDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'label' => 'Выполнивший заявку',
        'multiple' => true,
        'query' => Doctrine_Query::create()
          ->from('sfGuardUser')
          ->addWhere('type = ?', 'it-admin')
      ), ['class' => 'chzn-select']))

      ->offsetSet('headers_drawer', new sfWidgetFormInputText([
        'label' => 'Через сколько строк выводить заголовки',
        'default' => 0,
      ], [
        'type' => 'number',
        'min' => 0,
        'step' => 15,
      ]))

      ->setNameFormat('filter[%s]')
    ;

    $this->form->addCSRFProtection('123456789');
    $this->form->getValidatorSchema()
      ->offsetSet('from', new sfValidatorDate(array(
        'required' => false,
      )))
      ->offsetSet('to', new sfValidatorDate(array(
        'required' => false,
      )))
      ->offsetSet('category_id', new sfValidatorDoctrineChoice(array(
        'model' => 'Category',
        'required' => false,
        'multiple' => true,
      )))
      ->offsetSet('company_id', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardGroup',
        'required' => true,
        'multiple' => true,
      )))
      ->offsetSet('responsible_id', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardUser',
        'required' => false,
        'multiple' => true,
      )))
      ->offsetSet('headers_drawer', new sfValidatorPass())
    ;

    $this->period = array(
      'from' => date('Y') . '-01-01',
      'to' => date('Y-m-d', strtotime('+1 day')),
    );

    $this->categoryIds = Doctrine_Query::create()->from('Category')->select('id')->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }

  public function executeIndex(sfWebRequest $request)
  {
  }

  public function executeGet(sfWebRequest $request)
  {
    $this->tickets = [];

    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('filter'));

      if ($this->form->isValid()) {
        $query = Doctrine_Query::create()
          ->from('Ticket t')
          ->leftJoin('t.Creator')
          ->leftJoin('t.Category')
          ->leftJoin('t.ToCompany')
          ->leftJoin('t.Responsibles')
          ->leftJoin('t.CommentsForApplier applier with applier.changed_ticket_state_to = ?', 'applied')
          ->leftJoin('t.CommentsForCloser closer with closer.changed_ticket_state_to = ?', 'closed')
          ->leftJoin('applier.Creator')
          ->leftJoin('closer.Creator')

          ->addWhere('t.isClosed = ?', true)
        ;

        if ($this->form->getValue('from')) {
          $query->addWhere('t.created_at >= ?', $this->form->getValue('from'));
        }

        if ($this->form->getValue('to')) {
          $query->addWhere('t.created_at <= ?', date('Y-m-d H:i:s', strtotime('+1 day', strtotime($this->form->getValue('to')))));
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
  }
}
