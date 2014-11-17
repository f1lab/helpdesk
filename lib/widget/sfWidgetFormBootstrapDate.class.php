<?php

/**
 * sfWidgetFormBootstrapDate represents a date widget using bootstrap framework.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Vorontsov S.A. <raven_4@mail.ru>
 * @author     Anatoly Pashin <anatoly.pashin@gmail.com>
 * @version    SVN: $Id: sfWidgetFormBootstrapDate.class.php 2011-08-02 12:33:33Z  $
 */
class sfWidgetFormBootstrapDate extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('format', '%day%/%month%/%year%');

    if(isset($options['years'])) {
      $years = $options['years'];
    } else {
      $years = range(date('Y') - 15, date('Y') + 5);
    }

    $this->addOption('years', array_combine($years, $years));
    $this->addOption('date_widget', new sfWidgetFormDate(array('format' => $this->getOption('format'), 'years' => $this->getOption('years'))));

    parent::configure($options, $attributes);
  }

  protected function valuetotime($value)
  {
    if (is_array($value)) {
      return strtotime(implode('-', $value));
    } elseif (!is_int($value)) {
      return strtotime($value);
    } else {
      return $value;
    }
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = is_array($value) && join('', $value) !== ''
      ? mktime( 0, 0, 0, (int)$value['month'], (int)$value['day'], (int)$value['year'])
      : $value
    ;

    $date = $value > 0 && !is_array($value)
      ? date('d-m-Y', $this->valuetotime($value))
      : ''
    ;

    $id = $this->generateId($name);
    $calid = $id.'_calendar';
    $did = $this->generateId($name.'[day]');
    $mid = $this->generateId($name.'[month]');
    $yid = $this->generateId($name.'[year]');
    $iid = $this->generateId($name).'_input';
    $sid = $this->generateId($name).'_span';

    $attrs = isset($attributes['placeholder']) ? ' placeholder="' . $attributes['placeholder'] . '"' : '';

    $calendar = <<<HTML
    <div class="input-append date" id="{$calid}" data-date-format="dd-mm-yyyy" data-date-language="ru">
      <input class="span2 not-fluid" id="{$iid}" type="text" value="{$date}" readonly{$attrs}><span class="add-on" id="{$sid}"><i class="icon-calendar" style=""></i></span>
    </div>
HTML;
    $return = ''
      . $this->renderTag('input', array('type' => 'hidden', 'id' => $did, 'name' => $name."[day]",   'value' => $date > 0 ? date('d', strtotime($date)) : ''))
      . $this->renderTag('input', array('type' => 'hidden', 'id' => $mid, 'name' => $name."[month]", 'value' => $date > 0 ? date('m', strtotime($date)) : ''))
      . $this->renderTag('input', array('type' => 'hidden', 'id' => $yid, 'name' => $name."[year]",  'value' => $date > 0 ? date('Y', strtotime($date)) : ''))
      . $calendar
      . <<<HTML
<script type="text/javascript">
  $(function(){
    $('#{$calid}')
      .datepicker({weekStart:1})
      .on('changeDate', function(ev){
        var vdate = ev.date;
        $('#{$did}').val(vdate.getDate());
        $('#{$mid}').val(Number(vdate.getMonth()+1));
        $('#{$yid}').val(vdate.getFullYear());

        $('#{$calid}').datepicker('hide');
      })
    ;
  });
</script>
HTML;

    return $return;
  }
}
