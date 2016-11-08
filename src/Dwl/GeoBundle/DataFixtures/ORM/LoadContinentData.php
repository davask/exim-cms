<?php

namespace Dwl\GeoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwl\GeoBundle\Entity\Continent;

class LontinentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Continent = new Continent();
        $Continent->setIso('EU');
        $Continent->setName('Europe');
        $manager->persist($Continent);
        $manager->flush();

        $this->addReference('EU', $Continent);

        $Continent = new Continent();
        $Continent->setIso('NA');
        $Continent->setName('Amérique du nord');
        $manager->persist($Continent);
        $manager->flush();

        $this->addReference('NA', $Continent);
    }

    public function getOrder()
    {
        return 2;
    }
}
