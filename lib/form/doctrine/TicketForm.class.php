<?php

/**
 * Ticket form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TicketForm extends BaseTicketForm
{
  public function configure()
  {
    unset (
      $this['created_at'],
      $this['updated_at'],
      $this['created_by'],
      $this['updated_by'],
      $this['isClosed'],
      $this['planned_start'],
      $this['planned_finish'],
      $this['deleted_at']
      , $this['real_sender']
    );

    if (sfContext::getInstance()->getUser()->hasCredential('can_set_responsibles_for_tickets')) {
      $this->getWidgetSchema()
        ->offsetSet('responsibles_list', new sfWidgetFormDoctrineChoice(array(
          'multiple' => true,
          'model' => 'sfGuardUser',
          'query' => Doctrine_Query::create()
            ->from('sfGuardUser a')
            ->where('a.type = ?', 'it-admin'),
        ), array(
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        )))

        ->offsetSet('company_id', new sfWidgetFormDoctrineChoice(array(
          'multiple' => false,
          'model' => 'sfGuardUser',
          'add_empty' => true,
          'query' => Doctrine_Query::create()
            ->from('sfGuardGroup a')
            ->addWhere('a.id in (select group_id from ref_company_responsible where user_id = ?)', sfContext::getInstance()->getUser()->getGuardUser()->getId())
          ,
        ), array(
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        )))
      ;
    } else {
      unset (
        $this['responsibles_list']
        , $this['company_id']
      );
    }

    $this->getWidgetSchema()
      ->offsetSet('name', new sfWidgetFormInputText(array(), array(
        'class' => "fluid",
      )))
      ->offsetSet('description', new sfWidgetFormTextarea(array(), array(
        'class' => 'fluid wysiwyg',
        'rows' => 15,
      )))
      ->offsetSet('attach', new sfWidgetFormInputFile())
      ->offsetSet('observers_list', new sfWidgetFormDoctrineChoice(array(
          'multiple' => true,
          'model' => 'sfGuardUser',
          'query' => Doctrine_Query::create()
            ->from('sfGuardUser a')
            ->addOrderBy('a.first_name, a.last_name')
        ), array(
          'class' => 'chzn-select',
          'data-placeholder' => 'Выберите…',
        )))
      ;

    if (sfContext::getInstance()->getUser()->hasCredential('can set deadlines for tickets')) {
      $this->getWidgetSchema()->offsetSet('deadline', new sfWidgetFormBootstrapDateTime2(array(
        'minView' => 0,
      ), array(
        'placeholder' => '',
        'class' => 'span2',
        'type' => 'date',
      )));
    } else {
      $this->getWidgetSchema()->offsetSet('deadline', new sfWidgetFormInputHidden());
    }

    if (!sfContext::getInstance()->getUser()->hasCredential('can set categories for tickets')) {
      $this->getWidgetSchema()->offsetSet('category_id', new sfWidgetFormInputHidden());
    }

    $this->widgetSchema->setLabels(array(
      'name' => 'Тема',
      'description' => 'Описание',
      'attach' => 'Вложение',
      'deadline' => 'Сделать до',
      'responsibles_list' => 'Ответственные',
      'period_id' => 'Период',
      'company_id' => 'На компанию',
      'category_id' => 'Категория',
      'observers_list' => 'Наблюдатели',
    ));

    $this->validatorSchema['attach'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/ticket-attachments',
      //'mime_types' => 'web_images',
    ));
    $this->validatorSchema['shedule_time'] = new sfValidatorTime(array('required' => false));
  }
}
