<?php

class Tools extends ToolsCore
{
    /**
    * Return price with currency sign for a given product
    *
    * @param float $price Product price
    * @param object|array $currency Current currency (object, id_currency, NULL => context currency)
    * @return string Price correctly formated (sign, decimal separator...)
    * if you modify this function, don't forget to modify the Javascript function formatCurrency (in tools.js)
    */
    public static function displayPrice($price, $currency = null, $no_utf8 = false, Context $context = null)
    {
        if (!is_numeric($price)) {
            return $price;
        }
        if (!$context) {
            $context = Context::getContext();
        }
        if ($currency === null) {
            $currency = $context->currency;
        } elseif (is_int($currency)) {
            $currency = Currency::getCurrencyInstance((int)$currency);
        }
        $cldr = parent::getCldr($context);
        $active = Configuration::get('ETERTHEME_CURRENCY_ACTIVE');
        if ($active) {
            $iso_code = is_array($currency) ? $currency['iso_code'] : $currency->iso_code;
            $format = Configuration::get('ETERTHEME_CURRENCY_FORMAT');
            $format = str_replace("{currency}", $iso_code, $format);
            $decimal = Configuration::get('ETERTHEME_CURRENCY_DECIMAL');
            $group = Configuration::get('ETERTHEME_CURRENCY_GROUP');

            $symbols['currencySymbol'] = $currency->sign;
            $symbols['decimal'] = $decimal;
            $symbols['group'] = $group;

            $formater = new ICanBoogie\CLDR\CurrencyFormatter($cldr->getRepository());
            $price = $formater->format($price,$format,$symbols);
        } else {
            $price = $cldr->getPrice($price, is_array($currency) ? $currency['iso_code'] : $currency->iso_code);
        }
        

        return $price;
    }
}
