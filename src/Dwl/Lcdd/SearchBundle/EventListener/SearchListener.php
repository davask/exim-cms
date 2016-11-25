<?php

namespace Dwl\Lcdd\SearchBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

class SearchListener implements EventSubscriberInterface
{
    // private $anotherService;

    // ...

    public function addSearch(TransformEvent $event)
    {
        // $document = $event->getDocument();
        // $custom = $this->anotherService->calculateCustom($event->getObject());

        // $document->set('custom', $custom);
    }

    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addSearch',
        );
    }
}

