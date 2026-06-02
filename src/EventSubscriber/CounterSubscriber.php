<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CounterSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->shouldCount($event->getRequest())) {
            return;
        }

        $sessionCounter = (int) $event->getRequest()->getSession()->get('session_counter', 0) + 1;
        $event->getRequest()->getSession()->set('session_counter', $sessionCounter);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->shouldCount($event->getRequest())) {
            return;
        }

        $cookieCounter = (int) $event->getRequest()->cookies->get('cookie_counter', 0) + 1;

        $event->getResponse()->headers->setCookie(
            Cookie::create('cookie_counter', (string) $cookieCounter, time() + (365 * 24 * 60 * 60))
        );
    }

    private function shouldCount(Request $request): bool
    {
        $route = $request->attributes->get('_route');
        if (\is_string($route) && (str_starts_with($route, '_wdt') || str_starts_with($route, '_profiler'))) {
            return false;
        }

        return true;
    }
}
