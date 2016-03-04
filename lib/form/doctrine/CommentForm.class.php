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
        'required' => 'required'
      )))
      ->offsetSet('is_remote', new sfWidgetFormChoice([
        'choices' => [
          true => 'Сделано удалённо',
          false => 'Сделано на месте',
        ],
      ]))
    ;

    $this->widgetSchema->setLabels(array(
      'text' => 'Комментарий',
      'is_remote' => ' ',
      'attachment' => 'Вложение',
    ));

    $this->validatorSchema['attachment'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => Comment::getAttachmentsPath(),
    ));
  }
}
