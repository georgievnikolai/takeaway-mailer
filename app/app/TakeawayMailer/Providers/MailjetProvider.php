<?php

namespace App\TakeawayMailer\Providers;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\Provider;

class MailjetProvider extends Provider
{
    public function send(Message $message)
    {
        $client = new \Mailjet\Client(config('mail_providers.MJ_APIKEY_PUBLIC'), config('mail_providers.MJ_APIKEY_PRIVATE'), false, ['version' => 'v3.1']);
        
        $to = [];

        foreach($message->to as $k => $v)
        {
            $name = explode("@", $v);
            $name = $name[0];

            $to[] = [
                'Email' => $v,
                'Name' => $name,
            ];
        }

        $request_body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "TODO",
                        'Name' => "Me"
                    ],
                    'To' => $to,
                    'Subject' => $message->subject,
                    'TextPart' => $message->body_text,
                    'HTMLPart' => $message->body,
                ]
            ]
        ];

        $response = $client->post(\Mailjet\Resources::$Email, ['body' => $request_body]);

        if($response->success())
        {
            return true;
        }

        throw new \Exception("Sending failed");
    }
}