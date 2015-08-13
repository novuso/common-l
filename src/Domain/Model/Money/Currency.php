<?php

namespace Novuso\Common\Domain\Model\Money;

use Novuso\System\Type\Enum;

/**
 * Currency represents an ISO 4217 currency
 *
 * @see       http://www.iso.org/iso/home/standards/currency_codes.htm
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
final class Currency extends Enum
{
    /**
     * UAE Dirham
     *
     * @var string
     */
    const AED = 'AED';

    /**
     * Afghani
     *
     * @var string
     */
    const AFN = 'AFN';

    /**
     * Lek
     *
     * @var string
     */
    const ALL = 'ALL';

    /**
     * Armenian Dram
     *
     * @var string
     */
    const AMD = 'AMD';

    /**
     * Netherlands Antillean Guilder
     *
     * @var string
     */
    const ANG = 'ANG';

    /**
     * Kwanza
     *
     * @var string
     */
    const AOA = 'AOA';

    /**
     * Argentine Peso
     *
     * @var string
     */
    const ARS = 'ARS';

    /**
     * Australian Dollar
     *
     * @var string
     */
    const AUD = 'AUD';

    /**
     * Aruban Florin
     *
     * @var string
     */
    const AWG = 'AWG';

    /**
     * Azerbaijanian Manat
     *
     * @var string
     */
    const AZN = 'AZN';

    /**
     * Convertible Mark
     *
     * @var string
     */
    const BAM = 'BAM';

    /**
     * Barbados Dollar
     *
     * @var string
     */
    const BBD = 'BBD';

    /**
     * Taka
     *
     * @var string
     */
    const BDT = 'BDT';

    /**
     * Bulgarian Lev
     *
     * @var string
     */
    const BGN = 'BGN';

    /**
     * Bahraini Dinar
     *
     * @var string
     */
    const BHD = 'BHD';

    /**
     * Burundi Franc
     *
     * @var string
     */
    const BIF = 'BIF';

    /**
     * Bermudian Dollar
     *
     * @var string
     */
    const BMD = 'BMD';

    /**
     * Brunei Dollar
     *
     * @var string
     */
    const BND = 'BND';

    /**
     * Boliviano
     *
     * @var string
     */
    const BOB = 'BOB';

    /**
     * Mvdol
     *
     * @var string
     */
    const BOV = 'BOV';

    /**
     * Brazilian Real
     *
     * @var string
     */
    const BRL = 'BRL';

    /**
     * Bahamian Dollar
     *
     * @var string
     */
    const BSD = 'BSD';

    /**
     * Ngultrum
     *
     * @var string
     */
    const BTN = 'BTN';

    /**
     * Pula
     *
     * @var string
     */
    const BWP = 'BWP';

    /**
     * Belarussian Ruble
     *
     * @var string
     */
    const BYR = 'BYR';

    /**
     * Belize Dollar
     *
     * @var string
     */
    const BZD = 'BZD';

    /**
     * Canadian Dollar
     *
     * @var string
     */
    const CAD = 'CAD';

    /**
     * Congolese Franc
     *
     * @var string
     */
    const CDF = 'CDF';

    /**
     * WIR Euro
     *
     * @var string
     */
    const CHE = 'CHE';

    /**
     * Swiss Franc
     *
     * @var string
     */
    const CHF = 'CHF';

    /**
     * WIR Franc
     *
     * @var string
     */
    const CHW = 'CHW';

    /**
     * Unidad de Fomento
     *
     * @var string
     */
    const CLF = 'CLF';

    /**
     * Chilean Peso
     *
     * @var string
     */
    const CLP = 'CLP';

    /**
     * Yuan Renminbi
     *
     * @var string
     */
    const CNY = 'CNY';

    /**
     * Colombian Peso
     *
     * @var string
     */
    const COP = 'COP';

    /**
     * Unidad de Valor Real
     *
     * @var string
     */
    const COU = 'COU';

    /**
     * Costa Rican Colon
     *
     * @var string
     */
    const CRC = 'CRC';

    /**
     * Peso Convertible
     *
     * @var string
     */
    const CUC = 'CUC';

    /**
     * Cuban Peso
     *
     * @var string
     */
    const CUP = 'CUP';

    /**
     * Cabo Verde Escudo
     *
     * @var string
     */
    const CVE = 'CVE';

    /**
     * Czech Koruna
     *
     * @var string
     */
    const CZK = 'CZK';

    /**
     * Djibouti Franc
     *
     * @var string
     */
    const DJF = 'DJF';

    /**
     * Danish Krone
     *
     * @var string
     */
    const DKK = 'DKK';

    /**
     * Dominican Peso
     *
     * @var string
     */
    const DOP = 'DOP';

    /**
     * Algerian Dinar
     *
     * @var string
     */
    const DZD = 'DZD';

    /**
     * Egyptian Pound
     *
     * @var string
     */
    const EGP = 'EGP';

    /**
     * Nakfa
     *
     * @var string
     */
    const ERN = 'ERN';

    /**
     * Ethiopian Birr
     *
     * @var string
     */
    const ETB = 'ETB';

    /**
     * Euro
     *
     * @var string
     */
    const EUR = 'EUR';

    /**
     * Fiji Dollar
     *
     * @var string
     */
    const FJD = 'FJD';

    /**
     * Falkland Islands Pound
     *
     * @var string
     */
    const FKP = 'FKP';

    /**
     * Pound Sterling
     *
     * @var string
     */
    const GBP = 'GBP';

    /**
     * Lari
     *
     * @var string
     */
    const GEL = 'GEL';

    /**
     * Ghana Cedi
     *
     * @var string
     */
    const GHS = 'GHS';

    /**
     * Gibraltar Pound
     *
     * @var string
     */
    const GIP = 'GIP';

    /**
     * Dalasi
     *
     * @var string
     */
    const GMD = 'GMD';

    /**
     * Guinea Franc
     *
     * @var string
     */
    const GNF = 'GNF';

    /**
     * Quetzal
     *
     * @var string
     */
    const GTQ = 'GTQ';

    /**
     * Guyana Dollar
     *
     * @var string
     */
    const GYD = 'GYD';

    /**
     * Hong Kong Dollar
     *
     * @var string
     */
    const HKD = 'HKD';

    /**
     * Lempira
     *
     * @var string
     */
    const HNL = 'HNL';

    /**
     * Kuna
     *
     * @var string
     */
    const HRK = 'HRK';

    /**
     * Gourde
     *
     * @var string
     */
    const HTG = 'HTG';

    /**
     * Forint
     *
     * @var string
     */
    const HUF = 'HUF';

    /**
     * Rupiah
     *
     * @var string
     */
    const IDR = 'IDR';

    /**
     * New Israeli Sheqel
     *
     * @var string
     */
    const ILS = 'ILS';

    /**
     * Indian Rupee
     *
     * @var string
     */
    const INR = 'INR';

    /**
     * Iraqi Dinar
     *
     * @var string
     */
    const IQD = 'IQD';

    /**
     * Iranian Rial
     *
     * @var string
     */
    const IRR = 'IRR';

    /**
     * Iceland Krona
     *
     * @var string
     */
    const ISK = 'ISK';

    /**
     * Jamaican Dollar
     *
     * @var string
     */
    const JMD = 'JMD';

    /**
     * Jordanian Dinar
     *
     * @var string
     */
    const JOD = 'JOD';

    /**
     * Yen
     *
     * @var string
     */
    const JPY = 'JPY';

    /**
     * Kenyan Shilling
     *
     * @var string
     */
    const KES = 'KES';

    /**
     * Som
     *
     * @var string
     */
    const KGS = 'KGS';

    /**
     * Riel
     *
     * @var string
     */
    const KHR = 'KHR';

    /**
     * Comoro Franc
     *
     * @var string
     */
    const KMF = 'KMF';

    /**
     * North Korean Won
     *
     * @var string
     */
    const KPW = 'KPW';

    /**
     * Won
     *
     * @var string
     */
    const KRW = 'KRW';

    /**
     * Kuwaiti Dinar
     *
     * @var string
     */
    const KWD = 'KWD';

    /**
     * Cayman Islands Dollar
     *
     * @var string
     */
    const KYD = 'KYD';

    /**
     * Tenge
     *
     * @var string
     */
    const KZT = 'KZT';

    /**
     * Kip
     *
     * @var string
     */
    const LAK = 'LAK';

    /**
     * Lebanese Pound
     *
     * @var string
     */
    const LBP = 'LBP';

    /**
     * Sri Lanka Rupee
     *
     * @var string
     */
    const LKR = 'LKR';

    /**
     * Liberian Dollar
     *
     * @var string
     */
    const LRD = 'LRD';

    /**
     * Loti
     *
     * @var string
     */
    const LSL = 'LSL';

    /**
     * Lithuanian Litas
     *
     * @var string
     */
    const LTL = 'LTL';

    /**
     * Latvian Lats
     *
     * @var string
     */
    const LVL = 'LVL';

    /**
     * Libyan Dinar
     *
     * @var string
     */
    const LYD = 'LYD';

    /**
     * Moroccan Dirham
     *
     * @var string
     */
    const MAD = 'MAD';

    /**
     * Moldovan Leu
     *
     * @var string
     */
    const MDL = 'MDL';

    /**
     * Malagasy Ariary
     *
     * @var string
     */
    const MGA = 'MGA';

    /**
     * Denar
     *
     * @var string
     */
    const MKD = 'MKD';

    /**
     * Kyat
     *
     * @var string
     */
    const MMK = 'MMK';

    /**
     * Tugrik
     *
     * @var string
     */
    const MNT = 'MNT';

    /**
     * Pataca
     *
     * @var string
     */
    const MOP = 'MOP';

    /**
     * Ouguiya
     *
     * @var string
     */
    const MRO = 'MRO';

    /**
     * Mauritius Rupee
     *
     * @var string
     */
    const MUR = 'MUR';

    /**
     * Rufiyaa
     *
     * @var string
     */
    const MVR = 'MVR';

    /**
     * Kwacha
     *
     * @var string
     */
    const MWK = 'MWK';

    /**
     * Mexican Peso
     *
     * @var string
     */
    const MXN = 'MXN';

    /**
     * Mexican Unidad de Inversion (UDI)
     *
     * @var string
     */
    const MXV = 'MXV';

    /**
     * Malaysian Ringgit
     *
     * @var string
     */
    const MYR = 'MYR';

    /**
     * Mozambique Metical
     *
     * @var string
     */
    const MZN = 'MZN';

    /**
     * Namibia Dollar
     *
     * @var string
     */
    const NAD = 'NAD';

    /**
     * Naira
     *
     * @var string
     */
    const NGN = 'NGN';

    /**
     * Cordoba Oro
     *
     * @var string
     */
    const NIO = 'NIO';

    /**
     * Norwegian Krone
     *
     * @var string
     */
    const NOK = 'NOK';

    /**
     * Nepalese Rupee
     *
     * @var string
     */
    const NPR = 'NPR';

    /**
     * New Zealand Dollar
     *
     * @var string
     */
    const NZD = 'NZD';

    /**
     * Rial Omani
     *
     * @var string
     */
    const OMR = 'OMR';

    /**
     * Balboa
     *
     * @var string
     */
    const PAB = 'PAB';

    /**
     * Nuevo Sol
     *
     * @var string
     */
    const PEN = 'PEN';

    /**
     * Kina
     *
     * @var string
     */
    const PGK = 'PGK';

    /**
     * Philippine Peso
     *
     * @var string
     */
    const PHP = 'PHP';

    /**
     * Pakistan Rupee
     *
     * @var string
     */
    const PKR = 'PKR';

    /**
     * Zloty
     *
     * @var string
     */
    const PLN = 'PLN';

    /**
     * Guarani
     *
     * @var string
     */
    const PYG = 'PYG';

    /**
     * Qatari Rial
     *
     * @var string
     */
    const QAR = 'QAR';

    /**
     * Romanian Leu
     *
     * @var string
     */
    const RON = 'RON';

    /**
     * Serbian Dinar
     *
     * @var string
     */
    const RSD = 'RSD';

    /**
     * Russian Ruble
     *
     * @var string
     */
    const RUB = 'RUB';

    /**
     * Rwanda Franc
     *
     * @var string
     */
    const RWF = 'RWF';

    /**
     * Saudi Riyal
     *
     * @var string
     */
    const SAR = 'SAR';

    /**
     * Solomon Islands Dollar
     *
     * @var string
     */
    const SBD = 'SBD';

    /**
     * Seychelles Rupee
     *
     * @var string
     */
    const SCR = 'SCR';

    /**
     * Sudanese Pound
     *
     * @var string
     */
    const SDG = 'SDG';

    /**
     * Swedish Krona
     *
     * @var string
     */
    const SEK = 'SEK';

    /**
     * Singapore Dollar
     *
     * @var string
     */
    const SGD = 'SGD';

    /**
     * Saint Helena Pound
     *
     * @var string
     */
    const SHP = 'SHP';

    /**
     * Leone
     *
     * @var string
     */
    const SLL = 'SLL';

    /**
     * Somali Shilling
     *
     * @var string
     */
    const SOS = 'SOS';

    /**
     * Surinam Dollar
     *
     * @var string
     */
    const SRD = 'SRD';

    /**
     * South Sudanese Pound
     *
     * @var string
     */
    const SSP = 'SSP';

    /**
     * Dobra
     *
     * @var string
     */
    const STD = 'STD';

    /**
     * El Salvador Colon
     *
     * @var string
     */
    const SVC = 'SVC';

    /**
     * Syrian Pound
     *
     * @var string
     */
    const SYP = 'SYP';

    /**
     * Lilangeni
     *
     * @var string
     */
    const SZL = 'SZL';

    /**
     * Baht
     *
     * @var string
     */
    const THB = 'THB';

    /**
     * Somoni
     *
     * @var string
     */
    const TJS = 'TJS';

    /**
     * Turkmenistan New Manat
     *
     * @var string
     */
    const TMT = 'TMT';

    /**
     * Tunisian Dinar
     *
     * @var string
     */
    const TND = 'TND';

    /**
     * Pa’anga
     *
     * @var string
     */
    const TOP = 'TOP';

    /**
     * Turkish Lira
     *
     * TRY is a reserved word.
     *
     * @var string
     */
    const _TRY = 'TRY';

    /**
     * Trinidad and Tobago Dollar
     *
     * @var string
     */
    const TTD = 'TTD';

    /**
     * New Taiwan Dollar
     *
     * @var string
     */
    const TWD = 'TWD';

    /**
     * Tanzanian Shilling
     *
     * @var string
     */
    const TZS = 'TZS';

    /**
     * Hryvnia
     *
     * @var string
     */
    const UAH = 'UAH';

    /**
     * Uganda Shilling
     *
     * @var string
     */
    const UGX = 'UGX';

    /**
     * US Dollar
     *
     * @var string
     */
    const USD = 'USD';

    /**
     * US Dollar (Next day)
     *
     * @var string
     */
    const USN = 'USN';

    /**
     * Uruguay Peso en Unidades Indexadas (URUIURUI)
     *
     * @var string
     */
    const UYI = 'UYI';

    /**
     * Peso Uruguayo
     *
     * @var string
     */
    const UYU = 'UYU';

    /**
     * Uzbekistan Sum
     *
     * @var string
     */
    const UZS = 'UZS';

    /**
     * Bolivar
     *
     * @var string
     */
    const VEF = 'VEF';

    /**
     * Dong
     *
     * @var string
     */
    const VND = 'VND';

    /**
     * Vatu
     *
     * @var string
     */
    const VUV = 'VUV';

    /**
     * Tala
     *
     * @var string
     */
    const WST = 'WST';

    /**
     * CFA Franc BEAC
     *
     * @var string
     */
    const XAF = 'XAF';

    /**
     * Silver
     *
     * @var string
     */
    const XAG = 'XAG';

    /**
     * Gold
     *
     * @var string
     */
    const XAU = 'XAU';

    /**
     * Bond Markets Unit European Composite Unit (EURCO)
     *
     * @var string
     */
    const XBA = 'XBA';

    /**
     * Bond Markets Unit European Monetary Unit (E.M.U.-6)
     *
     * @var string
     */
    const XBB = 'XBB';

    /**
     * Bond Markets Unit European Unit of Account 9 (E.U.A.-9)
     *
     * @var string
     */
    const XBC = 'XBC';

    /**
     * Bond Markets Unit European Unit of Account 17 (E.U.A.-17)
     *
     * @var string
     */
    const XBD = 'XBD';

    /**
     * East Caribbean Dollar
     *
     * @var string
     */
    const XCD = 'XCD';

    /**
     * SDR (Special Drawing Right)
     *
     * @var string
     */
    const XDR = 'XDR';

    /**
     * CFA Franc BCEAO
     *
     * @var string
     */
    const XOF = 'XOF';

    /**
     * Palladium
     *
     * @var string
     */
    const XPD = 'XPD';

    /**
     * CFP Franc
     *
     * @var string
     */
    const XPF = 'XPF';

    /**
     * Platinum
     *
     * @var string
     */
    const XPT = 'XPT';

    /**
     * Sucre
     *
     * @var string
     */
    const XSU = 'XSU';

    /**
     * Codes specifically reserved for testing purposes
     *
     * @var string
     */
    const XTS = 'XTS';

    /**
     * ADB Unit of Account
     *
     * @var string
     */
    const XUA = 'XUA';

    /**
     * The codes assigned for transactions where no currency is involved
     *
     * @var string
     */
    const XXX = 'XXX';

    /**
     * Yemeni Rial
     *
     * @var string
     */
    const YER = 'YER';

    /**
     * Rand
     *
     * @var string
     */
    const ZAR = 'ZAR';

    /**
     * Zambian Kwacha
     *
     * @var string
     */
    const ZMW = 'ZMW';

    /**
     * Zimbabwe Dollar
     *
     * @var string
     */
    const ZWL = 'ZWL';

    /**
     * Currency data
     *
     * @var array
     */
    private static $currencies = [
        'AED' => [
            'display' => 'UAE Dirham',
            'code'    => 'AED',
            'numeric' => 784,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AFN' => [
            'display' => 'Afghani',
            'code'    => 'AFN',
            'numeric' => 971,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ALL' => [
            'display' => 'Lek',
            'code'    => 'ALL',
            'numeric' => 8,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AMD' => [
            'display' => 'Armenian Dram',
            'code'    => 'AMD',
            'numeric' => 51,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ANG' => [
            'display' => 'Netherlands Antillean Guilder',
            'code'    => 'ANG',
            'numeric' => 532,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AOA' => [
            'display' => 'Kwanza',
            'code'    => 'AOA',
            'numeric' => 973,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ARS' => [
            'display' => 'Argentine Peso',
            'code'    => 'ARS',
            'numeric' => 32,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AUD' => [
            'display' => 'Australian Dollar',
            'code'    => 'AUD',
            'numeric' => 36,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AWG' => [
            'display' => 'Aruban Florin',
            'code'    => 'AWG',
            'numeric' => 533,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AZN' => [
            'display' => 'Azerbaijanian Manat',
            'code'    => 'AZN',
            'numeric' => 944,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BAM' => [
            'display' => 'Convertible Mark',
            'code'    => 'BAM',
            'numeric' => 977,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BBD' => [
            'display' => 'Barbados Dollar',
            'code'    => 'BBD',
            'numeric' => 52,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BDT' => [
            'display' => 'Taka',
            'code'    => 'BDT',
            'numeric' => 50,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BGN' => [
            'display' => 'Bulgarian Lev',
            'code'    => 'BGN',
            'numeric' => 975,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BHD' => [
            'display' => 'Bahraini Dinar',
            'code'    => 'BHD',
            'numeric' => 48,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'BIF' => [
            'display' => 'Burundi Franc',
            'code'    => 'BIF',
            'numeric' => 108,
            'digits'  => 0,
            'minor'   => 100
        ],
        'BMD' => [
            'display' => 'Bermudian Dollar',
            'code'    => 'BMD',
            'numeric' => 60,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BND' => [
            'display' => 'Brunei Dollar',
            'code'    => 'BND',
            'numeric' => 96,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BOB' => [
            'display' => 'Boliviano',
            'code'    => 'BOB',
            'numeric' => 68,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BOV' => [
            'display' => 'Mvdol',
            'code'    => 'BOV',
            'numeric' => 984,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BRL' => [
            'display' => 'Brazilian Real',
            'code'    => 'BRL',
            'numeric' => 986,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BSD' => [
            'display' => 'Bahamian Dollar',
            'code'    => 'BSD',
            'numeric' => 44,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BTN' => [
            'display' => 'Ngultrum',
            'code'    => 'BTN',
            'numeric' => 64,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BWP' => [
            'display' => 'Pula',
            'code'    => 'BWP',
            'numeric' => 72,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BYR' => [
            'display' => 'Belarussian Ruble',
            'code'    => 'BYR',
            'numeric' => 974,
            'digits'  => 0,
            'minor'   => 100
        ],
        'BZD' => [
            'display' => 'Belize Dollar',
            'code'    => 'BZD',
            'numeric' => 84,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CAD' => [
            'display' => 'Canadian Dollar',
            'code'    => 'CAD',
            'numeric' => 124,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CDF' => [
            'display' => 'Congolese Franc',
            'code'    => 'CDF',
            'numeric' => 976,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHE' => [
            'display' => 'WIR Euro',
            'code'    => 'CHE',
            'numeric' => 947,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHF' => [
            'display' => 'Swiss Franc',
            'code'    => 'CHF',
            'numeric' => 756,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHW' => [
            'display' => 'WIR Franc',
            'code'    => 'CHW',
            'numeric' => 948,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CLF' => [
            'display' => 'Unidad de Fomento',
            'code'    => 'CLF',
            'numeric' => 990,
            'digits'  => 4,
            'minor'   => 100
        ],
        'CLP' => [
            'display' => 'Chilean Peso',
            'code'    => 'CLP',
            'numeric' => 152,
            'digits'  => 0,
            'minor'   => 100
        ],
        'CNY' => [
            'display' => 'Yuan Renminbi',
            'code'    => 'CNY',
            'numeric' => 156,
            'digits'  => 2,
            'minor'   => 100
        ],
        'COP' => [
            'display' => 'Colombian Peso',
            'code'    => 'COP',
            'numeric' => 170,
            'digits'  => 2,
            'minor'   => 100
        ],
        'COU' => [
            'display' => 'Unidad de Valor Real',
            'code'    => 'COU',
            'numeric' => 970,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CRC' => [
            'display' => 'Costa Rican Colon',
            'code'    => 'CRC',
            'numeric' => 188,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CUC' => [
            'display' => 'Peso Convertible',
            'code'    => 'CUC',
            'numeric' => 931,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CUP' => [
            'display' => 'Cuban Peso',
            'code'    => 'CUP',
            'numeric' => 192,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CVE' => [
            'display' => 'Cabo Verde Escudo',
            'code'    => 'CVE',
            'numeric' => 132,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CZK' => [
            'display' => 'Czech Koruna',
            'code'    => 'CZK',
            'numeric' => 203,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DJF' => [
            'display' => 'Djibouti Franc',
            'code'    => 'DJF',
            'numeric' => 262,
            'digits'  => 0,
            'minor'   => 100
        ],
        'DKK' => [
            'display' => 'Danish Krone',
            'code'    => 'DKK',
            'numeric' => 208,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DOP' => [
            'display' => 'Dominican Peso',
            'code'    => 'DOP',
            'numeric' => 214,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DZD' => [
            'display' => 'Algerian Dinar',
            'code'    => 'DZD',
            'numeric' => 12,
            'digits'  => 2,
            'minor'   => 100
        ],
        'EGP' => [
            'display' => 'Egyptian Pound',
            'code'    => 'EGP',
            'numeric' => 818,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ERN' => [
            'display' => 'Nakfa',
            'code'    => 'ERN',
            'numeric' => 232,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ETB' => [
            'display' => 'Ethiopian Birr',
            'code'    => 'ETB',
            'numeric' => 230,
            'digits'  => 2,
            'minor'   => 100
        ],
        'EUR' => [
            'display' => 'Euro',
            'code'    => 'EUR',
            'numeric' => 978,
            'digits'  => 2,
            'minor'   => 100
        ],
        'FJD' => [
            'display' => 'Fiji Dollar',
            'code'    => 'FJD',
            'numeric' => 242,
            'digits'  => 2,
            'minor'   => 100
        ],
        'FKP' => [
            'display' => 'Falkland Islands Pound',
            'code'    => 'FKP',
            'numeric' => 238,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GBP' => [
            'display' => 'Pound Sterling',
            'code'    => 'GBP',
            'numeric' => 826,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GEL' => [
            'display' => 'Lari',
            'code'    => 'GEL',
            'numeric' => 981,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GHS' => [
            'display' => 'Ghana Cedi',
            'code'    => 'GHS',
            'numeric' => 936,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GIP' => [
            'display' => 'Gibraltar Pound',
            'code'    => 'GIP',
            'numeric' => 292,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GMD' => [
            'display' => 'Dalasi',
            'code'    => 'GMD',
            'numeric' => 270,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GNF' => [
            'display' => 'Guinea Franc',
            'code'    => 'GNF',
            'numeric' => 324,
            'digits'  => 0,
            'minor'   => 100
        ],
        'GTQ' => [
            'display' => 'Quetzal',
            'code'    => 'GTQ',
            'numeric' => 320,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GYD' => [
            'display' => 'Guyana Dollar',
            'code'    => 'GYD',
            'numeric' => 328,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HKD' => [
            'display' => 'Hong Kong Dollar',
            'code'    => 'HKD',
            'numeric' => 344,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HNL' => [
            'display' => 'Lempira',
            'code'    => 'HNL',
            'numeric' => 340,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HRK' => [
            'display' => 'Kuna',
            'code'    => 'HRK',
            'numeric' => 191,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HTG' => [
            'display' => 'Gourde',
            'code'    => 'HTG',
            'numeric' => 332,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HUF' => [
            'display' => 'Forint',
            'code'    => 'HUF',
            'numeric' => 348,
            'digits'  => 2,
            'minor'   => 100
        ],
        'IDR' => [
            'display' => 'Rupiah',
            'code'    => 'IDR',
            'numeric' => 360,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ILS' => [
            'display' => 'New Israeli Sheqel',
            'code'    => 'ILS',
            'numeric' => 376,
            'digits'  => 2,
            'minor'   => 100
        ],
        'INR' => [
            'display' => 'Indian Rupee',
            'code'    => 'INR',
            'numeric' => 356,
            'digits'  => 2,
            'minor'   => 100
        ],
        'IQD' => [
            'display' => 'Iraqi Dinar',
            'code'    => 'IQD',
            'numeric' => 368,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'IRR' => [
            'display' => 'Iranian Rial',
            'code'    => 'IRR',
            'numeric' => 364,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ISK' => [
            'display' => 'Iceland Krona',
            'code'    => 'ISK',
            'numeric' => 352,
            'digits'  => 0,
            'minor'   => 100
        ],
        'JMD' => [
            'display' => 'Jamaican Dollar',
            'code'    => 'JMD',
            'numeric' => 388,
            'digits'  => 2,
            'minor'   => 100
        ],
        'JOD' => [
            'display' => 'Jordanian Dinar',
            'code'    => 'JOD',
            'numeric' => 400,
            'digits'  => 3,
            'minor'   => 100
        ],
        'JPY' => [
            'display' => 'Yen',
            'code'    => 'JPY',
            'numeric' => 392,
            'digits'  => 0,
            'minor'   => 1
        ],
        'KES' => [
            'display' => 'Kenyan Shilling',
            'code'    => 'KES',
            'numeric' => 404,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KGS' => [
            'display' => 'Som',
            'code'    => 'KGS',
            'numeric' => 417,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KHR' => [
            'display' => 'Riel',
            'code'    => 'KHR',
            'numeric' => 116,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KMF' => [
            'display' => 'Comoro Franc',
            'code'    => 'KMF',
            'numeric' => 174,
            'digits'  => 0,
            'minor'   => 100
        ],
        'KPW' => [
            'display' => 'North Korean Won',
            'code'    => 'KPW',
            'numeric' => 408,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KRW' => [
            'display' => 'Won',
            'code'    => 'KRW',
            'numeric' => 410,
            'digits'  => 0,
            'minor'   => 100
        ],
        'KWD' => [
            'display' => 'Kuwaiti Dinar',
            'code'    => 'KWD',
            'numeric' => 414,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'KYD' => [
            'display' => 'Cayman Islands Dollar',
            'code'    => 'KYD',
            'numeric' => 136,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KZT' => [
            'display' => 'Tenge',
            'code'    => 'KZT',
            'numeric' => 398,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LAK' => [
            'display' => 'Kip',
            'code'    => 'LAK',
            'numeric' => 418,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LBP' => [
            'display' => 'Lebanese Pound',
            'code'    => 'LBP',
            'numeric' => 422,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LKR' => [
            'display' => 'Sri Lanka Rupee',
            'code'    => 'LKR',
            'numeric' => 144,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LRD' => [
            'display' => 'Liberian Dollar',
            'code'    => 'LRD',
            'numeric' => 430,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LSL' => [
            'display' => 'Loti',
            'code'    => 'LSL',
            'numeric' => 426,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LTL' => [
            'display' => 'Lithuanian Litas',
            'code'    => 'LTL',
            'numeric' => 440,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LVL' => [
            'display' => 'Latvian Lats',
            'code'    => 'LVL',
            'numeric' => 428,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LYD' => [
            'display' => 'Libyan Dinar',
            'code'    => 'LYD',
            'numeric' => 434,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'MAD' => [
            'display' => 'Moroccan Dirham',
            'code'    => 'MAD',
            'numeric' => 504,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MDL' => [
            'display' => 'Moldovan Leu',
            'code'    => 'MDL',
            'numeric' => 498,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MGA' => [
            'display' => 'Malagasy Ariary',
            'code'    => 'MGA',
            'numeric' => 969,
            'digits'  => 1,
            'minor'   => 5
        ],
        'MKD' => [
            'display' => 'Denar',
            'code'    => 'MKD',
            'numeric' => 807,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MMK' => [
            'display' => 'Kyat',
            'code'    => 'MMK',
            'numeric' => 104,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MNT' => [
            'display' => 'Tugrik',
            'code'    => 'MNT',
            'numeric' => 496,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MOP' => [
            'display' => 'Pataca',
            'code'    => 'MOP',
            'numeric' => 446,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MRO' => [
            'display' => 'Ouguiya',
            'code'    => 'MRO',
            'numeric' => 478,
            'digits'  => 1,
            'minor'   => 5
        ],
        'MUR' => [
            'display' => 'Mauritius Rupee',
            'code'    => 'MUR',
            'numeric' => 480,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MVR' => [
            'display' => 'Rufiyaa',
            'code'    => 'MVR',
            'numeric' => 462,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MWK' => [
            'display' => 'Kwacha',
            'code'    => 'MWK',
            'numeric' => 454,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MXN' => [
            'display' => 'Mexican Peso',
            'code'    => 'MXN',
            'numeric' => 484,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MXV' => [
            'display' => 'Mexican Unidad de Inversion (UDI)',
            'code'    => 'MXV',
            'numeric' => 979,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MYR' => [
            'display' => 'Malaysian Ringgit',
            'code'    => 'MYR',
            'numeric' => 458,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MZN' => [
            'display' => 'Mozambique Metical',
            'code'    => 'MZN',
            'numeric' => 943,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NAD' => [
            'display' => 'Namibia Dollar',
            'code'    => 'NAD',
            'numeric' => 516,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NGN' => [
            'display' => 'Naira',
            'code'    => 'NGN',
            'numeric' => 566,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NIO' => [
            'display' => 'Cordoba Oro',
            'code'    => 'NIO',
            'numeric' => 558,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NOK' => [
            'display' => 'Norwegian Krone',
            'code'    => 'NOK',
            'numeric' => 578,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NPR' => [
            'display' => 'Nepalese Rupee',
            'code'    => 'NPR',
            'numeric' => 524,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NZD' => [
            'display' => 'New Zealand Dollar',
            'code'    => 'NZD',
            'numeric' => 554,
            'digits'  => 2,
            'minor'   => 100
        ],
        'OMR' => [
            'display' => 'Rial Omani',
            'code'    => 'OMR',
            'numeric' => 512,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'PAB' => [
            'display' => 'Balboa',
            'code'    => 'PAB',
            'numeric' => 590,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PEN' => [
            'display' => 'Nuevo Sol',
            'code'    => 'PEN',
            'numeric' => 604,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PGK' => [
            'display' => 'Kina',
            'code'    => 'PGK',
            'numeric' => 598,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PHP' => [
            'display' => 'Philippine Peso',
            'code'    => 'PHP',
            'numeric' => 608,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PKR' => [
            'display' => 'Pakistan Rupee',
            'code'    => 'PKR',
            'numeric' => 586,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PLN' => [
            'display' => 'Zloty',
            'code'    => 'PLN',
            'numeric' => 985,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PYG' => [
            'display' => 'Guarani',
            'code'    => 'PYG',
            'numeric' => 600,
            'digits'  => 0,
            'minor'   => 100
        ],
        'QAR' => [
            'display' => 'Qatari Rial',
            'code'    => 'QAR',
            'numeric' => 634,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RON' => [
            'display' => 'Romanian Leu',
            'code'    => 'RON',
            'numeric' => 946,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RSD' => [
            'display' => 'Serbian Dinar',
            'code'    => 'RSD',
            'numeric' => 941,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RUB' => [
            'display' => 'Russian Ruble',
            'code'    => 'RUB',
            'numeric' => 643,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RWF' => [
            'display' => 'Rwanda Franc',
            'code'    => 'RWF',
            'numeric' => 646,
            'digits'  => 0,
            'minor'   => 100
        ],
        'SAR' => [
            'display' => 'Saudi Riyal',
            'code'    => 'SAR',
            'numeric' => 682,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SBD' => [
            'display' => 'Solomon Islands Dollar',
            'code'    => 'SBD',
            'numeric' => 90,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SCR' => [
            'display' => 'Seychelles Rupee',
            'code'    => 'SCR',
            'numeric' => 690,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SDG' => [
            'display' => 'Sudanese Pound',
            'code'    => 'SDG',
            'numeric' => 938,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SEK' => [
            'display' => 'Swedish Krona',
            'code'    => 'SEK',
            'numeric' => 752,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SGD' => [
            'display' => 'Singapore Dollar',
            'code'    => 'SGD',
            'numeric' => 702,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SHP' => [
            'display' => 'Saint Helena Pound',
            'code'    => 'SHP',
            'numeric' => 654,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SLL' => [
            'display' => 'Leone',
            'code'    => 'SLL',
            'numeric' => 694,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SOS' => [
            'display' => 'Somali Shilling',
            'code'    => 'SOS',
            'numeric' => 706,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SRD' => [
            'display' => 'Surinam Dollar',
            'code'    => 'SRD',
            'numeric' => 968,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SSP' => [
            'display' => 'South Sudanese Pound',
            'code'    => 'SSP',
            'numeric' => 728,
            'digits'  => 2,
            'minor'   => 100
        ],
        'STD' => [
            'display' => 'Dobra',
            'code'    => 'STD',
            'numeric' => 678,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SVC' => [
            'display' => 'El Salvador Colon',
            'code'    => 'SVC',
            'numeric' => 222,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SYP' => [
            'display' => 'Syrian Pound',
            'code'    => 'SYP',
            'numeric' => 760,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SZL' => [
            'display' => 'Lilangeni',
            'code'    => 'SZL',
            'numeric' => 748,
            'digits'  => 2,
            'minor'   => 100
        ],
        'THB' => [
            'display' => 'Baht',
            'code'    => 'THB',
            'numeric' => 764,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TJS' => [
            'display' => 'Somoni',
            'code'    => 'TJS',
            'numeric' => 972,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TMT' => [
            'display' => 'Turkmenistan New Manat',
            'code'    => 'TMT',
            'numeric' => 934,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TND' => [
            'display' => 'Tunisian Dinar',
            'code'    => 'TND',
            'numeric' => 788,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'TOP' => [
            'display' => 'Pa’anga',
            'code'    => 'TOP',
            'numeric' => 776,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TRY' => [
            'display' => 'Turkish Lira',
            'code'    => 'TRY',
            'numeric' => 949,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TTD' => [
            'display' => 'Trinidad and Tobago Dollar',
            'code'    => 'TTD',
            'numeric' => 780,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TWD' => [
            'display' => 'New Taiwan Dollar',
            'code'    => 'TWD',
            'numeric' => 901,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TZS' => [
            'display' => 'Tanzanian Shilling',
            'code'    => 'TZS',
            'numeric' => 834,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UAH' => [
            'display' => 'Hryvnia',
            'code'    => 'UAH',
            'numeric' => 980,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UGX' => [
            'display' => 'Uganda Shilling',
            'code'    => 'UGX',
            'numeric' => 800,
            'digits'  => 0,
            'minor'   => 100
        ],
        'USD' => [
            'display' => 'US Dollar',
            'code'    => 'USD',
            'numeric' => 840,
            'digits'  => 2,
            'minor'   => 100
        ],
        'USN' => [
            'display' => 'US Dollar (Next day)',
            'code'    => 'USN',
            'numeric' => 997,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UYI' => [
            'display' => 'Uruguay Peso en Unidades Indexadas (URUIURUI)',
            'code'    => 'UYI',
            'numeric' => 940,
            'digits'  => 0,
            'minor'   => 100
        ],
        'UYU' => [
            'display' => 'Peso Uruguayo',
            'code'    => 'UYU',
            'numeric' => 858,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UZS' => [
            'display' => 'Uzbekistan Sum',
            'code'    => 'UZS',
            'numeric' => 860,
            'digits'  => 2,
            'minor'   => 100
        ],
        'VEF' => [
            'display' => 'Bolivar',
            'code'    => 'VEF',
            'numeric' => 937,
            'digits'  => 2,
            'minor'   => 100
        ],
        'VND' => [
            'display' => 'Dong',
            'code'    => 'VND',
            'numeric' => 704,
            'digits'  => 0,
            'minor'   => 10
        ],
        'VUV' => [
            'display' => 'Vatu',
            'code'    => 'VUV',
            'numeric' => 548,
            'digits'  => 0,
            'minor'   => 1
        ],
        'WST' => [
            'display' => 'Tala',
            'code'    => 'WST',
            'numeric' => 882,
            'digits'  => 2,
            'minor'   => 100
        ],
        'XAF' => [
            'display' => 'CFA Franc BEAC',
            'code'    => 'XAF',
            'numeric' => 950,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XAG' => [
            'display' => 'Silver',
            'code'    => 'XAG',
            'numeric' => 961,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XAU' => [
            'display' => 'Gold',
            'code'    => 'XAU',
            'numeric' => 959,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBA' => [
            'display' => 'Bond Markets Unit European Composite Unit (EURCO)',
            'code'    => 'XBA',
            'numeric' => 955,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBB' => [
            'display' => 'Bond Markets Unit European Monetary Unit (E.M.U.-6)',
            'code'    => 'XBB',
            'numeric' => 956,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBC' => [
            'display' => 'Bond Markets Unit European Unit of Account 9 (E.U.A.-9)',
            'code'    => 'XBC',
            'numeric' => 957,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBD' => [
            'display' => 'Bond Markets Unit European Unit of Account 17 (E.U.A.-17)',
            'code'    => 'XBD',
            'numeric' => 958,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XCD' => [
            'display' => 'East Caribbean Dollar',
            'code'    => 'XCD',
            'numeric' => 951,
            'digits'  => 2,
            'minor'   => 100
        ],
        'XDR' => [
            'display' => 'SDR (Special Drawing Right)',
            'code'    => 'XDR',
            'numeric' => 960,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XOF' => [
            'display' => 'CFA Franc BCEAO',
            'code'    => 'XOF',
            'numeric' => 952,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XPD' => [
            'display' => 'Palladium',
            'code'    => 'XPD',
            'numeric' => 964,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XPF' => [
            'display' => 'CFP Franc',
            'code'    => 'XPF',
            'numeric' => 953,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XPT' => [
            'display' => 'Platinum',
            'code'    => 'XPT',
            'numeric' => 962,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XSU' => [
            'display' => 'Sucre',
            'code'    => 'XSU',
            'numeric' => 994,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XTS' => [
            'display' => 'Codes specifically reserved for testing purposes',
            'code'    => 'XTS',
            'numeric' => 963,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XUA' => [
            'display' => 'ADB Unit of Account',
            'code'    => 'XUA',
            'numeric' => 965,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XXX' => [
            'display' => 'The codes assigned for transactions where no currency is involved',
            'code'    => 'XXX',
            'numeric' => 999,
            'digits'  => -1,
            'minor'   => 1
        ],
        'YER' => [
            'display' => 'Yemeni Rial',
            'code'    => 'YER',
            'numeric' => 886,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZAR' => [
            'display' => 'Rand',
            'code'    => 'ZAR',
            'numeric' => 710,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZMW' => [
            'display' => 'Zambian Kwacha',
            'code'    => 'ZMW',
            'numeric' => 967,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZWL' => [
            'display' => 'Zimbabwe Dollar',
            'code'    => 'ZWL',
            'numeric' => 932,
            'digits'  => 2,
            'minor'   => 100
        ]
    ];

    /**
     * Retrieves the display name
     *
     * @return string
     */
    public function displayName()
    {
        return self::$currencies[$this->value]['display'];
    }

    /**
     * Retrieves the ISO 4217 currency code
     *
     * @return string
     */
    public function code()
    {
        return self::$currencies[$this->value]['code'];
    }

    /**
     * Retrieves the ISO 4217 numeric code
     *
     * @return int
     */
    public function numericCode()
    {
        return self::$currencies[$this->value]['numeric'];
    }

    /**
     * Retrieves the default number of fraction digits
     *
     * In the case of pseudo-currencies, -1 is returned.
     *
     * @return int
     */
    public function digits()
    {
        return self::$currencies[$this->value]['digits'];
    }

    /**
     * Retrieves the number of minor units
     *
     * @return int
     */
    public function minor()
    {
        return self::$currencies[$this->value]['minor'];
    }
}
