<?php

namespace Dwl\LexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dwl\LexBundle\Entity\Authority;

class LoadAuthorityData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $authorities = array(
            array(
                "name" => "CONSEIL SUPERIEUR DE L'AUDIOVISUEL",
                "shortname" => "CSA"
            ),
            array(
                "name" => "COMMISSION POUR LA TRANSPARENCE FINANCIERE DE LA VIE POLITIQUE",
                "shortname" => "CTFVP"
            ),
            array(
                "name" => "BANQUE DE FRANCE - CONSEIL DE LA POLITIQUE MONETAIRE",
                "shortname" => "BF"
            ),
            array(
                "name" => "COMMISSION GENERALE DE TERMINOLOGIE ET DE NEOLOGIE",
                "shortname" => "CGTN"
            ),
            array(
                "name" => "AUTORITE DE REGULATION DES COMMUNICATIONS ELECTRONIQUES ET DES POSTES",
                "shortname" => "ARCEP"
            ),
            array(
                "name" => "AUTORITE DES MARCHES FINANCIERS",
                "shortname" => "AMF"
            ),
            array(
                "name" => "AGENCE FRANCAISE DE LUTTE CONTRE LE DOPAGE",
                "shortname" => "AFLD"
            ),
            array(
                "name" => "CONSEIL ECONOMIQUE ET SOCIAL",
                "shortname" => "CES"
            ),
            array(
                "name" => "COMITE DES ENTREPRISES D'ASSURANCE",
                "shortname" => "CEA"
            ),
            array(
                "name" => "AUTORITE DE REGULATION DES MESURES TECHNIQUES",
                "shortname" => "ARMT"
            ),
            array(
                "name" => "MEDIATEUR NATIONAL DE L'ENERGIE",
                "shortname" => "MNE"
            ),
            array(
                "name" => "COUR DES COMPTES",
                "shortname" => "CDC"
            ),
            array(
                "name" => "COMMISSION NATIONALE DE DEONTOLOGIE DE LA SECURITE",
                "shortname" => "CNDS"
            ),
            array(
                "name" => "HAUTE AUTORITE DE LUTTE CONTRE LES DISCRIMINATIONS ET POUR L'EGALITE",
                "shortname" => "HALDE"
            ),
            array(
                "name" => "CONSEIL DE LA CONCURRENCE",
                "shortname" => "AC"
            ),
            array(
                "name" => "COUR DE DISCIPLINE BUDGETAIRE ET FINANCIERE",
                "shortname" => "CDBF"
            ),
            array(
                "name" => "MINISTERE DE L'ECONOMIE, DES FINANCES ET DE L'INDUSTRIE REGLEMENTS DU COMITE DE LA REGLEMENTATION COMPTABLE",
                "shortname" => "MEF"
            ),
            array(
                "name" => "CONSEIL D'ETAT",
                "shortname" => "CE"
            ),
            array(
                "name" => "COUR DE JUSTICE DE LA REPUBLIQUE",
                "shortname" => "CJR"
            ),
            array(
                "name" => "CONSEIL DES VENTES VOLONTAIRES DE MEUBLES AUX ENCHERES PUBLIQUES",
                "shortname" => "CVVMEP"
            ),
            array(
                "name" => "COMMISSION NATIONALE DE L'INFORMATIQUE ET DES LIBERTES",
                "shortname" => "CNIL"
            ),
            array(
                "name" => "MEDIATEUR DE LA REPUBLIQUE",
                "shortname" => "DD"
            ),
            array(
                "name" => "COMMISSION NATIONALE DES COMPTES DE CAMPAGNE ET DES FINANCEMENTS POLITIQUES",
                "shortname" => "CNCCFP"
            ),
            array(
                "name" => "COMMISSION CONSULTATIVE DU SECRET DE LA DEFENSE NATIONALE",
                "shortname" => "CCSDN"
            ),
            array(
                "name" => "CONSEIL CONSTITUTIONNEL",
                "shortname" => "CC"
            ),
            array(
                "name" => "COMMISSION DE REGULATION DE L'ENERGIE",
                "shortname" => "CRE"
            ),
            array(
                "name" => "COMMISSION NATIONALE DU DEBAT PUBLIC",
                "shortname" => "CNDP"
            ),
            array(
                "name" => "HAUTE AUTORITE DE SANTE",
                "shortname" => "HAS"
            ),
            array(
                "name" => "COMMISSION D'ACCES AUX DOCUMENTS ADMINISTRATIFS",
                "shortname" => "CADA"
            ),
            array(
                "name" => "COMMISSION NATIONALE DE CONTROLE DES INTERCEPTIONS DE SECURITE",
                "shortname" => "CNCIS"
            ),
            array(
                "name" => "COMMISSION DES OPERATIONS DE BOURSE",
                "shortname" => "COB"
            ),
            array(
                "name" => "AUTORITE DE CONTROLE DES ASSURANCES ET DES MUTUELLES",
                "shortname" => "ACAM"
            ),
            array(
                "name" => "COMMISSION BANCAIRE",
                "shortname" => "CB"
            )
        );

        for ($i=0; $i < count($authorities); $i++) {

            $Authority = new Authority();

            $Authority->setName($authorities[$i]['name']);
            $Authority->setShortname($authorities[$i]['shortname']);
            $Authority->setAuthoritySupreme($this->getReference('GOV_RF'));

            $manager->persist($Authority);
            $manager->flush();

            $this->addReference($authorities[$i]['shortname'], $Authority);
        }

    }

    public function getOrder()
    {
        return 5;
    }
}
