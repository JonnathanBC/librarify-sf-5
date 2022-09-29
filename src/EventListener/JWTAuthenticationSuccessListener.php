<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JWTAuthenticationSuccessListener
{

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = [
            "code" => 200,
            "message" => "Authentication successfully",
            "data" => $event->getData()
        ];
        $event->setData($data);
    }
}
