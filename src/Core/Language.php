<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\LanguageExtensionInterface;

class Language implements LanguageExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /** @var string[] */
    protected array $locales = [
        'aa_DJ', 'aa_ER', 'aa_ET',
        'af_NA', 'af_ZA', 'ak_GH',
        'am_ET', 'ar_AE', 'ar_BH', 'ar_DZ',
        'ar_EG', 'ar_IQ', 'ar_JO', 'ar_KW', 'ar_LB',
        'ar_LY', 'ar_MA', 'ar_OM', 'ar_QA', 'ar_SA',
        'ar_SD', 'ar_SY', 'ar_TN', 'ar_YE',
        'as_IN', 'az_AZ', 'be_BY',
        'bg_BG', 'bn_BD', 'bn_IN',
        'bo_CN', 'bo_IN', 'bs_BA',
        'byn_ER', 'ca_ES',
        'cch_NG', 'cs_CZ',
        'cy_GB', 'da_DK', 'de_AT',
        'de_BE', 'de_CH', 'de_DE', 'de_LI', 'de_LU',
        'dv_MV', 'dz_BT',
        'ee_GH', 'ee_TG', 'el_CY', 'el_GR',
        'en_AS', 'en_AU', 'en_BE', 'en_BW',
        'en_BZ', 'en_CA', 'en_GB', 'en_GU', 'en_HK',
        'en_IE', 'en_IN', 'en_JM', 'en_MH', 'en_MP',
        'en_MT', 'en_NA', 'en_NZ', 'en_PH', 'en_PK',
        'en_SG', 'en_TT', 'en_UM', 'en_US', 'en_VI',
        'en_ZA', 'en_ZW', 'es_AR',
        'es_BO', 'es_CL', 'es_CO', 'es_CR', 'es_DO',
        'es_EC', 'es_ES', 'es_GT', 'es_HN', 'es_MX',
        'es_NI', 'es_PA', 'es_PE', 'es_PR', 'es_PY',
        'es_SV', 'es_US', 'es_UY', 'es_VE',
        'et_EE', 'eu_ES', 'fa_AF',
        'fa_IR', 'fi_FI', 'fil_PH',
        'fo_FO', 'fr_BE', 'fr_CA',
        'fr_CH', 'fr_FR', 'fr_LU', 'fr_MC', 'fr_SN',
        'fur_IT', 'ga_IE',
        'gaa_GH', 'gez_ER', 'gez_ET',
        'gl_ES', 'gsw_CH', 'gu_IN',
        'gv_GB', 'ha_GH', 'ha_NE',
        'ha_NG', 'ha_SD', 'haw_US',
        'he_IL', 'hi_IN', 'hr_HR',
        'hu_HU', 'hy_AM',
        'id_ID', 'ig_NG',
        'ii_CN', 'is_IS',
        'it_CH', 'it_IT',
        'ja_JP', 'ka_GE', 'kaj_NG',
        'kam_KE', 'kcg_NG',
        'kfo_CI', 'kk_KZ', 'kl_GL',
        'km_KH', 'kn_IN',
        'ko_KR', 'kok_IN', 'kpe_GN',
        'kpe_LR', 'ku_IQ', 'ku_IR', 'ku_SY',
        'ku_TR', 'kw_GB', 'ky_KG',
        'ln_CD', 'ln_CG', 'lo_LA',
        'lt_LT', 'lv_LV',
        'mk_MK', 'ml_IN', 'mn_CN',
        'mn_MN', 'mr_IN',
        'ms_BN', 'ms_MY', 'mt_MT',
        'my_MM', 'nb_NO', 'nds_DE',
        'ne_IN', 'ne_NP', 'nl_BE',
        'nl_NL', 'nn_NO',
        'nr_ZA', 'nso_ZA', 'ny_MW',
        'oc_FR', 'om_ET', 'om_KE',
        'or_IN', 'pa_IN', 'pa_PK',
        'pl_PL', 'ps_AF',
        'pt_BR', 'pt_PT', 'ro_MD', 'ro_RO',
        'ru_RU', 'ru_UA', 'rw_RW',
        'sa_IN', 'se_FI', 'se_NO',
        'sh_BA', 'sh_CS', 'sh_YU',
        'si_LK', 'sid_ET', 'sk_SK',
        'sl_SI', 'so_DJ', 'so_ET',
        'so_KE', 'so_SO', 'sq_AL',
        'sr_BA', 'sr_CS', 'sr_ME', 'sr_RS', 'sr_YU',
        'ss_SZ', 'ss_ZA', 'st_LS',
        'st_ZA', 'sv_FI', 'sv_SE',
        'sw_KE', 'sw_TZ', 'syr_SY',
        'ta_IN', 'te_IN', 'tg_TJ',
        'th_TH', 'ti_ER', 'ti_ET',
        'tig_ER', 'tn_ZA',
        'to_TO', 'tr_TR',
        'trv_TW', 'ts_ZA', 'tt_RU',
        'ug_CN', 'uk_UA',
        'ur_IN', 'ur_PK', 'uz_AF', 'uz_UZ',
        've_ZA', 'vi_VN',
        'wal_ET', 'wo_SN', 'xh_ZA',
        'yo_NG', 'zh_CN', 'zh_HK',
        'zh_MO', 'zh_SG', 'zh_TW', 'zu_ZA',
    ];

    /**
     * @var string[]
     *
     * @see https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     */
    protected array $languageCodes = [
        'aa', 'ab', 'ae', 'af', 'ak', 'am', 'an', 'ar', 'as', 'av',
        'ay', 'az', 'ba', 'be', 'bg', 'bh', 'bi', 'bm', 'bn', 'bo',
        'br', 'bs', 'ca', 'ce', 'ch', 'co', 'cr', 'cs', 'cu', 'cv',
        'cy', 'da', 'de', 'dv', 'dz', 'ee', 'el', 'en', 'eo', 'es',
        'et', 'eu', 'fa', 'ff', 'fi', 'fj', 'fo', 'fr', 'fy', 'ga',
        'gd', 'gl', 'gn', 'gu', 'gv', 'ha', 'he', 'hi', 'ho', 'hr',
        'ht', 'hu', 'hy', 'hz', 'ia', 'id', 'ie', 'ig', 'ii', 'ik',
        'io', 'is', 'it', 'iu', 'ja', 'jv', 'ka', 'kg', 'ki', 'kj',
        'kk', 'kl', 'km', 'kn', 'ko', 'kr', 'ks', 'ku', 'kv', 'kw',
        'ky', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv',
        'mg', 'mh', 'mi', 'mk', 'ml', 'mn', 'mr', 'ms', 'mt', 'my',
        'na', 'nb', 'nd', 'ne', 'ng', 'nl', 'nn', 'no', 'nr', 'nv',
        'ny', 'oc', 'oj', 'om', 'or', 'os', 'pa', 'pi', 'pl', 'ps',
        'pt', 'qu', 'rm', 'rn', 'ro', 'ru', 'rw', 'sa', 'sc', 'sd',
        'se', 'sg', 'si', 'sk', 'sl', 'sm', 'sn', 'so', 'sq', 'sr',
        'ss', 'st', 'su', 'sv', 'sw', 'ta', 'te', 'tg', 'th', 'ti',
        'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty', 'ug',
        'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'wo', 'xh', 'yi',
        'yo', 'za', 'zh', 'zu',
    ];

    public function languageCode(): string
    {
        return $this->randomizer->randomElement($this->languageCodes);
    }

    public function locale(): string
    {
        return $this->randomizer->randomElement($this->locales);
    }
}
