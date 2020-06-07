<?php

namespace KattSoftware\Telephonify\Drivers\Voices;

/**
 * This content is released under the MIT License (MIT).
 * @see LICENSE file
 *
 * @method static NexmoVoice EN_US_SALLI() female
 * @method static NexmoVoice EN_US_JOEY() male
 * @method static NexmoVoice DA_DK_NAJA() female
 * @method static NexmoVoice DA_DK_MADS() male
 * @method static NexmoVoice DE_DE_MARLENE() female
 * @method static NexmoVoice DE_DE_HANS() male
 * @method static NexmoVoice EN_AU_NICOLE() female
 * @method static NexmoVoice EN_AU_RUSSELL() male
 * @method static NexmoVoice EN_GB_AMY() female
 * @method static NexmoVoice EN_GB_BRIAN() male
 * @method static NexmoVoice EN_GB_EMMA() female
 * @method static NexmoVoice EN_GB_GWYNETH() WLS female
 * @method static NexmoVoice EN_GB_GERAINT() WLS male
 * @method static NexmoVoice CY_GB_GWYNETH() WLS female
 * @method static NexmoVoice CY_GB_GERAINT() WLS male
 * @method static NexmoVoice EN_IN_RAVEENA() female
 * @method static NexmoVoice EN_US_IVY() female
 * @method static NexmoVoice EN_US_MATTHEW() male
 * @method static NexmoVoice EN_US_JUSTIN() male
 * @method static NexmoVoice EN_US_KENDRA() female
 * @method static NexmoVoice EN_US_KIMBERLY() female
 * @method static NexmoVoice EN_US_JOANNA() female
 * @method static NexmoVoice ES_ES_CONCHITA() female
 * @method static NexmoVoice ES_ES_ENRIQUE() male
 * @method static NexmoVoice ES_US_PENELOPE() female
 * @method static NexmoVoice ES_US_MIGUEL() male
 * @method static NexmoVoice FR_CA_CHANTAL() female
 * @method static NexmoVoice FR_FR_CELINE() female
 * @method static NexmoVoice FR_FR_MATHIEU() male
 * @method static NexmoVoice HI_IN_ADITI() female
 * @method static NexmoVoice IS_IS_DORA() female
 * @method static NexmoVoice IS_IS_KARL() male
 * @method static NexmoVoice IT_IT_CARLA() female
 * @method static NexmoVoice IT_IT_GIORGIO() male
 * @method static NexmoVoice NB_NO_LIV() female
 * @method static NexmoVoice NL_NL_LOTTE() female
 * @method static NexmoVoice NL_NL_RUBEN() male
 * @method static NexmoVoice PL_PL_JACEK() male
 * @method static NexmoVoice PL_PL_EWA() female
 * @method static NexmoVoice PL_PL_JAN() male
 * @method static NexmoVoice PL_PL_MAJA() female
 * @method static NexmoVoice PT_BR_VITORIA() female
 * @method static NexmoVoice PT_BR_RICARDO() male
 * @method static NexmoVoice PT_PT_CRISTIANO() male
 * @method static NexmoVoice PT_PT_INES() female
 * @method static NexmoVoice RO_RO_CARMEN() female
 * @method static NexmoVoice RU_RU_MAXIM() male
 * @method static NexmoVoice RU_RU_TATYANA() female
 * @method static NexmoVoice SV_SE_ASTRID() female
 * @method static NexmoVoice TR_TR_FILIZ() female
 * @method static NexmoVoice JA_JP_MIZUKI() female
 * @method static NexmoVoice KO_KR_SEOYEON() female
 * @method static NexmoVoice ARA_XWW_LAILA() female
 * @method static NexmoVoice ARA_XWW_MAGED() male
 * @method static NexmoVoice ARA_XWW_TARIK() male
 * @method static NexmoVoice IND_IDN_DAMAYANTI() female
 * @method static NexmoVoice BAQ_ESP_MIREN() female
 * @method static NexmoVoice YUE_CHN_SIN_JI() female
 * @method static NexmoVoice CAT_ESP_JORDI() male
 * @method static NexmoVoice CAT_EST_MONTSERRAT() female
 * @method static NexmoVoice CES_CZE_IVETA() female
 * @method static NexmoVoice CES_CZE_ZUZANA() female
 * @method static NexmoVoice ENG_ZAF_TESSA() female
 * @method static NexmoVoice FIN_FIN_SATU() female
 * @method static NexmoVoice ELL_GRC_MELINA() female
 * @method static NexmoVoice ELL_GRC_NIKOS() male
 * @method static NexmoVoice HEB_ISR_CARMIT() female
 * @method static NexmoVoice HIN_IND_LEKHA() female
 * @method static NexmoVoice HUN_HUN_MARISKA() female
 * @method static NexmoVoice KOR_KOR_SORA() female
 * @method static NexmoVoice CMN_CHN_TIAN_TIAN() female
 * @method static NexmoVoice CMN_TWN_MEI_JIA() female
 * @method static NexmoVoice NOR_NOR_NORA() female
 * @method static NexmoVoice NOR_NOR_HENRIK() male
 * @method static NexmoVoice POR_BRA_LUCIANA() female
 * @method static NexmoVoice POR_BRA_FELIPE() male
 * @method static NexmoVoice POR_PRT_CATARINA() female
 * @method static NexmoVoice PRO_PRT_JOANA() female
 * @method static NexmoVoice RON_ROU_IOANA() female
 * @method static NexmoVoice SLK_SVK_LAURA() female
 * @method static NexmoVoice SWE_SWE_ALVA() female
 * @method static NexmoVoice SWE_SWE_OSKAR() male
 * @method static NexmoVoice THA_THA_KANYA() female
 * @method static NexmoVoice TUR_TUR_CEM() male
 * @method static NexmoVoice TUR_TUR_YELDA() female
 * @method static NexmoVoice SPA_ESP_EMPAR() female
 */
class NexmoVoice extends Voice
{
    const PROVIDER_VOICES = [
        'EN_US_SALLI',
        'EN_US_JOEY',
        'DA_DK_NAJA',
        'DA_DK_MADS',
        'DE_DE_MARLENE',
        'DE_DE_HANS',
        'EN_AU_NICOLE',
        'EN_AU_RUSSELL',
        'EN_GB_AMY',
        'EN_GB_BRIAN',
        'EN_GB_EMMA',
        'EN_GB_GWYNETH',
        'EN_GB_GERAINT',
        'CY_GB_GWYNETH',
        'CY_GB_GERAINT',
        'EN_IN_RAVEENA',
        'EN_US_IVY',
        'EN_US_MATTHEW',
        'EN_US_JUSTIN',
        'EN_US_KENDRA',
        'EN_US_KIMBERLY',
        'EN_US_JOANNA',
        'ES_ES_CONCHITA',
        'ES_ES_ENRIQUE',
        'ES_US_PENELOPE',
        'ES_US_MIGUEL',
        'FR_CA_CHANTAL',
        'FR_FR_CELINE',
        'FR_FR_MATHIEU',
        'HI_IN_ADITI',
        'IS_IS_DORA',
        'IS_IS_KARL',
        'IT_IT_CARLA',
        'IT_IT_GIORGIO',
        'NB_NO_LIV',
        'NL_NL_LOTTE',
        'NL_NL_RUBEN',
        'PL_PL_JACEK',
        'PL_PL_EWA',
        'PL_PL_JAN',
        'PL_PL_MAJA',
        'PT_BR_VITORIA',
        'PT_BR_RICARDO',
        'PT_PT_CRISTIANO',
        'PT_PT_INES',
        'RO_RO_CARMEN',
        'RU_RU_MAXIM',
        'RU_RU_TATYANA',
        'SV_SE_ASTRID',
        'TR_TR_FILIZ',
        'JA_JP_MIZUKI',
        'KO_KR_SEOYEON',
        'ARA_XWW_LAILA',
        'ARA_XWW_MAGED',
        'ARA_XWW_TARIK',
        'IND_IDN_DAMAYANTI',
        'BAQ_ESP_MIREN',
        'YUE_CHN_SIN_JI',
        'CAT_ESP_JORDI',
        'CAT_EST_MONTSERRAT',
        'CES_CZE_IVETA',
        'CES_CZE_ZUZANA',
        'ENG_ZAF_TESSA',
        'FIN_FIN_SATU',
        'ELL_GRC_MELINA',
        'ELL_GRC_NIKOS',
        'HEB_ISR_CARMIT',
        'HIN_IND_LEKHA',
        'HUN_HUN_MARISKA',
        'KOR_KOR_SORA',
        'CMN_CHN_TIAN_TIAN',
        'CMN_TWN_MEI_JIA',
        'NOR_NOR_NORA',
        'NOR_NOR_HENRIK',
        'POR_BRA_LUCIANA',
        'POR_BRA_FELIPE',
        'POR_PRT_CATARINA',
        'PRO_PRT_JOANA',
        'RON_ROU_IOANA',
        'SLK_SVK_LAURA',
        'SWE_SWE_ALVA',
        'SWE_SWE_OSKAR',
        'THA_THA_KANYA',
        'TUR_TUR_CEM',
        'TUR_TUR_YELDA',
        'SPA_ESP_EMPAR',
    ];
}
