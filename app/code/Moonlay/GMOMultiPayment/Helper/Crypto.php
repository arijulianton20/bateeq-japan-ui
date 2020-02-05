<?php

namespace Moonlay\GMOMultiPayment\Helper;

/**
 * Created by PhpStorm.
 * User: jimbur
 * Date: 27/10/2016
 * Time: 5:38 PM
 */
class Crypto
{
    /**
     * validates and associative array that contains a hmac signature against an api key
     * @param $query array
     * @param $api_key string
     * @return bool
     */
    public static function isValidSignature($response, $shop_id)
    {
        $actualSignature = md5($response['ShopID']);
        return $actualSignature == md5($shop_id);
    }

    // GMO create ShopPassString
    public static function getEncriptPassString($order)
    {
        return md5(implode('', $order)); 
    }
}
