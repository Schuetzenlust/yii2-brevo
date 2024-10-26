# Brevo (former SendInBlue) Extension for Yii 2

This extension provides a [Brevo](https://www.brevo.com/) mail solution for [Yii framework 2.0](http://www.yiiframework.com).

[![Latest Stable Version](https://poser.pugx.org/Schuetzenlust/yii2-brevo/v/stable)](https://packagist.org/packages/Schuetzenlust/yii2-brevo)
[![Total Downloads](https://poser.pugx.org/Schuetzenlust/yii2-brevo/downloads)](https://packagist.org/packages/Schuetzenlust/yii2-brevo)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require Schuetzenlust/yii2-brevo
```

## Usage

To use this extension, simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'mailer' => [
            'class' => 'schuetzenlust\brevo\Mailer',
            'apikey' => 'your-api-key',
        ],
    ],
];
```

You can then send an email as follows:

```php
Yii::$app->mailer->compose('contact/html', ['contactForm' => $form])
    ->setFrom('from@domain.com') // or ->setFrom(["name" => "Your name", "email" => "yourmail@example.com"])
    ->setTo($form->email) // or ->setTo(["name" => "Your name", "email" => "yourmail@example.com"]) 
    ->setSubject($form->subject)
    ->send();
```

### Batch Sending

Yet to be implemented