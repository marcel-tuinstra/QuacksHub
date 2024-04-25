<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CorsListener
{
    public function __construct(private readonly ParameterBagInterface $params)
    {
        // noop
    }

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        $allowedOrigins = $this->params->get('cors.allowed_origins');
        $request = $event->getRequest();
        $response = $event->getResponse();

        $origin = $request->headers->get('Origin');
        if (in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->getRequest()->getMethod() === 'OPTIONS') {
            $event->setResponse(new Response()); // No content response for OPTIONS requests
        }
    }
}
