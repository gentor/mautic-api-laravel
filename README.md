Mautic
======

Wrapper on the Mautic API for Laravel 4/5.x

Installation
------------

Installation using composer:

```
composer require gentor/mautic-api-laravel
```


Add the service provider in `config/app.php`:

```php
Gentor\Mautic\MauticServiceProvider::class,
```

Add the facade alias in `config/app.php`:

```php
Gentor\Mautic\Facades\Mautic::class,
```

Configuration
-------------

Change your default settings in `app/config/mautic.php`:

```php
return [
    'baseUrl' => env('MAUTIC_API_URL'),
    'userName' => env('MAUTIC_API_USERNAME'),
    'password' => env('MAUTIC_API_PASSWORD'),
];
```


Documentation
-------------

[Mautic API](https://developer.mautic.org/#rest-api)

