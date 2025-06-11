<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsAppHelper
{
    public static function enviarMensaje($mensaje)
    {
        $numero = '51981268897';
        $apiKey = '2426432';

        $url = "https://api.callmebot.com/whatsapp.php";

        HTTP::get($url, [
            'phone' => $numero,
            'text' => $mensaje,
            'apikey' => $apiKey,
        ]);
    }
}
