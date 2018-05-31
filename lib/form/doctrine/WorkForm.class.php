<?php

/**
 */
class WorkForm extends BaseWorkForm
{
    public function configure()
    {
        unset ($this['created_at'], $this['updated_at'], $this['created_by'], $this['updated_by']);

        $this->getWidgetSchema()->offsetSet('started_at', new sfWidgetFormBootstrapDateTime2(array(
            'minView' => 0,
        ), array(
            'placeholder' => '',
            'class' => 'span2',
            'type' => 'date',
        )));
        $this->getWidgetSchema()->offsetSet('finished_at', new sfWidgetFormBootstrapDateTime2(array(
            'minView' => 0,
        ), array(
            'placeholder' => '',
            'class' => 'span2',
            'type' => 'date',
        )));
        $this->getWidgetSchema()->offsetSet('responsible_id', new sfWidgetFormDoctrineChoice(array(
            'model' => 'sfGuardUser',
            'query' => Doctrine_Query::create()
                ->from('sfGuardUser a')
                ->where('a.type = ?', 'it-admin'),
        ), array(
            'class' => 'chzn-select',
            'data-placeholder' => 'Выберите…',
        )));

        $this->widgetSchema->setLabels([
            'started_at' => 'Факт. начало работ',
            'finished_at' => 'Факт. окончание работ',
            'description' => 'Содержание работ',
            'responsible_id' => 'Исполнитель',
        ]);
    }
}
