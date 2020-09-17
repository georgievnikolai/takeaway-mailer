<?php

namespace App\TakeawayMailer;

use App\Jobs\ProcessTakeawayMailerQueue;
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
        $mailer = new self($message, $retry);
        $mailer->send();
    }

    public function send()
    {
        $this->provider->send($this->message);
    }

    public static function queue(Message $message)
    {
        ProcessTakeawayMailerQueue::dispatch($message);
    }
}
