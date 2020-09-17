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
        $response = json_decode($response->body(), 1);

        if(isset($response['errors']))
        {
            throw new \Exception($response['errors'][0]['message']);
        }
    }
}