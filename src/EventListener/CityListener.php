<?php

namespace App\EventListener;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CityListener implements EventSubscriberInterface
{
    public const string REDIRECT = 'sftest2.my';

    public static function getSubscribedEvents(): array
    {
        return ['kernel.request' => 'onKernelRequest'];
    }

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $host = $event->getRequest()->getHost();
        $repo = $this->em->getRepository(City::class);
        // $uri = $event->getRequest()->getRequestUri();
        $domains = $repo->getAllDomains();

        if (self::REDIRECT === $host) {
            return;
        }

        // if (str_contains($uri, self::REDIRECT)) {
        //     return;
        // }

        foreach ($domains as $domain) {
            $domain = trim($domain);
            if ('' === $domain) {
                continue;
            }
            if (str_contains($host, $domain)) {
                return;
            }
        }

        $event->setResponse(new RedirectResponse('https://'.self::REDIRECT, 301));
    }
}
