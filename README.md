# Brevo (former SendInBlue) Extension for Yii 2

This extension provides a [Brevo](https://www.brevo.com/) mail solution for [Yii framework 2.0](http://www.yiiframework.com).

[![Latest Stable Version](http://poser.pugx.org/schuetzenlust/yii2-brevo/v)](https://packagist.org/packages/schuetzenlust/yii2-brevo) [![Total Downloads](http://poser.pugx.org/schuetzenlust/yii2-brevo/downloads)](https://packagist.org/packages/schuetzenlust/yii2-brevo) [![Latest Unstable Version](http://poser.pugx.org/schuetzenlust/yii2-brevo/v/unstable)](https://packagist.org/packages/schuetzenlust/yii2-brevo) [![License](http://poser.pugx.org/schuetzenlust/yii2-brevo/license)](https://packagist.org/packages/schuetzenlust/yii2-brevo) [![PHP Version Require](http://poser.pugx.org/schuetzenlust/yii2-brevo/require/php)](https://packagist.org/packages/schuetzenlust/yii2-brevo)

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