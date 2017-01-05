<?php

namespace Dwl\Lcdd\SearchBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dwl\Lcdd\SearchBundle\Entity\Question;

class QuestionListener
{

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Question) {
            // do something with the Product
            $this->handleEvent($entity, $entityManager);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Question) {
            // do something with the Product
            $this->handleEvent($entity, $entityManager);
        }
    }

    public function handleEvent($question, $em) {

        // $author = $question->getAuthor();

        // if(empty($author)) {
        //     $repo = $em->getRepository('ApplicationSonataUserBundle:User');
        //     $author = $repo->findLcdd();
        //     if(empty($author)) {
        //         $author = $repo->findOneById(1);
        //     }
        // }

    }

}

