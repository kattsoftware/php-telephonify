# Getting started with PHP Telephonify

During this tutorial, you will be able to see and understand how the Telephonify library works. 
It's assumed that you already installed the library via the Composer package manager and already included its autoloader.

This library works with the following providers:
* [Twilio](https://twilio.com)
* [Nexmo](https://nexmo.com) (now known as Vonage)

For the one you will use, ensure that you already have an account registered with them.

There are 2 types of calls which you can operate through this library:
* **incoming calls**: you bought a phone number from your provider and now you want to programmatically accept incoming calls to that number, from outside - e.g. from your customers. You can play waiting music, read some information, accept user's input and much more.
* **outgoing calls**: they are the same as the incoming calls, but are started by your application, upon calling a method from this library, and the recipient must answer the call in order launch the flow of actions.

To continue with the tutorial, please choose which provider you are going to use:

* [Twilio](first_app_twilio_02.md)
* [Nexmo](first_app_nexmo_02.md)
