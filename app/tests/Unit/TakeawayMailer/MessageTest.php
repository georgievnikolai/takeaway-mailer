<?php

namespace Tests\Unit\TakeawayMailer;

use App\TakeawayMailer\Exceptions\MessageValidationException;
use App\TakeawayMailer\Message;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * Ensure some validation of the input is performed when creating a Message object
     */
    public function testMessageSubjectValidation()
    {
        $this->expectException(MessageValidationException::class);
        $message = new Message('', 'test', ['xxxxxx@xxxxx.com']);
    }

    public function testMessageBodyValidation()
    {
        $this->expectException(MessageValidationException::class);
        $message = new Message('test subject', '', ['xxxxxx@xxxxx.com']);
    }

    public function testMessageReceiverValidation()
    {
        $this->expectException(MessageValidationException::class);
        $message = new Message('test subject', 'test body', ['invalid_receive_address']);
    }
}
