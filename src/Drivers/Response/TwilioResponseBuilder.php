<?php

namespace KattSoftware\Telephonify\Drivers\Response;

use DOMDocument;
use DOMElement;
use KattSoftware\Telephonify\Drivers\Voices\Voice;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 */
class TwilioResponseBuilder implements AnswerResponseBuilderInterface
{
    /** @var DOMDocument */
    private $response;

    /** @var DOMElement */
    private $xmlRoot;

    /** @var DOMElement[] */
    private $asyncElementsStack = [];

    public function __construct()
    {
        $this->response = new DOMDocument('1.0', 'UTF-8');

        $this->xmlRoot = $this->response->createElement('Response');
        $this->response->appendChild($this->xmlRoot);
    }

    /**
     * @inheritDoc
     */
    public function asString()
    {
        $this->resetAsyncStack();

        return $this->response->saveXML();
    }

    /**
     * @inheritDoc
     */
    public function getResponseHeaders()
    {
        return [
            'Content-Type' => 'text/xml'
        ];
    }

    /**
     * @inheritDoc
     */
    public function sayText($text, Voice $voice, $async, $loop)
    {
        $sayTag = $this->response->createElement('Say', htmlspecialchars($text));
        $this->decorateSayTag($sayTag, $voice);

        $sayTag->setAttribute('loop', $loop);

        $this->prepareAsyncElement($async, $sayTag);
    }

    /**
     * @inheritDoc
     */
    public function playAudio($audioUrl, $async, $loop)
    {
        $playTag = $this->response->createElement('Play', htmlspecialchars($audioUrl));

        $playTag->setAttribute('loop', $loop);

        $this->prepareAsyncElement($async, $playTag);
    }

    /**
     * @inheritDoc
     */
    public function redirect($appUrl, $callId, $toPhone, $fromPhone)
    {
        $this->resetAsyncStack();

        $redirectTag = $this->response->createElement('Redirect', htmlspecialchars($appUrl));
        $redirectTag->setAttribute('method', 'POST');

        $this->xmlRoot->appendChild($redirectTag);
    }

    /**
     * @inheritDoc
     */
    public function askForInput($appUrl, $maxDigits, $timeOut, $endOnHashKey)
    {
        $gatherTag = $this->response->createElement('Gather');
        $gatherTag->setAttribute('action', $appUrl);
        $gatherTag->setAttribute('input', 'dtmf');
        $gatherTag->setAttribute('method', 'POST');
        $gatherTag->setAttribute('numDigits', $maxDigits);
        $gatherTag->setAttribute('timeout', $timeOut);
        $gatherTag->setAttribute('actionOnEmptyResult', true);

        if ($endOnHashKey) {
            $gatherTag->setAttribute('finishOnKey', '#');
        }

        // Should Gather nest other async elements?
        if ($this->asyncElementsStack !== []) {
            foreach ($this->asyncElementsStack as $element) {
                $gatherTag->appendChild($element);
            }

            $this->asyncElementsStack = [];
        }

        $this->xmlRoot->appendChild($gatherTag);
    }

    /**
     * @inheritDoc
     */
    public function transferToPhoneNumber($toPhone, $ringingTimeout, $fromPhone)
    {
        $this->resetAsyncStack();

        $dialTag = $this->response->createElement('Dial', htmlspecialchars('+' . $toPhone));
        $dialTag->setAttribute('timeout', $ringingTimeout);
        $dialTag->setAttribute('callerId', '+' . $fromPhone);

        $this->xmlRoot->appendChild($dialTag);
    }

    /**
     * @inheritDoc
     */
    public function joinWaitingRoom($name, $startWhenEntering, $endWhenLeaving, $muted, $waitingMusicUrl)
    {
        $this->resetAsyncStack();

        $dialTag = $this->response->createElement('Dial');

        $conference = $this->response->createElement('Conference', htmlspecialchars($name));
        $conference->setAttribute('muted', $muted ? 'true' : 'false');
        $conference->setAttribute('startConferenceOnEnter', $startWhenEntering ? 'true' : 'false');
        $conference->setAttribute('endConferenceOnExit', $endWhenLeaving ? 'true' : 'false');
        $conference->setAttribute('beep', 'false');

        if ($waitingMusicUrl !== null) {
            $conference->setAttribute('waitUrl', $waitingMusicUrl);

            $waitUrlExtension = substr($waitingMusicUrl, -3);

            if (strcasecmp($waitUrlExtension, 'mp3') === 0 || strcasecmp($waitUrlExtension, 'wav')) {
                $conference->setAttribute('waitMethod', 'GET');
            }
        }

        $dialTag->appendChild($conference);

        $this->xmlRoot->appendChild($dialTag);
    }

    private function resetAsyncStack()
    {
        if ($this->asyncElementsStack !== []) {
            foreach ($this->asyncElementsStack as $element) {
                $this->xmlRoot->appendChild($element);
            }

            $this->asyncElementsStack = [];
        }
    }

    /**
     * @param DOMElement $sayTag
     * @param Voice $voice
     */
    private function decorateSayTag(DOMElement $sayTag, Voice $voice)
    {
        switch ($voice->getVoiceName()) {
            case 'EN_US_WOMAN':
                $sayTag->setAttribute('voice', 'woman');
                $sayTag->setAttribute('language', 'en');
                break;
            case 'EN_US_MAN':
                $sayTag->setAttribute('voice', 'man');
                $sayTag->setAttribute('language', 'en');
                break;
            case 'EN_GB_WOMAN':
                $sayTag->setAttribute('voice', 'woman');
                $sayTag->setAttribute('language', 'en-gb');
                break;
            case 'EN_GB_MAN':
                $sayTag->setAttribute('voice', 'man');
                $sayTag->setAttribute('language', 'en-gb');
                break;
            case 'ES_ES_WOMAN':
                $sayTag->setAttribute('voice', 'woman');
                $sayTag->setAttribute('language', 'es');
                break;
            case 'ES_ES_MAN':
                $sayTag->setAttribute('voice', 'man');
                $sayTag->setAttribute('language', 'es');
                break;
            case 'FR_FR_WOMAN':
                $sayTag->setAttribute('voice', 'woman');
                $sayTag->setAttribute('language', 'fr');
                break;
            case 'FR_FR_MAN':
                $sayTag->setAttribute('voice', 'man');
                $sayTag->setAttribute('language', 'fr');
                break;
            case 'DE_DE_WOMAN':
                $sayTag->setAttribute('voice', 'woman');
                $sayTag->setAttribute('language', 'de');
                break;
            case 'DE_DE_MAN':
                $sayTag->setAttribute('voice', 'man');
                $sayTag->setAttribute('language', 'de');
                break;
            case 'DA_DK_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'da-DK');
                break;
            case 'DE_DE_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'de-DE');
                break;
            case 'EN_AU_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'en-AU');
                break;
            case 'EN_CA_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'en-CA');
                break;
            case 'EN_GB_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'en-GB');
                break;
            case 'EN_IN_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'en-IN');
                break;
            case 'EN_US_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'en-US');
                break;
            case 'CA_ES_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'ca-ES');
                break;
            case 'ES_ES_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'es-ES');
                break;
            case 'ES_MX_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'es-MX');
                break;
            case 'FI_FI_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'fi-FI');
                break;
            case 'FR_CA_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'fr-CA');
                break;
            case 'FR_FR_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'fr-FR');
                break;
            case 'IT_IT_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'it-IT');
                break;
            case 'JA_JP_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'ja-JP');
                break;
            case 'KO_KR_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'ko-KR');
                break;
            case 'NB_NO_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'nb-NO');
                break;
            case 'NL_NL_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'nl-NL');
                break;
            case 'PL_PL_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'pl-PL');
                break;
            case 'PT_BR_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'pt-BR');
                break;
            case 'PT_PT_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'pt-PT');
                break;
            case 'RU_RU_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'ru-RU');
                break;
            case 'SV_SE_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'sv-SE');
                break;
            case 'ZH_CN_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'zh-CN');
                break;
            case 'ZH_HK_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'zh-HK');
                break;
            case 'ZH_TW_ALICE':
                $sayTag->setAttribute('voice', 'alice');
                $sayTag->setAttribute('language', 'zh-TW');
                break;
            case 'DA_DK_MADS':
                $sayTag->setAttribute('voice', 'Polly.Mads');
                break;
            case 'DA_DK_NAJA':
                $sayTag->setAttribute('voice', 'Polly.Naja');
                break;
            case 'NL_NL_LOTTE':
                $sayTag->setAttribute('voice', 'Polly.Lotte');
                break;
            case 'NL_NL_RUBEN':
                $sayTag->setAttribute('voice', 'Polly.Ruben');
                break;
            case 'EN_AU_NICOLE':
                $sayTag->setAttribute('voice', 'Polly.Nicole');
                break;
            case 'EN_AU_RUSSELL':
                $sayTag->setAttribute('voice', 'Polly.Russell');
                break;
            case 'EN_GB_AMY':
                $sayTag->setAttribute('voice', 'Polly.Amy');
                break;
            case 'EN_GB_BRIAN':
                $sayTag->setAttribute('voice', 'Polly.Brian');
                break;
            case 'EN_GB_EMMA':
                $sayTag->setAttribute('voice', 'Polly.Emma');
                break;
            case 'EN_GB_AMY_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Amy-Neural');
                break;
            case 'EN_GB_EMMA_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Emma-Neural');
                break;
            case 'EN_GB_BRIAN_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Brian-Neural');
                break;
            case 'EN_IN_RAVEENA':
                $sayTag->setAttribute('voice', 'Polly.Raveena');
                break;
            case 'EN_US_IVY':
                $sayTag->setAttribute('voice', 'Polly.Ivy');
                break;
            case 'EN_US_JOANNA':
                $sayTag->setAttribute('voice', 'Polly.Joanna');
                break;
            case 'EN_US_JOEY':
                $sayTag->setAttribute('voice', 'Polly.Joey');
                break;
            case 'EN_US_JUSTIN':
                $sayTag->setAttribute('voice', 'Polly.Justin');
                break;
            case 'EN_US_KENDRA':
                $sayTag->setAttribute('voice', 'Polly.Kendra');
                break;
            case 'EN_US_KIMBERLY':
                $sayTag->setAttribute('voice', 'Polly.Kimberly');
                break;
            case 'EN_US_MATTHEW':
                $sayTag->setAttribute('voice', 'Polly.Matthew');
                break;
            case 'EN_US_SALLI':
                $sayTag->setAttribute('voice', 'Polly.Salli');
                break;
            case 'EN_US_IVY_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Ivy-Neural');
                break;
            case 'EN_US_JOANNA_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Joanna-Neural');
                break;
            case 'EN_US_KENDRA_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Kendra-Neural');
                break;
            case 'EN_US_KIMBERLY_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Kimberly-Neural');
                break;
            case 'EN_US_SALLI_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Salli-Neural');
                break;
            case 'EN_US_JOEY_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Joey-Neural');
                break;
            case 'EN_US_JUSTIN_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Justin-Neural');
                break;
            case 'EN_US_MATTHEW_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Matthew-Neural');
                break;
            case 'EN_GB_GERAINT':
                $sayTag->setAttribute('voice', 'Polly.Geraint');
                break;
            case 'FR_FR_CELINE':
                $sayTag->setAttribute('voice', 'Polly.Celine');
                break;
            case 'FR_FR_MATHIEU':
                $sayTag->setAttribute('voice', 'Polly.Mathieu');
                break;
            case 'FR_CA_CHANTAL':
                $sayTag->setAttribute('voice', 'Polly.Chantal');
                break;
            case 'DE_DE_HANS':
                $sayTag->setAttribute('voice', 'Polly.Hans');
                break;
            case 'DE_DE_MARLENE':
                $sayTag->setAttribute('voice', 'Polly.Marlene');
                break;
            case 'DE_DE_VICKI':
                $sayTag->setAttribute('voice', 'Polly.Vicki');
                break;
            case 'IS_IS_DORA':
                $sayTag->setAttribute('voice', 'Polly.Dora');
                break;
            case 'IS_IS_KARL':
                $sayTag->setAttribute('voice', 'Polly.Karl');
                break;
            case 'IT_IT_CARLA':
                $sayTag->setAttribute('voice', 'Polly.Carla');
                break;
            case 'IT_IT_GIORGIO':
                $sayTag->setAttribute('voice', 'Polly.Giorgio');
                break;
            case 'JA_JP_MIZUKI':
                $sayTag->setAttribute('voice', 'Polly.Mizuki');
                break;
            case 'JA_JP_TAKUMI':
                $sayTag->setAttribute('voice', 'Polly.Takumi');
                break;
            case 'NB_NO_LIV':
                $sayTag->setAttribute('voice', 'Polly.Liv');
                break;
            case 'PL_PL_JACEK':
                $sayTag->setAttribute('voice', 'Polly.Jacek');
                break;
            case 'PL_PL_JAN':
                $sayTag->setAttribute('voice', 'Polly.Jan');
                break;
            case 'PL_PL_EWA':
                $sayTag->setAttribute('voice', 'Polly.Ewa');
                break;
            case 'PL_PL_MAJA':
                $sayTag->setAttribute('voice', 'Polly.Maja');
                break;
            case 'PT_BR_RICARDO':
                $sayTag->setAttribute('voice', 'Polly.Ricardo');
                break;
            case 'PT_BR_VITORIA':
                $sayTag->setAttribute('voice', 'Polly.Vitoria');
                break;
            case 'PT_BR_CAMILA_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Camila-Neural');
                break;
            case 'PT_PT_CRISTIANO':
                $sayTag->setAttribute('voice', 'Polly.Cristiano');
                break;
            case 'PT_PT_INES':
                $sayTag->setAttribute('voice', 'Polly.Ines');
                break;
            case 'RO_RO_CARMEN':
                $sayTag->setAttribute('voice', 'Polly.Carmen');
                break;
            case 'RU_RU_MAXIM':
                $sayTag->setAttribute('voice', 'Polly.Maxim');
                break;
            case 'RU_RU_TATYANA':
                $sayTag->setAttribute('voice', 'Polly.Tatyana');
                break;
            case 'ES_ES_CONCHITA':
                $sayTag->setAttribute('voice', 'Polly.Conchita');
                break;
            case 'ES_ES_ENRIQUE':
                $sayTag->setAttribute('voice', 'Polly.Enrique');
                break;
            case 'ES_US_MIGUEL':
                $sayTag->setAttribute('voice', 'Polly.Miguel');
                break;
            case 'ES_US_PENELOPE':
                $sayTag->setAttribute('voice', 'Polly.Penelope');
                break;
            case 'ES_US_LUPE_NEURAL':
                $sayTag->setAttribute('voice', 'Polly.Lupe-Neural');
                break;
            case 'SV_SE_ASTRID':
                $sayTag->setAttribute('voice', 'Polly.Astrid');
                break;
            case 'TR_TR_FILIZ':
                $sayTag->setAttribute('voice', 'Polly.Filiz');
                break;
            case 'CY_GB_GWYNETH':
                $sayTag->setAttribute('voice', 'Polly.Gwyneth');
                break;
        }
    }

    /**
     * @param bool $async
     * @param DOMElement $element
     */
    private function prepareAsyncElement($async, DOMElement $element)
    {
        if ($async) {
            $this->asyncElementsStack[] = $element;
        } else {
            if ($this->asyncElementsStack !== []) {
                // Reset the async stack, discarding all async stack elements
                // directly into XML's root
                $this->resetAsyncStack();
            }

            $this->xmlRoot->appendChild($element);
        }
    }
}
