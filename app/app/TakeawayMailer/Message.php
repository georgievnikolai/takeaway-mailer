<?php

namespace App\TakeawayMailer;

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
        Log::info("Message {$this->id} (reference_id: {$this->reference_id}) created");
    }

    public static function initFromCommandArguments(array $arguments)
    {
        $validator = Validator::make($arguments, [
            'subject' => "required",
            'body' => "required",
            'to' => "required|email",
        ]);

        if($validator->fails())
        {
            throw new \Exception($validator->errors());
        }

        $arguments['reference_id'] = $arguments['reference_id'] ?? null;

        return new self($arguments['subject'], $arguments['body'], [$arguments['to']], $arguments['reference_id']);
    }

    public static function initFromRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => "required",
            'body' => "required",
            'to.*' => "required|email",
        ]);

        if($validator->fails())
        {
            throw new \Exception($validator->errors());
        }

        return new self($request->input('subject'), $request->input('body'), $request->input('to'), $request->input('reference_id'));
    }
}