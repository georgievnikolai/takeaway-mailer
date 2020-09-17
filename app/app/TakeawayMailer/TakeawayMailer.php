<?php

namespace App\TakeawayMailer;
use App\TakeawayMailer\Message;
use App\TakeawayMailer\Providers\SendgridProvider;
use App\TakeawayMailer\Providers\MailjetProvider;

class TakeawayMailer
{
    private $provider;
    private $message;

    private static $available_providers = [
        SendgridProvider::class,
        MailjetProvider::class,
    ];    

    public function __construct(Message $message, int $retry)
    {
        $this->message = $message;
        $this->provider = $this->pickProvider($retry);
    }

    private function pickProvider(int $retry)
    {
        $key = $retry % count(self::$available_providers);
        $provider = self::$available_providers[$key];
        return new $provider;
    }

    public static function trySend(Message $message, int $retry)
    {
        return new self($message, $retry);
    }

    public function send()
    {
        $this->provider->send($this->message);
    }

    public static function queue(Message $message)
    {
        // add message to queue for sending
    }
}
