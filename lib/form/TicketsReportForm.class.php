<?php

class TicketsReportForm extends BaseReportForm
{
  public function configure()
  {
      parent::configure();

    $this->getWidgetSchema()
      ->offsetSet('type', new sfWidgetFormChoice([
        'label' => 'Тип отчёта',
        'choices' => [
          'createdIn+closed' => 'Закрытые заявки, созданные в указанный период',
          'createdIn+notClosed' => 'Открытые заявки, созданные в указанный период',
          'createdBefore+closed' => 'Созданные ранее указанного периода и закрытые в указанный',
          'createdBefore+notClosed' => 'Созданные ранее указанного периода и до сих пор не закрытые',
        ],
      ], ['class' => 'span6']))

      ->offsetSet('headers_drawer', new sfWidgetFormChoice([
        'label' => ' ',
        'choices' => [
          0 => 'Не повторять заголовки',
          15 => 'Повторить каждые 15 строк',
          30 => 'Повторить каждые 50 строк',
          60 => 'Повторить каждые 50 строк',
        ],
      ]))
    ;

    $this->getValidatorSchema()
      ->offsetSet('type', new sfValidatorPass(array(
        'required' => true,
      )))
      ->offsetSet('headers_drawer', new sfValidatorPass())
    ;
  }
}



