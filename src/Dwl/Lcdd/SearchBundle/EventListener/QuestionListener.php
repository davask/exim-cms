<?php

namespace Dwl\Lcdd\SearchBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Dwl\Lcdd\SearchBundle\Entity\Question;

class QuestionListener implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return array(
          'prePersist',
          'preUpdate',
          'postPersist',
          'postUpdate',
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof \Dwl\Lcdd\SearchBundle\Entity\Question) {

            if(count($entity->getUnqualifiedQuestions()) > 0) {

                foreach ($entity->getUnqualifiedQuestions() as $key => $unqualifiedQuestion) {
                    $unqualifiedQuestion->setQualifiedQuestion($entity);
                }

                $entity->setQualified(true);
                $entity->removeQualifiedQuestion();

            }

        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof \Dwl\Lcdd\SearchBundle\Entity\Question) {

            if(count($entity->getUnqualifiedQuestions()) > 0) {

                foreach ($entity->getUnqualifiedQuestions() as $key => $unqualifiedQuestion) {
                    $unqualifiedQuestion->setQualifiedQuestion($entity);
                }

                $entity->setQualified(true);
                $entity->removeQualifiedQuestion();

            }

        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->postUpdate($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof \Dwl\Lcdd\SearchBundle\Entity\Question) {

        }
    }

}

