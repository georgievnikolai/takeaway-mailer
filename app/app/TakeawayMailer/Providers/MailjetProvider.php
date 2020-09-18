<?php

namespace App\TakeawayMailer\Providers;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\Provider;
use Illuminate\Support\Facades\Log;

class MailjetProvider extends Provider
{
    public function send(Message $message)
    {
        $client = new \Mailjet\Client(config('mail_providers.MJ_APIKEY_PUBLIC'), config('mail_providers.MJ_APIKEY_PRIVATE'), true, ['version' => 'v3.1']);
        
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
                        'Email' => "ehwas503@gmail.com",
                        'Name' => "Nikolai"
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

        $response_data = $response->getData();

        if(isset($response_data['Messages'][0]['Errors']))
        {
            Log::error("MailJet error {$response_data['Messages'][0]['Errors'][0]['ErrorCode']} {$response_data['Messages'][0]['Errors'][0]['StatusCode']} {$response_data['Messages'][0]['Errors'][0]['ErrorMessage']}");
            throw new \Exception($response_data['Messages'][0]['Errors'][0]['ErrorMessage']);            
        }

        throw new \Exception("Sending failed");
    }
}