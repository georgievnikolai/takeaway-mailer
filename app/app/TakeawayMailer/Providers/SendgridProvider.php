<?php

namespace App\TakeawayMailer\Providers;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\Provider;

class SendgridProvider extends Provider
{
    public function send(Message $message)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("ehwas503@gmail.com", "Nikolai");
        $email->setSubject($message->subject);

        foreach($message->to as $k => $v)
        {
            $name = explode("@", $v);
            $name = $name[0];      
      
            $email->addTo($v, $name);
        }

        $email->addContent("text/plain", $message->body_text);
        $email->addContent("text/html", $message->body);
        $sendgrid = new \SendGrid(config('mail_providers.SENDGRID_API_KEY'));

        $response = $sendgrid->send($email);
        $response_data = json_decode($response->body(), 1);

        if(in_array($response->statusCode(), [200, 201, 202]))
        {
            return true;
        }
        
        if(isset($response_data['errors']))
        {
            throw new \Exception($response_data['errors'][0]['message']);
        }

        throw new \Exception("Sending failed");
    }
}