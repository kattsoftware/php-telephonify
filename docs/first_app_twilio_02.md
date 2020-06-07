### Creating your first IVR application with Telephonify and Twilio

When you are accepting incoming calls (or create outgoing calls), the library will use the code you write inside a PHP class, called the _controller_. Based on the input (what number is calling, the input from his/her phone keyboard, etc.), known as the _request_, you will create a _response_, which is a collection of actions that will control the flow of the call. Examples of such actions are:
* using a TTS (text-to-speech) voice you can speak texts to your user;
* playing an MP3 file (waiting music, pre-recorded speech, etc.)
* asking for user's input (asking the option from the menu for navigating to it, a numeric password, a phone number, etc.)
* transferring the user to a phone number, speaking directly to a person;
* and more!
 
 Let's start by creating a class which extends `KattSoftware\Telephonify\IVRController`, naming it `SayHelloController`. This is essential for any Telephonify application, as the code/logic for your calls will always live in classes which extend the `IVRController` class, more exactly in the `run()` method:
 
 ```php
<?php

use KattSoftware\Telephonify\IVRController;
use KattSoftware\Telephonify\Request;
use KattSoftware\Telephonify\Response;
use KattSoftware\Telephonify\Drivers\Voices\TwilioVoice;

class SayHelloController extends IVRController
{
    public function run(Request $request, Response $response)
    {
        $text = 'Hello! Welcome, ' . $request->getCallingNumber() . '!';
        $voice = TwilioVoice::EN_US_JOANNA();

        $response->say($text, $voice);
    }
}

```

When somebody will call your number, they will hear the text `Hello! Welcome, <number>!`, where the `<number>` is the phone number in the E.164 format (that is - full phone number, including the country code, but without the `+` sign - e.g. `14155551234`). The text is read by a TTS (text-to-speech) voice, called `Joanna`, a female voice which can read any text in the English (US) language. Twilio offers many TTS voices for different languages, and they will be covered later.

### Creating the endpoint for incoming calls

OK, so let's test what you've just created. 
It is assumed that you already have a phone number bought from Twilio.

The way this library works for incoming calls is like this: you create a public endpoint URL, Twilio will make a `POST` request to that URL and after that, Telephony will control the entire call flow by using one or more `IVRController` instances (in our case, we have only one controller (which is also the starting one), and that is `SayHelloController`).

The most basic implementation of a such endpoint is this:

```php
<?php

use KattSoftware\Telephonify\Application;
use KattSoftware\Telephonify\Drivers\Twilio;
use KattSoftware\Telephonify\EventsManager;
use KattSoftware\Telephonify\SessionStorage\LocalFiles;
use KattSoftware\Telephonify\Exceptions\TelephonifyException;
// include here the SayHelloController.php file...

$driver = new Twilio();
$sessionStorage = new LocalFiles(sys_get_temp_dir());

$app = new Application(
    $driver,
    $sessionStorage
);

$endpointUrl = 'http://myserver.com/telephonify-server.php';
$eventUrl = 'http://myserver.com/telephonify-server.php?eventUrl=1';

try {
    // Is this an event request - i.e. containing info about the call?
    if (isset($_GET['eventUrl'])) {
        // (will be discussed later)
        $app->processEventRequest(new EventsManager(), $eventUrl);
    } else {
        $startingController = SayHelloController::class;        

        $result = $app->processIncomingCall($endpointUrl, $eventUrl, $startingController);   
    }
} catch (TelephonifyException $e) {
    // An error has occurred, do the logging, alerting etc.
}
```

Save that as `telephonify-server.php` and make it publicly available.

If you have a server online, making it public shouldn't be a problem, however, if you want to test it from your local machine, use a HTTP tunneling tool, such as [ngrok](https://ngrok.com/) or [Serveo](https://serveo.net/).

Let's assume that the file you just created is available from `http://myserver.com/telephonify-server.php`. Then, head over Programmable Voice > Numbers > Manage numbers > select your number from the listing, then fill in the following fields:

* **A call comes in**: set it as _HTTP POST_ and the value to `http://myserver.com/telephonify-server.php`
* **Call status changes**: set it as _HTTP POST_ and the value to `http://myserver.com/telephonify-server.php?eventUrl=1`. 

That's it! You have just created your first automatic phone system (known also as IVR) by using Telephonify! 

Just call your Twilio phone number and you will hear `Hello! Welcome, <your phone number>!`. Then the call will finish.

Now let's see the explanations of a few things that you encountered, but not explained yet, in the [next part](core_concepts_03.md).

Other pages:
* [`Request` class reference](request.md)
* [`Response` class reference](response.md)
* [Reading text by using the TTS](tts.md)
* [Security considerations](security.md)
