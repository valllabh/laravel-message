# Laravel Messages

Message/Alerts Management for Laravel.

## Feature:
1. Handles Flash or Same-Request Messages
2. Handles Validation Messages
3. Grouping of Messages
4. Total control on message types like `errors, information, warnings`

## Setup:

in `app/config/app.php`

- Add Service provider
add `'Valllabh\Message\MessageServiceProvider'` in `providers` array

- Add Facade
add `'Message' => 'Valllabh\Message\Facade\Message'` in `aliases` array

- Publishing config file
`php artisan config:publish valllabh/message`

- Publishing views
`php artisan view:publish valllabh/message`

## Usage:

**Add Message**
```php
$message // array, MessageBag
$group // any string name of group like 'sign_up_messages', 'website_notices'
$flash // true if message needs to be flashed on next request
```
```php
Message::error( $message, $group = 'global', $flash = false );
Message::info( $message, $group = 'global', $flash = false );
Message::warning( $message, $group = 'global', $flash = false );
Message::success( $message, $group = 'global', $flash = false );
```

Message adding functions are nothing but the keys form the `config:types`

```
'types' => [
	'success' => array( 'class' => 'alt-success' ),
	'error' => array( 'class' => 'alt-danger' ),
	'warning' => array( 'class' => 'alt-warning' ),
	'info' => array( 'class' => 'alt-info' )
]
```

