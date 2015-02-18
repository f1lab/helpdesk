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
      , $this['skip_notification']
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

    $requestParameters = sfContext::getInstance()->getRequest()->getParameter($this->getName());
    $ticket = $this->getObject()->getTicket();
    $user = sfContext::getInstance()->getUser();

    if (parent::save($con)) {
      if (isset($requestParameters['changed_ticket_state_to'])
        and in_array($requestParameters['changed_ticket_state_to'], $states)
        and (
          $ticket->getCreatedBy() === $user->getGuardUser()->getId()
          || $user->hasCredential('can_edit_tickets')
          || $user->getGuardUser()->getType() === 'it-admin'
        )
      ) {
        $ticket
          ->setIsClosed((bool)array_search($requestParameters['changed_ticket_state_to'], $states))
          ->save()
        ;
        $user->setFlash('message', array(
          'success',
          'Отлично!',
          'Комментарий добавлен, заявка ' . array_search($requestParameters['changed_ticket_state_to'], $statesRu) . '.'
        ));
      }
    }

    return $this->getObject();
  }
}
