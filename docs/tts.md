### Reading text by using the TTS

For all providers, you are able to use a TTS (text-to-speech) engine which transforms text to actual audio speech. When doing so, you must pick a voice which can read the text in a certain language (and sometimes with a certain accent). Unfortunately, the voices available for a certain provider are not compatible with the voices from another provider (and vice-versa). Because of that, depending on the driver that you are using, you must ensure that the voice you are trying to use is specific to that provider. Below are the complete lists of available voices, depending on your provider, and what value you must send as the second parameter for `Response::say()`. 

```php
$textToSpeech = '...';
$voice = ...; (see below)

$response->say($text, $voice);
```


#### Nexmo

If you are using Nexmo, then the following voices are available:

| To speak the language | By using the voice of | Then `$voice` should be |
|-----------------------|-----------------------|-------------------------|
| en-US | Salli (female) | `NexmoVoice::EN_US_SALLI()` |
| en-US | Joey (male) | `NexmoVoice::EN_US_JOEY()` |
| da-DK | Naja (female) | `NexmoVoice::DA_DK_NAJA()` |
| da-DK | Mads (male) | `NexmoVoice::DA_DK_MADS()` |
| de-DE | Marlene (female) | `NexmoVoice::DE_DE_MARLENE()` |
| de-DE | Hans (male) | `NexmoVoice::DE_DE_HANS()` |
| en-AU | Nicole (female) | `NexmoVoice::EN_AU_NICOLE()` |
| en-AU | Russell (male) | `NexmoVoice::EN_AU_RUSSELL()` |
| en-GB | Amy (female) | `NexmoVoice::EN_GB_AMY()` |
| en-GB | Brian (male) | `NexmoVoice::EN_GB_BRIAN()` |
| en-GB | Emma (female) | `NexmoVoice::EN_GB_EMMA()` |
| en-IN | Raveena (female) | `NexmoVoice::EN_IN_RAVEENA()` |
| en-US | Ivy (female) | `NexmoVoice::EN_US_IVY()` |
| en-US | Matthew (male) | `NexmoVoice::EN_US_MATTHEW()` |
| en-US | Justin (male) | `NexmoVoice::EN_US_JUSTIN()` |
| en-US | Kendra (female) | `NexmoVoice::EN_US_KENDRA()` |
| en-US | Kimberly (female) | `NexmoVoice::EN_US_KIMBERLY()` |
| en-US | Joanna (female) | `NexmoVoice::EN_US_JOANNA()` |
| es-ES | Conchita (female) | `NexmoVoice::ES_ES_CONCHITA()` |
| es-ES | Enrique (male) | `NexmoVoice::ES_ES_ENRIQUE()` |
| es-US | Penelope (female) | `NexmoVoice::ES_US_PENELOPE()` |
| es-US | Miguel (male) | `NexmoVoice::ES_US_MIGUEL()` |
| fr-CA | Chantal (female) | `NexmoVoice::FR_CA_CHANTAL()` |
| fr-FR | Celine (female) | `NexmoVoice::FR_FR_CELINE()` |
| fr-FR | Mathieu (male) | `NexmoVoice::FR_FR_MATHIEU()` |
| hi-IN | Aditi (female) | `NexmoVoice::HI_IN_ADITI()` |
| is-IS | Dora (female) | `NexmoVoice::IS_IS_DORA()` |
| is-IS | Karl (male) | `NexmoVoice::IS_IS_KARL()` |
| it-IT | Carla (female) | `NexmoVoice::IT_IT_CARLA()` |
| it-IT | Giorgio (male) | `NexmoVoice::IT_IT_GIORGIO()` |
| nb-NO | Liv (female) | `NexmoVoice::NB_NO_LIV()` |
| nl-NL | Lotte (female) | `NexmoVoice::NL_NL_LOTTE()` |
| nl-NL | Ruben (male) | `NexmoVoice::NL_NL_RUBEN()` |
| pl-PL | Jacek (male) | `NexmoVoice::PL_PL_JACEK()` |
| pl-PL | Ewa (female) | `NexmoVoice::PL_PL_EWA()` |
| pl-PL | Jan (male) | `NexmoVoice::PL_PL_JAN()` |
| pl-PL | Maja (female) | `NexmoVoice::PL_PL_MAJA()` |
| pt-BR | Vitoria (female) | `NexmoVoice::PT_BR_VITORIA()` |
| pt-BR | Ricardo (male) | `NexmoVoice::PT_BR_RICARDO()` |
| pt-PT | Cristiano (male) | `NexmoVoice::PT_PT_CRISTIANO()` |
| pt-PT | Ines (female) | `NexmoVoice::PT_PT_INES()` |
| ro-RO | Carmen (female) | `NexmoVoice::RO_RO_CARMEN()` |
| ru-RU | Maxim (male) | `NexmoVoice::RU_RU_MAXIM()` |
| ru-RU | Tatyana (female) | `NexmoVoice::RU_RU_TATYANA()` |
| sv-SE | Astrid (female) | `NexmoVoice::SV_SE_ASTRID()` |
| tr-TR | Filiz (female) | `NexmoVoice::TR_TR_FILIZ()` |
| ja-JP | Mizuki (female) | `NexmoVoice::JA_JP_MIZUKI()` |
| ko-KR | Seoyeon (female) | `NexmoVoice::KO_KR_SEOYEON()` |
| ara-XWW | Laila (female) | `NexmoVoice::ARA_XWW_LAILA()` |
| ara-XWW | Maged (male) | `NexmoVoice::ARA_XWW_MAGED()` |
| ara-XWW | Tarik (male) | `NexmoVoice::ARA_XWW_TARIK()` |
| ind-IDN | Damayanti (female) | `NexmoVoice::IND_IDN_DAMAYANTI()` |
| baq-ESP | Miren (female) | `NexmoVoice::BAQ_ESP_MIREN()` |
| yue-CHN | Miren (female) | `NexmoVoice::YUE_CHN_MIREN()` |
| cat-ESP | Jordi (male) | `NexmoVoice::CAT_ESP_JORDI()` |
| cat-ESP | Montserrat (female) | `NexmoVoice::CAT_ESP_MONTSERRAT()` |
| ces-CZE | Iveta (female) | `NexmoVoice::CES_CZE_IVETA()` |
| ces-CZE | Zuzana (female) | `NexmoVoice::CES_CZE_ZUZANA()` |
| eng-ZAF | Tessa (female) | `NexmoVoice::ENG_ZAF_TESSA()` |
| fin-FIN | Satu (female) | `NexmoVoice::FIN_FIN_SATU()` |
| ell-GRC | Melina (female) | `NexmoVoice::ELL_GRC_MELINA()` |
| ell-GRC | Nikos (male) | `NexmoVoice::ELL_GRC_NIKOS()` |
| heb-ISR | Carmit (female) | `NexmoVoice::HEB_ISR_CARMIT()` |
| hin-IND | Lekha (female) | `NexmoVoice::HIN_IND_LEKHA()` |
| hun-HUN | Mariska (female) | `NexmoVoice::HUN_HUN_MARISKA()` |
| kor-KOR | Sora (female) | `NexmoVoice::KOR_KOR_SORA()` |
| cmn-CHN | Sora (female) | `NexmoVoice::CMN_CHN_SORA()` |
| cmn-TWN | Sora (female) | `NexmoVoice::CMN_TWN_SORA()` |
| nor-NOR | Nora (female) | `NexmoVoice::NOR_NOR_NORA()` |
| nor-NOR | Henrik (male) | `NexmoVoice::NOR_NOR_HENRIK()` |
| por-BRA | Luciana (female) | `NexmoVoice::POR_BRA_LUCIANA()` |
| por-BRA | Felipe (male) | `NexmoVoice::POR_BRA_FELIPE()` |
| por-PRT | Catarina (female) | `NexmoVoice::POR_PRT_CATARINA()` |
| por-PRT | Joana (female) | `NexmoVoice::POR_PRT_JOANA()` |
| ron-ROU | Ioana (female) | `NexmoVoice::RON_ROU_IOANA()` |
| slk-SVK | Laura (female) | `NexmoVoice::SLK_SVK_LAURA()` |
| swe-SWE | Alva (female) | `NexmoVoice::SWE_SWE_ALVA()` |
| swe-SWE | Oskar (male) | `NexmoVoice::SWE_SWE_OSKAR()` |
| tha-THA | Kanya (female) | `NexmoVoice::THA_THA_KANYA()` |
| tur-TUR | Cem (male) | `NexmoVoice::TUR_TUR_CEM()` |
| tur-TUR | Yelda (female) | `NexmoVoice::TUR_TUR_YELDA()` |
| spa-ESP | Empar (female) | `NexmoVoice::SPA_ESP_EMPAR()` |

Note: the above table was automatically generated based on the Nexmo documentation. If you find any issues, kindly please report them.

Example usage (assuming you are in a controller):

```php
$response->say('Hello! My name is Salli!', NexmoVoice::EN_US_SALLI());
$response->say('And my name is Joanna!', NexmoVoice::EN_US_JOANNA());
$response->say('Bonjour, mon ami!', NexmoVoice::FR_FR_CELINE());
```

#### Twilio

If you are using Twilio, then the following voices are available:

| To speak the language | By using the voice of | Then `$voice` should be |
|-----------------------|-----------------------|-------------------------|
| en-us | generic woman | `TwilioVoice::EN_US_WOMAN()` |
| en-us | generic man | `TwilioVoice::EN_US_MAN()` |
| en-gb | generic woman | `TwilioVoice::EN_GB_WOMAN()` |
| en-gb | generic man | `TwilioVoice::EN_GB_MAN()` |
| es | generic woman | `TwilioVoice::ES_ES_WOMAN()` |
| es | generic man | `TwilioVoice::ES_ES_MAN()` |
| fr | generic woman | `TwilioVoice::FR_FR_WOMAN()` |
| fr | generic man | `TwilioVoice::FR_FR_MAN()` |
| de | generic woman | `TwilioVoice::DE_DE_WOMAN()` |
| de | generic man | `TwilioVoice::DE_DE_MAN()` |
| Danish, Denmark | Alice (female) | `TwilioVoice::DA_DK_ALICE()` |
| German, Germany | Alice (female) | `TwilioVoice::DE_DE_ALICE()` |
| English, Australia | Alice (female) | `TwilioVoice::EN_AU_ALICE()` |
| English, Canada | Alice (female) | `TwilioVoice::EN_CA_ALICE()` |
| English, UK | Alice (female) | `TwilioVoice::EN_GB_ALICE()` |
| English, India | Alice (female) | `TwilioVoice::EN_IN_ALICE()` |
| English, United States | Alice (female) | `TwilioVoice::EN_US_ALICE()` |
| Catalan, Spain | Alice (female) | `TwilioVoice::CA_ES_ALICE()` |
| Spanish, Spain | Alice (female) | `TwilioVoice::ES_ES_ALICE()` |
| Spanish, Mexico | Alice (female) | `TwilioVoice::ES_MX_ALICE()` |
| Finnish, Finland | Alice (female) | `TwilioVoice::FI_FI_ALICE()` |
| French, Canada | Alice (female) | `TwilioVoice::FR_CA_ALICE()` |
| French, France | Alice (female) | `TwilioVoice::FR_FR_ALICE()` |
| Italian, Italy | Alice (female) | `TwilioVoice::IT_IT_ALICE()` |
| Japanese, Japan | Alice (female) | `TwilioVoice::JA_JP_ALICE()` |
| Korean, Korea | Alice (female) | `TwilioVoice::KO_KR_ALICE()` |
| Norwegian, Norway | Alice (female) | `TwilioVoice::NB_NO_ALICE()` |
| Dutch, Netherlands | Alice (female) | `TwilioVoice::NL_NL_ALICE()` |
| Polish-Poland | Alice (female) | `TwilioVoice::PL_PL_ALICE()` |
| Portuguese, Brazil | Alice (female) | `TwilioVoice::PT_BR_ALICE()` |
| Portuguese, Portugal | Alice (female) | `TwilioVoice::PT_PT_ALICE()` |
| Russian, Russia | Alice (female) | `TwilioVoice::RU_RU_ALICE()` |
| Swedish, Sweden | Alice (female) | `TwilioVoice::SV_SE_ALICE()` |
| Chinese (Mandarin) | Alice (female) | `TwilioVoice::ZH_CN_ALICE()` |
| Chinese (Cantonese) | Alice (female) | `TwilioVoice::ZH_HK_ALICE()` |
| Chinese (Taiwanese Mandarin) | Alice (female) | `TwilioVoice::ZH_TW_ALICE()` |
| Danish (da-DK) | Mads (male) | `TwilioVoice::DA_DK_MADS()` |
| Danish (da-DK) | Naja (female) | `TwilioVoice::DA_DK_NAJA()` |
| Dutch (nl-NL) | Lotte (female) | `TwilioVoice::NL_NL_LOTTE()` |
| Dutch (nl-NL) | Ruben (male) | `TwilioVoice::NL_NL_RUBEN()` |
| English (Australian) (en-AU) | Nicole (female) | `TwilioVoice::EN_AU_NICOLE()` |
| English (Australian) (en-AU) | Russell (male) | `TwilioVoice::EN_AU_RUSSELL()` |
| English (British) (en-GB) | Amy (female) | `TwilioVoice::EN_GB_AMY()` |
| English (British) (en-GB) | Brian (male) | `TwilioVoice::EN_GB_BRIAN()` |
| English (British) (en-GB) | Emma (female) | `TwilioVoice::EN_GB_EMMA()` |
| English (British) (en-GB) | Amy Neural (female) | `TwilioVoice::EN_GB_AMY_NEURAL()` |
| English (British) (en-GB) | Emma Neural (female) | `TwilioVoice::EN_GB_EMMA_NEURAL()` |
| English (British) (en-GB) | Brian Neural (male) | `TwilioVoice::EN_GB_BRIAN_NEURAL()` |
| English (Indian) (en-IN) | Raveena (female) | `TwilioVoice::EN_IN_RAVEENA()` |
| English (US) (en-US) | Ivy (female) | `TwilioVoice::EN_US_IVY()` |
| English (US) (en-US) | Joanna (female) | `TwilioVoice::EN_US_JOANNA()` |
| English (US) (en-US) | Joey (male) | `TwilioVoice::EN_US_JOEY()` |
| English (US) (en-US) | Justin (male) | `TwilioVoice::EN_US_JUSTIN()` |
| English (US) (en-US) | Kendra (female) | `TwilioVoice::EN_US_KENDRA()` |
| English (US) (en-US) | Kimberly (female) | `TwilioVoice::EN_US_KIMBERLY()` |
| English (US) (en-US) | Matthew (male) | `TwilioVoice::EN_US_MATTHEW()` |
| English (US) (en-US) | Salli (female) | `TwilioVoice::EN_US_SALLI()` |
| English (US) (en-US) | Ivy Neural (female) | `TwilioVoice::EN_US_IVY_NEURAL()` |
| English (US) (en-US) | Joanna Neural (female) | `TwilioVoice::EN_US_JOANNA_NEURAL()` |
| English (US) (en-US) | Kendra Neural (female) | `TwilioVoice::EN_US_KENDRA_NEURAL()` |
| English (US) (en-US) | Kimberly Neural (female) | `TwilioVoice::EN_US_KIMBERLY_NEURAL()` |
| English (US) (en-US) | Salli Neural (female) | `TwilioVoice::EN_US_SALLI_NEURAL()` |
| English (US) (en-US) | Joey Neural (male) | `TwilioVoice::EN_US_JOEY_NEURAL()` |
| English (US) (en-US) | Justin Neural (male) | `TwilioVoice::EN_US_JUSTIN_NEURAL()` |
| English (US) (en-US) | Matthew Neural (male) | `TwilioVoice::EN_US_MATTHEW_NEURAL()` |
| English (Welsh) (en-GB-WLS) | Geraint (male) | `TwilioVoice::EN_GB_GERAINT()` |
| French (fr-FR) | Céline (female) | `TwilioVoice::FR_FR_CELINE()` |
| French (fr-FR) | Mathieu (male) | `TwilioVoice::FR_FR_MATHIEU()` |
| French (Canadian) (fr-CA) | Chantal (female) | `TwilioVoice::FR_CA_CHANTAL()` |
| German (de-DE) | Hans (male) | `TwilioVoice::DE_DE_HANS()` |
| German (de-DE) | Marlene (female) | `TwilioVoice::DE_DE_MARLENE()` |
| German (de-DE) | Vicki (female) | `TwilioVoice::DE_DE_VICKI()` |
| Icelandic (is-IS) | Dóra (female) | `TwilioVoice::IS_IS_DORA()` |
| Icelandic (is-IS) | Karl (male) | `TwilioVoice::IS_IS_KARL()` |
| Italian (it-IT) | Carla (female) | `TwilioVoice::IT_IT_CARLA()` |
| Italian (it-IT) | Giorgio (male) | `TwilioVoice::IT_IT_GIORGIO()` |
| Japanese (ja-JP) | Mizuki (female) | `TwilioVoice::JA_JP_MIZUKI()` |
| Japanese (ja-JP) | Takumi (male) | `TwilioVoice::JA_JP_TAKUMI()` |
| Norwegian (nb-NO) | Liv (female) | `TwilioVoice::NB_NO_LIV()` |
| Polish (pl-PL) | Jacek (male) | `TwilioVoice::PL_PL_JACEK()` |
| Polish (pl-PL) | Jan (male) | `TwilioVoice::PL_PL_JAN()` |
| Polish (pl-PL) | Ewa (female) | `TwilioVoice::PL_PL_EWA()` |
| Polish (pl-PL) | Maja (female) | `TwilioVoice::PL_PL_MAJA()` |
| Portuguese (Brazilian) (pt-BR) | Ricardo (male) | `TwilioVoice::PT_BR_RICARDO()` |
| Portuguese (Brazilian) (pt-BR) | Vitória (female) | `TwilioVoice::PT_BR_VITORIA()` |
| Portuguese (Brazilian) (pt-BR) | Camila Neural (female) | `TwilioVoice::PT_BR_CAMILA_NEURAL()` |
| Portuguese (European) (pt-PT) | Cristiano (male) | `TwilioVoice::PT_PT_CRISTIANO()` |
| Portuguese (European) (pt-PT) | Inês (female) | `TwilioVoice::PT_PT_INES()` |
| Romanian (ro-RO) | Carmen (female) | `TwilioVoice::RO_RO_CARMEN()` |
| Russian (ru-RU) | Maxim (male) | `TwilioVoice::RU_RU_MAXIM()` |
| Russian (ru-RU) | Tatyana (female) | `TwilioVoice::RU_RU_TATYANA()` |
| Spanish (Castilian) (es-ES) | Conchita (female) | `TwilioVoice::ES_ES_CONCHITA()` |
| Spanish (Castilian) (es-ES) | Enrique (male) | `TwilioVoice::ES_ES_ENRIQUE()` |
| Spanish (Latin American) (es-US) | Miguel (male) | `TwilioVoice::ES_US_MIGUEL()` |
| Spanish (Latin American) (es-US) | Penélope (female) | `TwilioVoice::ES_US_PENELOPE()` |
| Spanish (Latin American) (es-US) | Lupe Neural (female) | `TwilioVoice::ES_US_LUPE_NEURAL()` |
| Swedish (sv-SE) | Astrid (female) | `TwilioVoice::SV_SE_ASTRID()` |
| Turkish (tr-TR) | Filiz (female) | `TwilioVoice::TR_TR_FILIZ()` |
| Welsh (cy-GB) | Gwyneth (female) | `TwilioVoice::CY_GB_GWYNETH()` |

Note: the above table was automatically generated based on the Twilio documentation. If you find any issues, kindly please report them.

Example usage (assuming you are in a controller):

```php
$response->say('Hello!', TwilioVoice::EN_US_JOANNA());
$response->say('Hello from the Neural version of Joanna voice!', TwilioVoice::EN_US_JOANNA_NEURAL());
$response->say('Bonjour!', TwilioVoice::FR_FR_CELINE());
```

Depending on the voice that you are using, Twilio may charge its TTS engine usage. Please review their [documentation](https://www.twilio.com/docs/voice/twiml/say/text-speech#pricing) for more info.
