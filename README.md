## PHP Telephonify

Telephonify is a PHP library which helps you to create telephone interactive menus (IVRs), automated attendants, call-centers and many more!

If you ever wanted to create one of those popular over-the-phone assistants, menus (and even a call-center!), then this library is for you.

This library can be used for the following providers of such services:
* [Twilio](https://twilio.com)
* [Nexmo](https://nexmo.com) (now known as Vonage)

### Installation

This library can be installed by using [composer](https://getcomposer.org/):

```
composer install kattsoftware/php-telephonify
```

### Getting started

You can get started using Telephonify [here](docs/getting_started_01.md).

### About

This library tries to unify more providers of such services under one, unique usage. While all the providers were thoroughly tested and checked for corner cases, as this library is just new, there could be very rare situations where functionality and/or features' specific behaviors work slightly different than expected. If you find any such cases, kindly please report them as issues on this repo. You are also more than welcome to submit your own PRs!

To do:
* unit tests
* a tool for testing locally the implementation (without making the actual calls)
* recording feature (for human-to-human dialog parts of the call)
* implement more events across all providers

### License

The library is licensed under the MIT License (MIT). See the `LICENSE` file for more information.

Copyright (c) KattSoftware dev team.
