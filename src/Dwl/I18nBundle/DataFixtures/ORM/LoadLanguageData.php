<?php

namespace Dwl\I18nBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwl\I18nBundle\Entity\Language;

class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Language = new Language();
        $Language->setIso('fr_FR');
        $Language->setName('Français');

        $manager->persist($Language);
        $manager->flush();

        $this->addReference('fr_FR', $Language);
    }

    public function getOrder()
    {
        return 1;
    }
}
