<?php

namespace Dwl\GeoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwl\GeoBundle\Entity\Country;

class LoadCountryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Country = new Country();
        $Country->setIso('FRA');
        $Country->setiso2('FR');
        $Country->setName('France');
        $Country->setContinent($this->getReference('EU'));
        $Country->setLanguage($this->getReference('fr_FR'));

        $manager->persist($Country);
        $manager->flush();

        $this->addReference('FRA', $Country);
    }

    public function getOrder()
    {
        return 3;
    }
}
