<?php

namespace App\TakeawayMailer\Providers;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\Provider;

class SendgridProvider extends Provider
{
    public function send(Message $message)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("TODO", "TODO");
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
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (\Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
}