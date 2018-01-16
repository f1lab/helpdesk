<?php

/**
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
            ->offsetSet('attachment', new sfWidgetFormInputFile([]))
            ->offsetSet('changed_ticket_state_to', new sfWidgetFormInputHidden())
            ->offsetSet('text', new sfWidgetFormTextarea([], [
                'class' => 'fluid',
                'required' => 'required',
            ]));

        // ->offsetSet('is_remote', new sfWidgetFormChoice([
        //     'choices' => [
        //         true => 'Сделано удалённо',
        //         false => 'Сделано на месте',
        //     ],
        // ]))
        unset($this['is_remote']);

        $this->widgetSchema->setLabels([
            'text' => 'Комментарий',
            'is_remote' => ' ',
            'attachment' => 'Вложение',
        ]);

        $this->validatorSchema['attachment'] = new sfValidatorFile([
            'required' => false,
            'path' => Comment::getAttachmentsPath(),
        ]);
    }
}
