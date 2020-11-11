# Zoho Desk for Laravel

Zoho Desk Laravel provides an oauth2 client and sdk for Zoho Desk api.

## Requirements

- Laravel 6+
- PHP 7.2.5+

## Installation

Install via using composer

```bash
composer require ayles-software/laravel-zoho-desk
```

## Setup

Publish the config.

```bash
php artisan vendor:publish --provider="AylesSoftware\ZohoDesk\ServiceProvider"
```

You now need to populate the `config/zoho-desk.php` file with the credentials for 
your Zoho integration. You can create new API keys
[here](https://api-console.zoho.com.au/). 
Add the following variables to your `.env` file.

```
ZOHO_DESK_REDIRECT_URL=
ZOHO_DESK_CLIENT_ID=
ZOHO_DESK_CLIENT_SECRET=
ZOHO_DESK_ACCESS_TYPE=
ZOHO_DESK_ORGANISATION_ID=
ZOHO_DESK_BASE_URL=
```

The config file also contains scopes for Zoho. 
The `access_type=offiline` scope is required to obtain a new access token. Access tokens **expire after 60 minutes**.
For more information on scopes try the [documentation](https://desk.zoho.com.au/support/APIDocument.do#OauthTokens#OAuthScopes).  

#### Migrations

This package uses `ZohoDeskAccess` model to manage access tokens. Run the migration to create this table.
```bash
php artisan migrate
```

## OAuth flow

First you must authorize the application, this will require a controller and route to be setup. 
Once the application has been authorized then access to Zoho is self managed within this package. 
Once the access token expires, a new one will be request as needed.

`ZohoOAuth::class` will provide the OAuth flow and regeneration of the access token.

Example of Controller for authorization.
```php
<?php

namespace App\Http\Controllers;

use AylesSoftware\ZohoDesk\ZohoOAuth;

class ZohoOAuthController extends Controller
{
    public function __invoke(ZohoOAuth $zohoOAuth)
    {
        return $zohoOAuth->flow();
    }
}
``` 

## Usage

```php
use AylesSoftware\ZohoDesk\Facades\ZohoDesk;

# create a ticket
# for more options see https://desk.zoho.com.au/support/APIDocument.do#Tickets#Tickets_Createaticket
$response = ZohoDesk::createTicket([
    'subject' => 'Support message - Test user',
    'description' => 'This is the body of the ticket',
    'departmentId' => '0000000000000000', // this can be found within ZohoDesk
    'email' => 'contact@email.com',
    'contact' => [
        'email' => 'contact@email.com',
        'firstName' => 'Benjamin',
    ],
]);
```

For more information see the [API Documentation](https://desk.zoho.com.au/support/APIDocument.do). 

## License

Zoho Desk Laravel is open-sourced software licensed under the [MIT license](https://github.com/ayles-software/xero-laravel/blob/master/LICENSE.md).
