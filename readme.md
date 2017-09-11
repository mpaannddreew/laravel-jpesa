## About this library

This is a simple/basic implementation of JPesa payments in laravel 5

## Actions supported
Note: You must have a valid JPesa account to use this library
- RECEIVING MONEY [Request payment]

For more information visit [JPesa](https://secure.jpesa.com/welcome.php)

## Installation
git https://github.com/mpaannddreew/laravel-jpesa.git

Register service provider
```php
FannyPack\JPesa\JPesaServiceProvider::class,
```
Register Facade
Register service provider
```php
'JPesa' => FannyPack\JPesa\JPesa::class,
```

After the service provider is registered run this command
```
php artisan vendor:publish
```
This command will create a copy of the library's config file and migrations into your code base <pre>jpesa.php</pre>

## Environment setup
The library loads configurations from the .env file in your application's root folder. These are the contents of jpesa.php
```
return [
    'username' => env('JPESA_USERNAME', ''),
    'password' => env('JPESA_PASSWORD', ''),
];
```

## Usage in context of your jpesa account
Using it with your models, add 
```php
namespace App;

use FannyPack\JPesa\Billable;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Billable;
}
```

Requesting payment from a registered mobile money number
```php
$response = JPesa::deposit($from_phone_number, $amount);
```
Information about a jpesa transaction
```php
$response = JPesa::info($transactionId);
```

## Bugs
For any bugs found, please email me at andrewmvp007@gmail.com or register an issue at [issues](https://github.com/mpaannddreew/laravel-jpesa/issues)
