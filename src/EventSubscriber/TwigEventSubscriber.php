<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\ArticleRepository;
use Twig\Environment;
use App\Repository\StaticPageRepository;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $staticPageRepository;


    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('static_pages', $this->staticPageRepository->findAll());
    }

    public function __construct(Environment $twig, StaticPageRepository $staticPageRepository)
    {
        $this->twig = $twig;
        $this->staticPageRepository = $staticPageRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
