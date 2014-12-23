<?php

class Sms
{
  static public final function send(array $to, $text) {
    $enabled = sfConfig::get('app_sms_enabled', false);
    $key = sfConfig::get('app_sms_key', null);
    $from = sfConfig::get('app_sms_from', null);

    if (!$enabled or !$key or count($to) < 1) {
      return false;
    }

    return file_get_contents('http://sms.ru/sms/send?api_id=' . $key . '&to='
      . implode(',', $to) . '&from=' . $from . '&text='
      . urlencode($text)
    );
  }
}
