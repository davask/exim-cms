<?php

namespace Dwl\LexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwl\LexBundle\Entity\AuthoritySupreme;

class LoadAuthoritySupremeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $AuthoritySupreme = new AuthoritySupreme();
        $AuthoritySupreme->setName('Gouvernement de la République Française');
        $AuthoritySupreme->setShortname('GOV_RF');
        $AuthoritySupreme->setCountry($this->getReference('FRA'));
        $manager->persist($AuthoritySupreme);
        $manager->flush();

        $this->addReference('GOV_RF', $AuthoritySupreme);
    }

    public function getOrder()
    {
        return 4;
    }
}
