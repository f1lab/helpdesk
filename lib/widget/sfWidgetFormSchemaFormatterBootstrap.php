<?php
class sfWidgetFormSchemaFormatterBootstrap extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat                 = "<div class=\"control-group %row_class%\">\n %label%\n <div class=\"controls\">\n  %field%\n  %error%\n  %help%\n  %hidden_fields%\n </div>\n</div>\n",
    $errorRowFormat            = '<div class="control-group error">%errors%</div>',
    $errorListFormatInARow     = "<span class=\"help-inline\">%errors%</span>\n",
    $errorRowFormatInARow      = "%error% ",
    $namedErrorRowFormatInARow = "%name%: %error% ",
    $helpFormat                = '<p class="help-block">%help%</p>',
    $decoratorFormat           = '';

  public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
  {
    $row = parent::formatRow(
      $label,
      $field,
      $errors,
      $help,
      $hiddenFields
    );

    return strtr($row, array(
      '%row_class%' => count($errors) ? ' error' : '',
    ));
  }

  public function generateLabel($name, $attributes = array())
  {
    if(isset($attributes['class']))
    {
      $attributes['class'] .= ' control-label';
    }
    else
    {
      $attributes['class'] = 'control-label';
    }
    return parent::generateLabel($name, $attributes);
  }
}
