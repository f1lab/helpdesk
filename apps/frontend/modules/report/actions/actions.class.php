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
        'required' => true,
        'multiple' => true,
      )))
      ->offsetSet('company_id', new sfValidatorDoctrineChoice(array(
        'model' => 'sfGuardGroup',
        'required' => true,
        'multiple' => true,
      )))
    ;

    $this->period = array(
      'from' => date('Y') . '-01-01',
      'to' => date('Y-m-d', strtotime('+1 day')),
    );

    $this->categoryIds = Doctrine_Query::create()->from('Category')->select('id')->execute([], Doctrine_Core::HYDRATE_SINGLE_SCALAR);

    $this->form->setDefault('category_id', $this->categoryIds);
    $this->form->setDefault('company_id', $this->companyIds);
  }

  public function executeIndex(sfWebRequest $request)
  {
  }

  public function executeGet(sfWebRequest $request)
  {
    if ($request->isMethod('post')) {
      $this->form->bind($request->getParameter('filter'));

      if ($this->form->isValid()) {
        if ($this->form->getValue('from')) {
          $this->period['from'] = $this->form->getValue('from');
        }

        if ($this->form->getValue('to')) {
          $this->period['to'] = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($this->form->getValue('to'))));
        }

        if ($this->form->getValue('category_id')) {
          $this->categoryIds = (array)$this->form->getValue('category_id');
        }

        if ($this->form->getValue('company_id')) {
          $this->company_id = (array)$this->form->getValue('company_id');
        }
      }
    }

    $this->tickets = Doctrine_Query::create()
      ->from('Ticket t')
      ->leftJoin('t.Creator')
      ->leftJoin('t.Category')
      ->addWhere('t.created_at >= ? and t.created_at <= ?', array($this->period['from'], $this->period['to']))
      ->andWhereIn('t.category_id', $this->categoryIds)
      ->andWhereIn('t.company_id', $this->company_id)
      // ->addOrderBy('c.created_at desc')
      ->execute()
    ;
  }
}
