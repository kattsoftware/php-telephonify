### `Response` class reference

All the methods from this class will return `$this`. So you can chain all the calls, like this:

```php
$response
    ->play('http://myserver.com/audio.mp3')
    ->play('http://anotherserver.com/anotheraudio.mp3')
    ->askForInput(MainMenuSelectionController::class);
```

* `say($text, $voice [, $async = false [, $loop = 1 ] ] )`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$text** (`string`) - text to be read in the call by the TTS engine 
    * **$voice** (`\KattSoftware\Telephonify\Drivers\Voices\Voice`) - voice & language used by the TTS engine when reading
    * **$async** (`bool`) - if user can send input and interrupt the reading
    * **$loop** (`int`) - how many times to read the text

    Reads the `$text`, by using the voice and language specified by the `$voice` instance. The voice instance is something particular to each provider's TTS engine and they are not interchangeable. Find more about what languages and voices you can use [here](tts.md).

    If `$async` is `true` and a `Response::askForInput()` is following, then the user will be able to interrupt the reading and send the input, without waiting for the reading to finish. If it's `false`, then the user must wait for the reading to finish completely. When `true`, any subsequent `Response::say()` or `Response::play()` must have `$async` set to `true` as well if you want to make multiple consecutive items async. If one entry with `$async` set to `false` follows, then all the previous async items will be changed to non-async (see the example).
    
    The `$loop` variable states how many times the reading to occur. When `0`, the reading will loop infinitely.

    A simple example: (assuming you are inside a controller):
    
    ```php
        // If you are using Twilio
        $response->say('Hello!', TwilioVoice::EN_US_JOANNA()); 
    
        // If you are using Nexmo
        $response->say('Hello!', NexmoVoice::EN_US_JOANNA()); 
    ```

    Async texts vs non-async ones example:
    
    ```php
        $voice = ...; // A voice instance, depending on your driver
    
        $response->say('Hello!', $voice, true); // <-- async = true
        $response->say('How are you?', $voice, true); // <-- async = true

        // Because the following line it's called with $async = false,
        // then the previous 2 items are not async anymore (they will
        // changed to $async = false by Telephonify).               
        $response->say('If you are fine, press 1.', $voice, false); // <-- async = false
    
        // Hence, the user will have to wait for all the previous readings
        // to finish before continuing. Only during the below reading,
        // he/she will be able to interrupt it and immediately send input.
        $response->say('If not, press 2.', $voice, true); // <-- async = true
        $response->askForInput(MenuSelectionController::class, 1);

    ```

* `play($audioUrl [, $async = false [, $loop = 1 ] ] )`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$audioUrl** (`string`) - absolute URL to an audio file
    * **$async** (`bool`) - if user can send input and interrupt the playback
    * **$loop** (`int`) - how many times to play the file

    Plays the audio file found at the `$audioUrl` URL. Each provider has its own recommendations and requirements about file's bitrate, size and other technical details. In this case, it's better to review their docs. Generally speaking, for all providers you can use both MP3 and WAV files.

    If `$async` is `true` and a `Response::askForInput()` is following, then the user will be able to interrupt the playback and send the input. If it's `false`, then the user must wait for the playback to finish completely. When `true`, any subsequent `Response::say()` or `Response::play()` must have `$async` set to `true` as well if you want to make multiple consecutive items async. If one entry with `$async` set to `false` follows, then all the previous async items will be changed to non-async (just as in the case of `Response::say()`).
    
    The `$loop` variable states how many times the playback to occur. When `0`, the reading will loop infinitely.

* `askForInput($returnTo [, $maxDigits = 1 [, $timeOut = 5 [, $endOnHashKey = false ] ] ] )`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$returnTo** (`string` or `array`) - fully qualified controller(s) class name(s)
    * **$maxDigits** (`int`) - max number of digits upon which the result is immediately sent
    * **$timeOut** (`int`) - seconds after the last callee's action to submit the result
    * **$endOnHashKey** (`bool`) - whether to submit the result immediately upon pressing `#`

    Puts your call in the mode for accepting input. The call will accept any numeric input until there is no activity for `$timeOut` seconds or until the `#` is pressed (if `$endOnHashKey` is set to `true`).
    Then, the flow will be passed back to Telephonify, and the execution will continue to `$returnTo`, which can be:
    * a fully qualified class name of an `IVRController` instance. Example:
    ```php
        $voice = ...; // A voice instance, depending on your driver
    
        $response->say('For sales department, press 1.', $voice);
        $response->say('For tech department, press 2.', $voice);
        $response->say('To speak to an operator, please press 9.', $voice);

        $response->askForInput(ProcessMenuOptionController::class, 1);
    ```

    * an associative array of possible routes, depending on the input; `*` is used as the default option if no value is matched. Example:
    
    ```php
        $voice = ...; // A voice instance, depending on your driver
    
        $response->say('For sales department, press 1.', $voice);
        $response->say('For tech department, press 2.', $voice);
        $response->say('To speak to an operator, please press 9.', $voice);

        $response->askForInput(
            [
                '1' => SalesDeptMenuController::class,
                '2' => TechDeptMenuController::class,
                '9' => OperatorRedirectController::class,
                '*' => InvalidOptionController::class,
            ],
            1
        );
    ```

    For the last example, if no routing could be performed for the next request (i.e. the `*` option wouldn't be provided), then `Application::processIncomingCall()` or `Application::processOutgoingCall()` (depending on your type call) will throw a `TelephonifyException`. In most of the cases, it's good to ensure that you provide the `*` option always.

    After returning, the input collecting is considered "timed-out" (`Request::hasTimedOut()`) if (a) the user didn't use all the `$maxDigits` and was idle for more than `$timeOut` seconds or (b) the user didn't use the `#` key after finishing typing (if `$endOnHashKey` was set to `true`). 

    While the user is typing, no audio output can be provided to the call (the call audio will be silenced).
    
    While the return value of this method it's still `$this`, **no** further action should be called from the `Response`, as `askForInput()` should be the last one from that particular request.

* `transferToPhoneNumber($toPhone, $fromPhone [, $ringingTimeout = 10 ] )`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$toPhone** (`string`) - phone number to be called by your provider
    * **$fromPhone** (`string`) - one of your own phone numbers which should be calling
    * **$ringingTimeout** (`int`) - how many seconds to ring until the `$toPhone` is considered busy

    Calls the `$toPhone` number and makes a direct conversation between the current caller and `$toPhone`. The phone number that will perform the calling is `$fromPhone`, and must one of your phone numbers that you own. Both numbers must be in the E.164 format (that is - full phone number, including the country code, but without the `+` sign - e.g. `14155551234`). The ringing will be heard by the current caller as well, and it will take `$ringingTimeout` seconds, after which `$toPhone` is considered busy.
    
* `joinConference($uniqueName, $startWhenEntering, $endWhenLeaving, $muted, [, $waitingMusicUrl = null ] )`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$uniqueName** (`string`) - unique conference name across your provider's application/project
    * **$startWhenEntering** (`bool`) - whether to stop the waiting music and start the conference once the current caller joins
    * **$endWhenLeaving** (`bool`) - whether to finish the conference once the current callers hangs up
    * **$muted** (`bool`) - whether the current caller is allowed to hear all other participants, but not to speak
    * **$waitingMusicUrl** (`string` or `null`) - absolute URL to an audio file to play while the conference is not started 

    Put the current caller to create/join a new/existing conference, uniquely identified by `$uniqueName` at your provider's application/project level. 
    For the first participant (which creates the conference), if he/she joins with `$startWhenEntering` set to `false`, then the conference is marked as not started. While not started, in the conference the audio file `$waitingMusicUrl` will be played infinitely (if provided, otherwise the audio output will be silenced while waiting).
    
    Using the same `$uniqueName` value, subsequent participants can join the same conference. If they will join with the `$startWhenEntering` set to `false`, they will hear the `$waitingMusicUrl` audio played as well (if provided). When the first participant with the `$startWhenEntering` set to `true` joins, the conference will start, the waiting music will stop playing, and all participants will be able to talk and hear each other.
    
    Any participant which joined with `$muted` set to `true`, will be able only to hear others, and his/her audio input will be muted.

    If any participant joins with the `$endWhenLeaving` set to `true`, then if he/she hangs up, then the entire conference will be stopped, for all participants.
    
    It is usually recommended that `$uniqueName` shouldn't contain any sensitive data, such as personal data information of an individual.

    Example:
    ```php
    // Customer initial call: requests to speak to someone from sales dept
    $response->joinConference(
        'sales-aabbccddeeff123456', // unique conference name
        false, // don't start when entering, play the waiting music
        true, // if he/she leaves the call, the conference will end
        false, // not muted, will be able to hear and speak to others
        'http://myserver.com/waiting_callcenter.mp3' // the waiting audio to be played
    );

    // ... on another call:
    // The operator own call: the operator is ready to join the conference
    $response->joinConference(
        'sales-aabbccddeeff123456', // unique conference name
        true, // start the conference when the operator enters
        true, // if the operator leaves the call, the conference will end
        false // not muted, will be able to hear and speak to others
    );

    // now the customer and the operator are speaking one to each other.
    
    // ... on another call:
    // The manager own call: the manager joins the conference to just listen
    // to the conversation:
    $response->joinConference(
        'sales-aabbccddeeff123456', // unique conference name
        true, // the conference should have been started by now 
        false, // if the manager leaves the call, the conference won't end
        true // muted, will be able only to hear others, and not to speak to them
    );
    ```
* `redirect($controllerClass)`
  * Return type: `\KattSoftware\Telephonify\Response`
  * Parameters: 
    * **$controllerClass** (`string`) - fully qualified class name of an `IVRController`

    Passes the execution flow to a new `IVRController` class. If the provided `$controllerClass` is not a fully qualified class name, then `Application::processIncomingCall()` or `Application::processOutgoingCall()` (depending on your type call) will throw a `TelephonifyException`. The passing is not done on the same request, but rather on another, new one.
