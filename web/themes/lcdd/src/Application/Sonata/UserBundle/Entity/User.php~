<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", mappedBy="speaker")
     */
    private $questions;

    /**
     * @var \Application\Sonata\ClassificationBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $position;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $avatar;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group")
     */
    protected $groups;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     * @return User
     */
    public function addQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Dwl\Lcdd\SearchBundle\Entity\Question $questions
     */
    public function removeQuestion(\Dwl\Lcdd\SearchBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set avatar
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $avatar
     * @return User
     */
    public function setAvatar(\Application\Sonata\MediaBundle\Entity\Media $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set position
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $position
     * @return User
     */
    public function setPosition(\Application\Sonata\ClassificationBundle\Entity\Category $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Application\Sonata\ClassificationBundle\Entity\Category
     */
    public function getPosition()
    {
        return $this->position;
    }
}
