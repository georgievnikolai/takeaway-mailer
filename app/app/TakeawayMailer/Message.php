<?php

namespace App\TakeawayMailer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Message
{
    public $subject;
    public $body;
    public $body_text;
    public $to;

    public function __construct($subject, $body, $to)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->body_text = strip_tags($body);
        $this->to = $to;
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

        return new self($request->input('subject'), $request->input('body'), $request->input('to'));
    }
}