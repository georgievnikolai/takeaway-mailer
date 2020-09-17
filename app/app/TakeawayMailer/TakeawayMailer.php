<?php

namespace App\TakeawayMailer;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\Providers\SendgridProvider;
use App\TakeawayMailer\Providers\MailjetProvider;

class TakeawayMailer
{
    private $provider;
    private $message;

    public function __construct(Message $message, int $retry)
    {
        $this->message = $message;
        $this->provider = ProviderFactory::make($retry);
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
