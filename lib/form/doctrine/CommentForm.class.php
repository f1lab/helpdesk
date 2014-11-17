<?php

/**
 * Comment form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentForm extends BaseCommentForm
{
  public function configure()
  {
    unset (
      $this['created_at'],
      $this['updated_at'],
      $this['created_by'],
      $this['updated_by'],
      $this['ticket_id']
    );

    $this->getWidgetSchema()
      ->offsetSet('attachment', new sfWidgetFormInputFile(array()))
      ->offsetSet('changed_ticket_state_to', new sfWidgetFormInputHidden())
      ->offsetSet('text', new sfWidgetFormTextarea(array(), array(
        'class' => 'fluid',
      )))
    ;

    $this->widgetSchema->setLabels(array(
      'text' => 'Комментарий',
      'attachment' => 'Вложение',
    ));

    $this->validatorSchema['attachment'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/comment-attachments',
    ));
  }

  public function save($con=null)
  {
    $states = array (0 => 'opened', 1 => 'closed');
    $statesRu = array ('открыта' => 'opened', 'закрыта' => 'closed');

    if (parent::save($con)) {
      if (sfContext::getInstance()->getUser()->getGuardUser()->getGroups()->getFirst()->getIsExecutor()
        and $request=sfContext::getInstance()->getRequest()->getParameter($this->getName())
        and isset($request['changed_ticket_state_to'])
        and in_array($request['changed_ticket_state_to'], $states)
      ) {
        $this->getObject()->getTicket()
          ->setIsClosed((bool)array_search($request['changed_ticket_state_to'], $states))
        ->save();
        sfContext::getInstance()->getUser()->setFlash('message', array(
          'success',
          'Отлично!',
          'Комментарий добавлен, заявка ' . array_search($request['changed_ticket_state_to'], $statesRu) . '.'
        ));
      }
    }

    return $this->getObject();
  }
}
