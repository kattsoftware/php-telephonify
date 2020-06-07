### The core concepts of Telephonify

In the previous example, we created a simple Telephonify application and tested it to see that it works. 

Let's see what concepts you've already came across:

#### The `IVRController` base class

Your Telephonify application code will live in classes which must always extend the `\KattSoftware\Telephonify\IVRController` base class. By doing so, you will have a method to implement, the `run()` method, which holds the partial or entire logic of the call flow. The `run()` method has 2 parameters:

*  `\KattSoftware\Telephonify\Request $request` - contains information about the ongoing call. Read more about it [here](request.md).
* `\KattSoftware\Telephonify\Response $response` - controls the actual call flow, by creating a stack of actions to be executed. Read more about it [here](response.md).

So, your call flow logic will work by calling the `Response`'s methods in order to sequentially perform certain actions (such as reading a text, playing an audio file, asking for input etc.) and also by using the information from the `Request` object about the current call (such as who called, the user's input etc).

It's important to understand that the methods you are calling on the `Response` instance are **not** synchronous. They stack on an internal array immediately as soon as they are called, and when the controller's code ends, they will be iterated by Telephonify to build a provider-specific response.

#### The `Application` instance

When you want to process any webhook incoming requests, you will have to use the public methods of an `Application` instance.
* For the "Answer URL" requests, you will use `Application::processIncomingCall()` (for incoming calls) and `Application::processOutgoingCall()` (for outgoing calls). These methods will execute your controllers' code and return to the provider the response for controlling the call flow. These methods will set any required HTTP response headers, as well as the response output, so there is nothing left for you to do. 
* For the "Event URL" requests, you will use the `Application::processEventRequest()` method. This will not output anything, nor execute controllers' code, as it doesn't control th call flow, but just execute your callbacks, defined per event situation for a call (e.g. when the call ends, an event request will be issued to this URL).

So generally speaking, if you will use only incoming calls, you will need 2 endpoints: an "Answer URL" which handles the execution to `Application::processIncomingCall()` and an "Event URL", which uses `Application::processEventRequest()`. If your application will make outgoing calls as well, an additional endpoint will be required, where `Application::processOutgoingCall()` will be called.

Note: it's not something mandatory to process events! As long as your "Event URL" will respond with a blank HTTP response, with a response code of 200, then everything should be fine. In such cases, you don't even need to call `Application::processEventRequest()`, or anything from Telephonify.

When you create an `Application` instance, it requires 3 parameters:
* a driver instance (a class implementing `\KattSoftware\Telephonify\Drivers\DriverInterface`)
* a session storage instance (a class implementing `\KattSoftware\Telephonify\SessionStorage\SessionStorageInterface`)
* (optional) a callable which should be used for creating the controller instances, instead of just applying `new` to them. 

The first one is obvious. You already created one such driver, based on your provider of voice services. 

Additionally, Telephonify needs some sort of session storage for each call, as a call can span over many HTTP requests made to your "Answer URL" endpoint. As such, this library will always need a way to match the current incoming HTTP request to an existing call state, so it will be able to follow to the subsequent controller, based on the call flow.

Such session storage implementations are implementing the `SessionStorageInterface` interface, and they have a few methods to declare. For convenience, this library comes already with one session storage implementation, and that is `LocalFiles`. The class needs on its constructor an absolute path, for storing locally the session files.

While the examples from this documentation use the `sys_get_temp_dir()` path, it may not be the best place to store those files in a production environment, as they contain sensitive data, such as participants' phone numbers and user's input. Also, the temp dir of a system can get emptied at any time.

The third parameter stands for a callable which Telephonify should use, instead of attempting to issue a `new` on any controller class name. This is useful if you are using dependency injection, or any other class management mechanism. As you already seen, this library works only by providing to its input fully qualified class names, and not instances of `IVRController`.

For example:

```php
$driver = ...;
$sessionStorage = ...;

$controllerFactory = function ($controllerClassName) {
    return MyClassFactory::make($controllerClassName);
};

new Application($driver, $sessionStorage, $controllerFactory);
```

Next, you will learn how to use the `CallManager` to modify existing calls or create outgoing calls.
