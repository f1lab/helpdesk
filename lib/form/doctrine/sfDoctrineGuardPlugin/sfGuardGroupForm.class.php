<?php

/**
 * sfGuardGroup form.
 *
 * @package    helpdesk
 * @subpackage form
 * @author     Anatoly Pashin
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupForm extends PluginsfGuardGroupForm
{
    public function configure()
    {
        unset (
            $this['permissions_list'],
            $this['isExecutor'],
            $this['isClient']
        );

        /* @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $this->getWidgetSchema()
            ->offsetSet('description', new sfWidgetFormInputText([]))
            ->offsetSet('responsibles_list', new sfWidgetFormDoctrineChoice([
                'multiple' => true,
                'model' => 'sfGuardUser',
                'query' => Doctrine_Query::create()
                    ->from('sfGuardUser a')
                    ->leftJoin('a.Groups b')
                    ->where('b.isExecutor = ?', true),
            ], [
                'class' => 'chzn-select',
                'data-placeholder' => 'Выберите…',
            ]))
            ->offsetSet('users_list', new sfWidgetFormDoctrineChoice([
                'multiple' => true,
                'model' => 'sfGuardUser',
            ], [
                'class' => 'chzn-select',
                'data-placeholder' => 'Выберите…',
            ]))
            ->offsetSet('notify_sms_list', new sfWidgetFormDoctrineChoice([
                'multiple' => true,
                'model' => 'sfGuardUser',
                'query' => Doctrine_Query::create()
                    ->from('sfGuardUser u')
                    ->where('u.type = ?', 'it-admin')
                ,
            ], [
                'class' => 'chzn-select',
                'data-placeholder' => 'Выберите…',
            ]))
            ->offsetSet('notify_email_list', new sfWidgetFormDoctrineChoice([
                'multiple' => true,
                'model' => 'sfGuardUser',
                'query' => Doctrine_Query::create()
                    ->from('sfGuardUser u')
                    ->where('u.type = ?', 'it-admin')
                ,
            ], [
                'class' => 'chzn-select',
                'data-placeholder' => 'Выберите…',
            ]))
            ->offsetSet('working_hours_from', new sfWidgetFormTime([]))
            ->offsetSet('working_hours_to', new sfWidgetFormTime([]));

        $this->validatorSchema['description'] = new sfValidatorString([
            'max_length' => 128,
            'required' => false,
        ]);

        $this->validatorSchema['name'] = new sfValidatorString([
            'max_length' => 32,
            'required' => true,
        ]);

        $this->widgetSchema->setLabels([
            'name' => 'Alias',
            'users_list' => 'Пользователи',
            'description' => 'Наименование',
            'responsibles_list' => 'Ответственные',
            'notify_sms_list' => 'Кого оповещать по SMS',
            'notify_email_list' => 'Кого оповещать по Email',
            'sms_title' => 'Заголовок смс-сообщения',
            'deadline_for_setting_responsible' => 'Время для назначения ответственного, в секундах',
            'deadline_for_approving' => 'Время для принятия в работу, в секундах',
            'working_hours_from' => 'Время оказания услуг — с',
            'working_hours_to' => 'По',
        ]);
    }
}
