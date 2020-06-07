<?php

namespace KattSoftware\Telephonify;

use KattSoftware\Telephonify\Actions\AskForInput;
use KattSoftware\Telephonify\Actions\JoinConference;
use KattSoftware\Telephonify\Actions\Play;
use KattSoftware\Telephonify\Actions\Redirect;
use KattSoftware\Telephonify\Actions\SayText;
use KattSoftware\Telephonify\Actions\TransferToPhoneNumber;
use KattSoftware\Telephonify\Drivers\DriverInterface;
use KattSoftware\Telephonify\Drivers\Response\AnswerResponseBuilderInterface;
use KattSoftware\Telephonify\Exceptions\SecurityViolationException;
use KattSoftware\Telephonify\Exceptions\TelephonifyException;
use KattSoftware\Telephonify\Result\OngoingCall;
use KattSoftware\Telephonify\SessionStorage\SessionStorageException;
use KattSoftware\Telephonify\SessionStorage\SessionStorageInterface;
use UnexpectedValueException;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class Application
{
    /** @var DriverInterface */
    private $driver;

    /** @var SessionStorageInterface */
    private $sessionStorage;

    /** @var callable|null */
    private $controllerFactory;

    /**
     * Application constructor.
     * @param DriverInterface $driver
     * @param SessionStorageInterface $sessionStorage
     * @param callable|null $controllerFactory
     */
    public function __construct(DriverInterface $driver, SessionStorageInterface $sessionStorage, callable $controllerFactory = null)
    {
        $this->driver = $driver;
        $this->sessionStorage = $sessionStorage;
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * Incoming calls method to be called when processing such call.
     * This method has side effects: it will echo the answer and will
     * also set the headers required by the driver's response.
     *
     * @param string $endpointUrl The absolute URL of the Answer URL endpoint
     * @param string $eventUrl The absolute URL of the Event URL endpoint
     * @param string $startingControllerClass Fully qualified class name, which should be the starting IVRController point
     * @return OngoingCall
     * @throws TelephonifyException
     */
    public function processIncomingCall($endpointUrl, $eventUrl, $startingControllerClass)
    {
        try {
            $request = $this->driver->createRequest($endpointUrl);
        } catch (SecurityViolationException $e) {
            throw new TelephonifyException('Security violation (please see the other exception)', 0, $e);
        } catch (UnexpectedValueException $e) {
            throw new TelephonifyException('Invalid request: ' . $e->getMessage(), 0, $e);
        }

        $response = new Response();

        try {
            $savedState = $this->sessionStorage->readState($request->getCallId());
            $callState = CallState::createFromState(json_decode($savedState, true));
        } catch (SessionStorageException $e) {
            // No session found, so this must be the beginning of the call
            $callState = new CallState();
            $callState->setCallingNumber($request->getCallingNumber());
            $callState->setCallerNumber($request->getCallerNumber());
        }

        // Some providers may not send the calling/caller numbers on subsequent requests
        if ($request->getCallerNumber() === null && $request->getCallingNumber() === null) {
            // Re-create the request, but using the caller and calling numbers from the session
            $request = new Request(
                $request->getCallId(),
                $callState->getCallerNumber(),
                $callState->getCallingNumber(),
                $request->hasInput() ? $request->getInput() : null,
                $request->hasTimedOut()
            );
        }

        $this->processCall($endpointUrl, $eventUrl, $request, $response, $callState, $startingControllerClass);

        return new OngoingCall($request->getCallId(), OngoingCall::TYPE_INCOMING);
    }

    /**
     * Method to be called on an Event URL webhook request.
     *
     * @param EventsManager $eventsManager Already configured Events Manager
     * @param string $eventUrl The absolute URL of the Event URL endpoint
     * @throws TelephonifyException
     */
    public function processEventRequest(EventsManager $eventsManager, $eventUrl)
    {
        try {
            $eventsProcessor = $this->driver->createEventsProcessor($eventUrl);
        } catch (SecurityViolationException $e) {
            throw new TelephonifyException('Security violation (please see the other exception)', 0, $e);
        }

        $eventsProcessor->process($eventsManager);
    }

    /**
     * @param string $endpointUrl
     * @param string $eventUrl
     * @return OngoingCall
     * @throws TelephonifyException
     */
    public function processOutgoingCall($endpointUrl, $eventUrl)
    {
        try {
            $request = $this->driver->createRequest($endpointUrl);
        } catch (SecurityViolationException $e) {
            throw new TelephonifyException('Security violation (please see the other exception)', 0, $e);
        } catch (UnexpectedValueException $e) {
            throw new TelephonifyException('Invalid request: ' . $e->getMessage(), 0, $e);
        }

        $response = new Response();

        try {
            // Is this the the 2nd, 3rd (and so on...) request?
            $rawState = $this->sessionStorage->readState($request->getCallId());
            $callState = CallState::createFromState(json_decode($rawState, true));

            $this->processCall($endpointUrl, $eventUrl, $request, $response, $callState);

            return new OngoingCall($request->getCallId(), OngoingCall::TYPE_OUTGOING);
        } catch (SessionStorageException $e) {
            // If not, this is the first request
            $initialCallId = CallManager::computeOutgoingCallHash(
                get_class($this->driver),
                $request->getCallingNumber(),
                $request->getCallerNumber()
            );

            try {
                $rawState = $this->sessionStorage->readState($initialCallId);
                $callState = CallState::createFromState(json_decode($rawState, true));

                $this->sessionStorage->writeState($request->getCallId(), json_encode($callState));
                $this->sessionStorage->deleteState($initialCallId);

                $this->processCall($endpointUrl, $eventUrl, $request, $response, $callState);

                return new OngoingCall($request->getCallId(), OngoingCall::TYPE_OUTGOING);
            } catch (SessionStorageException $e) {
                throw new TelephonifyException('No session could be found for the requested outgoing call', 0, $e);
            }
        }
    }

    /**
     * Process a call request (incoming/outgoing)
     *
     * @param string $endpointUrl Absolute URL for the Answer endpoint (for further redirects)
     * @param string $eventUrl Absolute URL for the Event endpoint
     * @param Request $request Absolute URL for the Event endpoint
     * @param Response $response
     * @param CallState $callState
     * @param string|null $startingControllerClass Fully qualified starting point IVRController (for incoming calls only)
     * @throws TelephonifyException
     */
    private function processCall($endpointUrl, $eventUrl, Request $request, Response $response, CallState $callState, $startingControllerClass = null)
    {
        // Ensure that continue-to is being reset
        if ($startingControllerClass !== null) {
            $callState->setContinueTo([]);
        }

        $controllerClass = $this->computeRunningController($request, $callState, $startingControllerClass);

        /** @var IVRController $controller */
        $controller = $this->makeController($controllerClass);
        $controller->run($request, $response);
        $controller->setDriver($this->driver);

        $driverResponse = $this->driver->createAnswerResponseBuilder();

        $this->processResponseItems($endpointUrl, $eventUrl, $driverResponse, $request, $response, $callState);

        try {
            $this->sessionStorage->writeState($request->getCallId(), json_encode($callState));
        } catch (SessionStorageException $e) {
            throw new TelephonifyException('The session could not be written for session ID = ' . $request->getCallId());
        }

        foreach ($driverResponse->getResponseHeaders() as $headerName => $headerValue) {
            header("{$headerName}: {$headerValue}");
        }

        echo $driverResponse->asString();
    }


    /**
     * Call the $answerResponseBuilder's methods, based on what items does the $response contains.
     *
     * @param string $endpointUrl
     * @param string $eventUrl
     * @param AnswerResponseBuilderInterface $answerResponseBuilder
     * @param Request $request
     * @param Response $response
     * @param CallState $callState
     * @throws TelephonifyException
     */
    private function processResponseItems(
        $endpointUrl,
        $eventUrl,
        AnswerResponseBuilderInterface $answerResponseBuilder,
        Request $request,
        Response $response,
        CallState $callState
    ) {
        foreach ($response->_getActionsStack() as $action) {
            switch (get_class($action)) {
                case SayText::class:
                    /** @var SayText $action */
                    $answerResponseBuilder->sayText($action->getText(), $action->getVoice(), $action->isAsync(), $action->getLoop());
                    break;
                case Play::class:
                    /** @var Play $action */
                    $answerResponseBuilder->playAudio($action->getAudioUrl(), $action->isAsync(), $action->getLoop());
                    break;
                case Redirect::class:
                    /** @var Redirect $action */
                    $className = $action->getControllerClass();

                    if (!class_exists($className) && is_subclass_of($className, IVRController::class)) {
                        throw new TelephonifyException('Attempting to do a redirect with a non-IVRController: ' . $className);
                    }

                    $action->setApplicationUrl($endpointUrl);

                    $callState->setContinueTo([$className]);
                    $answerResponseBuilder->redirect($endpointUrl, $request->getCallId(), $request->getCallingNumber(), $request->getCallerNumber());
                    break 2;
                case AskForInput::class:
                    /** @var AskForInput $action */
                    $continueValue = $action->getReturnTo();

                    if (is_string($continueValue)) {
                        $continueValue = ['*' => $continueValue];
                    }

                    foreach ($continueValue as $className) {
                        if (!class_exists($className) && is_subclass_of($className, IVRController::class)) {
                            throw new TelephonifyException('Attempting to ask for input with a non-IVRController: ' . $className);
                        }
                    }

                    $callState->setContinueTo($continueValue);

                    $answerResponseBuilder->askForInput($endpointUrl, $action->getMaxDigits(), $action->getTimeOut(), $action->shouldEndOnHashKey()
                    );
                    break 2;
                case TransferToPhoneNumber::class:
                    /** @var TransferToPhoneNumber $action */
                    $answerResponseBuilder->transferToPhoneNumber($action->getToPhone(), $action->getRingingTimeout(), $action->getFromPhone());
                    break 2;
                case JoinConference::class:
                    /** @var JoinConference $action */
                    $answerResponseBuilder->joinWaitingRoom(
                        $action->getName(),
                        $action->shouldStartWhenEntering(),
                        $action->shouldEndWhenLeaving(),
                        $action->isMuted(),
                        $action->getWaitingMusicUrl()
                    );

                    break 2;
            }
        }
    }

    /**
     * Based on the call input/current call session, route the call to a controller.
     *
     * @param Request $request
     * @param CallState $callState
     * @param null|string $startingControllerClass For incoming calls: starting IVRController point
     * @return string Fully qualified IVRController class name
     * @throws TelephonifyException If no route cannot be performed
     */
    private function computeRunningController(Request $request, CallState $callState, $startingControllerClass)
    {
        $continueTo = $callState->getContinueTo();

        if ($continueTo !== []) {
            if (count($continueTo) > 1) {
                if ($request->hasInput() && isset($continueTo[$request->getInput()])) {
                    return $continueTo[$request->getInput()];
                }

                if (isset($continueTo['*'])) {
                    return $continueTo['*'];
                }

                throw new TelephonifyException(
                    'No match could be found for this routing: ' . var_export($continueTo, true));
            }

            return current($continueTo);
        }

        if ($startingControllerClass !== null) {
            return $startingControllerClass;
        }

        throw new TelephonifyException('No match could be found for this routing (no starting controller provided)');
    }

    /**
     * Call the factory callable for making a controller (if set),
     * otherwise just try to "new" the class name
     *
     * @param string $controllerClassName Fully qualified IVRController class name
     * @return IVRController
     */
    private function makeController($controllerClassName)
    {
        if ($this->controllerFactory !== null) {
            return call_user_func($this->controllerFactory, $controllerClassName);
        }

        return new $controllerClassName;
    }
}
