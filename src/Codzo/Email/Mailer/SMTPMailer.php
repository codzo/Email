<?php

namespace Codzo\Email\Mailer ;

use Codzo\Config\Config ;
use Codzo\Email\Mailer\IMailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class SMTPMailer implements IMailer
{

    protected $mailer ;

    public function __construct(Config $config)
    {
        //Create a new PHPMailer instance
        $mailer = $this->mailer = new PHPMailer();
        $mailer->CharSet = 'UTF-8';
        $mailer->Encoding = 'base64';

        //Tell PHPMailer to use SMTP
        $mailer->isSMTP();

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $mailer->SMTPDebug = SMTP::DEBUG_OFF;

        //Set the hostname of the mail server
        $mailer->Host = $config->get('smtp.host');

        // use $mailer->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mailer->Port = $config->get('smtp.port');

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        //Whether to use SMTP authentication
        $mailer->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mailer->Username = $config->get('smtp.username');

        //Password to use for SMTP authentication
        $mailer->Password = $config->get('smtp.password');

        //Set who the message is to be sent from
        $mailer->setFrom($config->get('smtp.from.email'), $config->get('smtp.from.name'));
    }

    public function getMailer()
    {
        return $this->mailer;
    }

    public function send(array $receipts, $subject, $body, $attachments = array())
    {
        $mailer = $this->mailer;

        //Set who the message is to be sent to
        foreach ($receipts as $to) {
            $mailer->addAddress($to);
        }

        //Set the subject line
        $mailer->Subject = $subject;

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mailer->msgHTML($body);

        //Attach an image file
        foreach ($attachments as $attachment) {
            $mailer->addAttachment($attachment);
        }

        //send the message, check for errors
        if (!$mailer->send()) {
            // TODO need log here
            return false;
        } else {
            return true;
        }
    }
}
