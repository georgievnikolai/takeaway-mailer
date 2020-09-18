<?php

namespace App\TakeawayMailer;

use App\TakeawayMailer\Providers\MailjetProvider;
use App\TakeawayMailer\Providers\SendgridProvider;

class ProviderFactory
{

    public static $available_providers = [
        SendgridProvider::class,
        MailjetProvider::class,
    ];  

    public static function make(int $retry)
    {
        $key = $retry % count(self::$available_providers);
        $provider = self::$available_providers[$key];
        return new $provider;
    }
}