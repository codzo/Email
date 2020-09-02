# Email
Email handler.

This package handles PHPMailer with Codzo/Config settings.

## Installation
```composer
composer require "codzo/email:dev-master"
```

## Usage

```php
$email = new \Codzo\Email\Email(new \Codzo\Config());
```

## Mailer Support
Now only supports SMTPMailer.

All Mailer needs to implement IMailer interface.
