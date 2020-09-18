<?php

namespace App\TakeawayMailer;
use App\TakeawayMailer\Message;

abstract class Provider
{
    abstract public function send(Message $message);

    public function __toString()
    {
        return get_called_class();
    }
}