### Security considerations

The webhook URLs (Answer URL, Event URL) you are sharing with your provider are public. While using only HTTPS and hard-to-find URLs may be a good measure, it is not enough for an application with a strong security.

#### Securing URLs when using Twilio

When using Twilio, each incoming request from Twilio has a signature attached to one of its HTTP headers. By using the auth token from your Twilio account, call the driver's `enableRequestSignatureValidation($authToken)` method and Telephonify will check every request for its authenticity and stop if there's any problem:

```php
<?php 

use KattSoftware\Telephonify\Application;
use KattSoftware\Telephonify\Drivers\Twilio;
use KattSoftware\Telephonify\Exceptions\TelephonifyException;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;
use KattSoftware\Telephonify\SessionStorage\LocalFiles;

$driver = new Twilio();
$sessionStorage = new LocalFiles(sys_get_temp_dir());

$driver->enableRequestSignatureValidation('<-- your auth token -->');

$app = new Application($driver, $sessionStorage);

// ...

try {
    $app->processIncomingCall(
       'http://myserver.com/telephonify_server.php',
       'http://myserver.com/telephonify_server_events.php',
        MyController::class
    );
} catch (TelephonifyException $e) {
    if ($e->getPrevious() instanceof SecurityViolationException) {
        // The request may be forged by an attacker!
    }
}
``` 

The same code logic applies for `Application::processOutgoingCall()` and `Application::processOutgoingCall()` as well.

#### Securing URLs when using Nexmo

At this moment, Nexmo doesn't have a built-in security mechanism for its webhook requests, unfortunately. Until they will improve this, the only way to check for a request authenticity is to call on every request their API Client, querying for the current call ID and trying to see if some details (calling number, caller number) are matching with the sent values in the request. While this will add some extra processing time to every request, it is still important to enable this mechanism to prevent request forgery attempts.

First, install their PHP client library:

```
composer require nexmo/client
```

Secondly, create a new instance of their client, assign it to the Nexmo driver, and enable this feature by calling `enforceCallLookUpSecurity()` on the driver. Additionally, you will need the private key file from your Nexmo application stored somewhere, and the application ID.

```php
<?php 

use KattSoftware\Telephonify\SessionStorage\LocalFiles;
use Nexmo\Client;
use Nexmo\Client\Credentials\Container;
use Nexmo\Client\Credentials\Keypair;
use KattSoftware\Telephonify\Application;
use KattSoftware\Telephonify\Drivers\Nexmo;
use KattSoftware\Telephonify\Exceptions\TelephonifyException;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;

$keypair = new Keypair(
    file_get_contents('/path/to/your/nexmo/applicaton/private_key_file'),
    '<-- your Nexmo Application ID here -->'
);

$client = new Client(new Container($keypair));

$driver = new Nexmo();
$driver->setNexmoClient($client);
$driver->enforceCallLookUpSecurity();

$sessionStorage = new LocalFiles(sys_get_temp_dir());

$app = new Application($driver, $sessionStorage);

// ...

try {
    $app->processIncomingCall(
       'http://myserver.com/telephonify_server.php',
       'http://myserver.com/telephonify_server_events.php',
        MyController::class
    );
} catch (TelephonifyException $e) {
    if ($e->getPrevious() instanceof SecurityViolationException) {
        // The request may be forged by an attacker!
    }
}
``` 

The same code logic applies for `Application::processOutgoingCall()` as well.
