<?php

class Email
{
  static public function send($to, $subject, $text) {
    $enabled = sfConfig::get('app_mailgun_enabled', false);
    $key = sfConfig::get('app_mailgun_key', null);
    $domain = sfConfig::get('app_mailgun_domain', null);
    $from = sfConfig::get('app_mailgun_from', null);

    if (!$enabled or !$key) {
      return false;
    }

    $mgClient = new Mailgun\Mailgun($key);

    return $mgClient->sendMessage($domain, [
      'from'    => $from,
      'to'      => $to,
      'subject' => $subject,
      'text'    => $text,
    ]);
  }
}
