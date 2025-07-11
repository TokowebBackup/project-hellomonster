<?php

/**
 * @author Puji Ermanto<pujiermanto@gmail.com> | AKA Puji Was Here
 * @return _config
 * @method Midtrans::Hook
 */

namespace App\Libraries;

class MidtransSnap
{
    public function __construct()
    {
        require_once APPPATH . '../vendor/autoload.php';

        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // ubah ke true di production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public static function getClientKey()
    {
        return getenv('MIDTRANS_CLIENT_KEY');
    }
}
