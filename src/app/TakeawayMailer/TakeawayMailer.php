<?php

namespace App\TakeawayMailer;

use App\Jobs\ProcessTakeawayMailerQueue;
use App\TakeawayMailer\Message;
use Illuminate\Support\Facades\Log;

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
        Log::info("{$retry} attempt to send message {$message->id} (reference_id: {$message->reference_id})");
        $mailer = new self($message, $retry);
        $mailer->send();
    }

    public function send()
    {
        $this->provider->send($this->message);
        Log::info("Message {$this->message->id} sent through {$this->provider} (reference_id: {$this->message->reference_id})");
    }

    public static function queue(Message $message)
    {
        ProcessTakeawayMailerQueue::dispatch($message);
        Log::info("Message {$message->id} (reference_id: {$message->reference_id}) queued for sending");
    }
}
