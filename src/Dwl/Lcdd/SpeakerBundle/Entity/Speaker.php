<?php

namespace Dwl\Lcdd\SpeakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

use Dwl\Lcdd\SpeakerBundle\Base\BaseSpeaker as BaseSpeaker;

/**
 * Speaker
 *
 * @ORM\Entity(repositoryClass="Dwl\Lcdd\SpeakerBundle\Repository\SpeakerRepository")
 */
class Speaker extends BaseSpeaker
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
