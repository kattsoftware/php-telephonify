### `Request` class reference

* `getCallerNumber()`
  * Return type: `string` or `null`

    Returns the phone number that initiated the call, in the E.164 format (that is - full phone number, including the country code, but without the `+` sign - e.g. `14155551234`). For an outgoing call created with the `CallManager`, this will hold the value of your number, which initiates the call.
 
    If the type of call is incoming and the caller [disabled its identity](https://en.wikipedia.org/wiki/Caller_ID#Disabling_caller_ID_delivery) (thus, by making an anonymous call), then this method will return `null`.
    
* `getCallingNumber()`
  * Return type: `string`

    Returns the phone number that answered the call, in the E.164 format (e.g. `14155551234`). For an outgoing call, this will hold the value of the number that is being called by your number.
 
* `getInput()`
  * Return type: `string` or `null`
  
    Provides the numeric input that was given by the user upon calling `Response::askForInput()`. If the current request comes after a previous one which didn't use `Response::askForInput()` (i.e. no input was actually asked for), then the return value will be `null`. Additionally, if the user failed to provide any input and the timeout was reached, then this method will return `null`.

* `hasTimedOut()`
  * Return type: `bool`

    If the previous request during the current call ended by using `Response::askForInput()`, a timeout value (in seconds) was provided. If the user stopped providing any input for such a period of time, the call flow will be passed back to Telephonify and this method will return `true` in the current request, no matter if there is any input collected or not. If the user managed to give an input and reached the maximum number of digits or pressed the `#` key (if such option was used), then this method will return `false`.

    If the previous request didn't end by using `Response::askForInput()` (i.e. no input was actually asked for), then this method will also return `false`.

    Example usage (assuming you are inside a controller):
    
    ```php
        $voice = ...; // A voice instance, depending on your provider
    
        if ($request->getInput() === null) {
            if ($request->hasTimedOut()) {
                $response->say('Your input was expected, please try again.', $voice); 
            } else {
                $response->say('Huh? I never asked for an input, to be honest. What are you doing in this controller?', $voice); 
            }
        } else {
            $response->say('You entered: ' . $request->getInput() . '.', $voice);
    
            if ($request->hasTimedOut()) {
                $response->say(
                    'You also did not enter the required number of digits, nor pressed the # key (if the option was used).',
                    $voice
                );
            }
        }
    ```

* `hasInput()`
  * Return type: `bool`
  
    If the previous request during the current call ended by using `Response::askForInput()`,  then if there is any user input collected, this method will return `true`, and `false` otherwise. If the current request comes after a previous one which didn't use `Response::askForInput()` (i.e. no input was actually asked for), then the return value will be `false`.

    This method is sugar for `$request->getInput() !== null`.

* `getCallId()`
  * Return type: `string`
  
    Returns the current call unique ID, as given by your provider. Can contain alphanumeric characters, but also special characters (such as dashes).

* `getFrom()`
  * Return type: `string` or `null`
  
    Alias for `$request->getCallerNumber()`.
    
* `getTo()`
  * Return type: `string`
  
    Alias for `$request->getCallingNumber()`.
