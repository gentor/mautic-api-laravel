Mautic API
==========

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

Usage
-----

* Creating an item

```php
// Create contact
$fields = Mautic::contacts()->getFieldList();

$data = array();

foreach ($fields as $field) {
    $data[$field['alias']] = $_POST[$field['alias']];
}

// Set the IP address the contact originated from if it is different than that of the server making the request
$data['ipAddress'] = $ipAddress;

// Create the contact
$response = Mautic::contacts()->create($data);
$contact = $response[Mautic::contacts()->itemName()];
```

```php
// Create company
$fields = Mautic::companies()->getFieldList();

$data = array();

foreach ($fields as $field) {
    $data[$field['alias']] = $_POST[$field['alias']];
}

// Create the company
$response = Mautic::companies()->create($data);
$contact = $response[Mautic::companies()->itemName()];
```

```php
// Create contact with companies
$contact = Mautic::contacts()->createWithCompanies([
    'firstname' => 'Mautic',
    'lasttname' => 'Contact',
    'email' => 'contact@email.com',
    'companies' => [
        [
            'companyname' => 'Company 1',
        ],
        [
            'companyname' => 'Company 2',
        ],
    ],
]);
```

* Edit an item

```php
$updatedData = array(
    'firstname' => 'Updated Name'
);

$response = Mautic::contacts()->edit($contactId, $updatedData);
$contact = $response[Mautic::contacts()->itemName()];

// If you want to create a new contact in the case that $contactId no longer exists
// $response will be populated with the new contact item
$response = Mautic::contacts()->edit($contactId, $updatedData, true);
$contact = $response[Mautic::contacts()->itemName()];
```

* Delete an item

```php
$response = Mautic::contacts()->delete($contactId);
$contact = $response[Mautic::contacts()->itemName()];
```

* Error handling

```php
// $response returned by an API call should be checked for errors
$response = Mautic::contacts()->delete($contactId);

if (isset($response['error'])) {
    echo $response['error']['code'] . ": " . $response['error']['message'];
} else {
    // do whatever with the info
}
```

Documentation
-------------

[Mautic API Library](https://github.com/mautic/api-library)

[Mautic API Docs](https://developer.mautic.org/#rest-api)
