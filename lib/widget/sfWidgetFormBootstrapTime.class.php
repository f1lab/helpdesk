
<?php
/**
 * sfWidgetFormBootstrapTime represents a time widget using bootstrap framework.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Saritskiy R.G. <antismok@mail.ru>
 * @version    SVN: $Id: sfWidgetFormBootstrapTime.class.php 2013-01-24 17:05:33Z  $
 */
  class sfWidgetFormBootstrapTime extends sfWidgetForm
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('template.javascript','
      <script type="text/javascript">
        $(\'.timepicker-default\').timepicker({defaultTime:\'false\', showMeridian:false});
      </script>
    ');
  }


  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value)) {
      $value = strtotime(implode(':', $value));
    } elseif (!is_int($value)) {
      $value = strtotime($value);
    } 
    return  
    '<div class="input-append bootstrap-timepicker-component">
      <input name="'.$name.'" type="text" class="timepicker-default input-small" value="'.$value.'">
      <span class="add-on">
        <i class="icon-time"></i>
      </span>
    </div>
    <script type="text/javascript">
        $(\'.timepicker-default\').timepicker({defaultTime:\'false\', showMeridian:false});
      </script>';
  }
}
?>