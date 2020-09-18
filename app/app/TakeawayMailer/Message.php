<?php

namespace App\TakeawayMailer;

use App\TakeawayMailer\Exceptions\MessageValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Message
{
    public $id;
    public $reference_id;
    public $subject;
    public $body;
    public $body_text;
    public $to;

    public function __construct(string $subject, string $body, array $to, string $reference_id = null)
    {
        $this->id = Str::uuid()->toString();
        $this->subject = $subject;
        $this->body = $body;
        $this->body_text = strip_tags($body);
        $this->to = $to;
        $this->reference_id = $reference_id;

        $this->_validate();

        Log::info("Message {$this->id} (reference_id: {$this->reference_id}) created");
    }

    private function _validate()
    {
        $data = (array) $this;

        $validator = Validator::make($data, [
            'subject' => "required|nullable|min:1",
            'body' => "required|nullable|min:1",
            'to.*' => "required|email",
        ]);

        if($validator->fails())
        {
            throw new MessageValidationException($validator->errors());
        }
    }

    public static function initFromCommandArguments(array $arguments)
    {
        $arguments['reference_id'] = $arguments['reference_id'] ?? null;
        return new self($arguments['subject'], $arguments['body'], [$arguments['to']], $arguments['reference_id']);
    }

    public static function initFromRequest(Request $request)
    {
        return new self($request->input('subject'), $request->input('body'), $request->input('to'), $request->input('reference_id'));
    }
}