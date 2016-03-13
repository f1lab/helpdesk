<?php

class Email
{
  static public function send($to, $subject, $text, array $files = []) {
    $enabled = sfConfig::get('app_mailgun_enabled', false);
    $key = sfConfig::get('app_mailgun_key', null);
    $domain = sfConfig::get('app_mailgun_domain', null);
    $from = sfConfig::get('app_mailgun_from', null);

    if (!$enabled or !$key) {
      return false;
    }

    if ($to === '' or is_array($to) && count($to) < 1) {
      return false;
    }

    $mgClient = new Mailgun\Mailgun($key);

    if (!is_array($to)) {
      $to = [$to];
    }

    $toImploded = implode(',', $to);
    $variables = array_combine($to, array_map(function($email) {
      return ['md5' => md5($email)];
    }, $to));
    $variablesJson = json_encode($variables);

    $result = false;
    try {
      $result = $mgClient->sendMessage($domain, [
        'from'    => $from,
        'to'      => $toImploded,
        'subject' => $subject,
        'text'    => $text,
        'recipient-variables' => $variablesJson,
      ], ['attachment' => $files]);
    } catch (Exception $e) {
      file_put_contents(
        sfConfig::get('sf_upload_dir') . DIRECTORY_SEPARATOR . 'email-bugs' . DIRECTORY_SEPARATOR . 'email@' . time()
        , print_r([$e->getMessage(), $to, $subject, $text], true)
      );
    }

    return $result;
  }

  static public function generateSubject(Ticket $ticket) {
    return 'Re: [F1LAB-HLPDSK-' . $ticket->getId() . '] ' . $ticket->getName();
  }
}
