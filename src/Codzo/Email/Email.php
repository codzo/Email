<?php

namespace Codzo\Email ;

use Codzo\Email\Mailer\IMailer as IMailer;

class Email
{
    protected $config;
    protected $mailer;
    protected $receipts = array();
    protected $subject  = '';
    protected $body = '';
    protected $attachments = array();

    public function __construct(&$config)
    {
        $this->config = $config;
        $mailer_name = $config->get('email.mailer');
        $mailer_classname = '\\' . __NAMESPACE__ . '\\Mailer\\' . $mailer_name;
        if (class_exists($mailer_classname)) {
            $this->mailer = new $mailer_classname($config);
        } else {
            throw new \Exception('Invalid mailer ' . $mailer_name);
        }
    }

    public function setMailer(IMailer $mailer)
    {
        $this->mailer = $mailer;
        return $this;
    }

    public function getReceipts()
    {
        return $this->receipts;
    }

    public function setReceipts(array $receipts)
    {
        $this->receipts = $receipts;
        return $this;
    }

    public function addReceipt($receipt)
    {
        $this->receipts[] = $receipt;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    public function clearAttachments()
    {
        $this->attachments = array();
        return $this;
    }

    public function send()
    {
        $this->mailer->send(
            $this->receipts,
            $this->subject,
            $this->body,
            $this->attachments
        );
    }
}
