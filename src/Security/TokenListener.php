<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TokenListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($event->getRequest()->headers->get('x-token') != "azerty") {
            $event->setResponse(new Response(null, 401));
        }
    }

}

