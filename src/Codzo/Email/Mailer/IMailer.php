<?php

namespace Codzo\Email\Mailer ;

interface IMailer
{
    public function getMailer();
    public function send(array $receipt, $subject, $body, $attachments = null);
}
