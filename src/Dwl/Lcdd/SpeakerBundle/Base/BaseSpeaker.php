<?php

namespace Dwl\Lcdd\SpeakerBundle\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserInterface;
use Sonata\Component\Customer\CustomerInterface;
use Sonata\CustomerBundle\Entity\BaseCustomer as BaseCustomer;
use Dwl\Lcdd\SpeakerBundle\Component\SpeakerInterface;

/**
 * Dwl\Lcdd\SpeakerBundle\Base\BaseSpeaker.
 */
abstract class BaseSpeaker implements SpeakerInterface
{

    /**
     * Available properties
     *
     * FOS properties
     *
     * protected $id;
     * protected $username;
     * protected $usernameCanonical;
     * protected $email;
     * protected $emailCanonical;
     * protected $enabled;
     * protected $salt;
     * protected $password;
     * protected $plainPassword;
     * protected $lastLogin;
     * protected $confirmationToken;
     * protected $passwordRequestedAt;
     * protected $groups;
     * protected $locked;
     * protected $expired;
     * protected $expiresAt;
     * protected $roles;
     * protected $credentialsExpired;
     * protected $credentialsExpireAt;
     *
     * Sonata user properties
     *
     * protected $createdAt;
     * protected $updatedAt;
     * protected $twoStepVerificationCode;
     * protected $dateOfBirth;
     * protected $firstname;
     * protected $lastname;
     * protected $website;
     * protected $biography;
     * protected $gender; // set the default to unknown
     * protected $locale;
     * protected $timezone;
     * protected $phone;
     * protected $facebookUid;
     * protected $facebookName;
     * protected $facebookData;
     * protected $twitterUid;
     * protected $twitterName;
     * protected $twitterData;
     * protected $gplusUid;
     * protected $gplusName;
     * protected $gplusData;
     * protected $token;
     *
     * sonata customer properties
     *
     * protected $title;
     * protected $firstname;
     * protected $lastname;
     * protected $email;
     * protected $birthDate;
     * protected $birthPlace;
     * protected $phoneNumber;
     * protected $mobileNumber;
     * protected $faxNumber;
     * protected $updatedAt;
     * protected $createdAt;
     * protected $user;
     * protected $addresses;
     * protected $orders;
     * protected $locale;
     * protected $isFake;
     *
     */

    protected $displayName;

    /**
     * @var \Application\Sonata\CustomerBundle\Entity\Customer
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_speaker", type="boolean", nullable=true)
     */
    protected $isSpeaker;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Dwl\Lcdd\SearchBundle\Entity\Question", mappedBy="speaker")
     */
    protected $questions;

    /**
     * @var \Application\Sonata\ClassificationBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClassificationBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $position;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    protected $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="career", type="string", length=1000, nullable=true)
     */
    protected $career;

    /**
     * @var string
     *
     * @ORM\Column(name="specialties", type="string", length=1000, nullable=true)
     */
    protected $specialties;

    /**
     * @var string
     *
     * @ORM\Column(name="publications", type="string", length=1000, nullable=true)
     */
    protected $publications;

    /* see http://www.coordonnees-gps.fr/ */
    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=180, nullable=true)
     */
    protected $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=360, nullable=true)
     */
    protected $longitude;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="presentation_id", referencedColumnName="id")
     */
    protected $presentation;

    protected $protectedEmail;

    protected $protectedPhone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getFullname()
    {

        if(!empty($this->customer)) {

            $customer = $this->customer;
            $fullname = trim($customer->getLastname().' '.$customer->getFirstname());

        }

        if(empty($fullname)) {

            $user = $customer->getUser();

            if(!empty($user)) {
                $fullname = trim($user->getLastname().' '.$user->getFirstname());

                if(empty($fullname)) {
                    $fullname = trim($user->getUsername());
                }

            }

        }

        if(empty($fullname)) {
            $fullname = trim("-");
        }

        return ucfirst($fullname);

    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSpeaker($isSpeaker)
    {
        $this->isSpeaker = $isSpeaker;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsSpeaker()
    {
        return $this->isSpeaker;
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

    /**
     * Set career
     *
     * @param string $career
     * @return User
     */
    public function setCareer($career)
    {
        $this->career = $career;

        return $this;
    }

    /**
     * Get career
     *
     * @return string
     */
    public function getCareer()
    {
        return $this->career;
    }

    /**
     * Set specialties
     *
     * @param string $specialties
     * @return User
     */
    public function setSpecialties($specialties)
    {
        $this->specialties = $specialties;

        return $this;
    }

    /**
     * Get specialties
     *
     * @return string
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * Set publications
     *
     * @param string $publications
     * @return User
     */
    public function setPublications($publications)
    {
        $this->publications = $publications;

        return $this;
    }

    /**
     * Get publications
     *
     * @return string
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set presentation
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $presentation
     * @return Question
     */
    public function setPresentation(\Application\Sonata\MediaBundle\Entity\Media $presentation = null)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * set textToPng
     */
    public function textToPng($text = null, $R = 49, $G = 75, $B = 72)
    {

        // Replace path by your own font path
        $font = dirname(__DIR__).'/Helper/OpenSans-Regular.ttf';

        if(empty($text)) {
            $text = '-';
        }

        $width = strlen($text) * 15;
        $height = 28;
        $stringImg = imagecreatetruecolor($width, $height);

        imagealphablending($stringImg, false);
        imagesavealpha($stringImg,true);

        // Create colors and draw transparent background
        $trans = imagecolorallocatealpha($stringImg, 255, 255, 255, 127);
        // $black = imagecolorallocate($stringImg, 0, 0, 0);
        $color = imagecolorallocate($stringImg, $R, $G, $B);
        imagefilledrectangle($stringImg, 0, 0, $width, $height, $trans);

        imagealphablending($stringImg, true);

        // Add the text
        imagettftext($stringImg, 18, 0, 0, 20, $color, $font, $text);

        ob_start();
        imagepng($stringImg);
        $stringdata = ob_get_contents();
        ob_end_clean();
        imagedestroy($stringImg);

        return 'data:image/png;base64,'.base64_encode($stringdata);

    }

    /**
     * Get protectedPhone
     */
    public function setProtectedPhone()
    {
        $this->protectedPhone = $this->textToPng($this->getCustomer()->getUser()->getPhone());
    }

    /**
     * Get protectedEmail
     */
    public function getProtectedPhone()
    {
        if(empty($this->protectedPhone)) {
            $this->setProtectedPhone();
        }
        return $this->protectedPhone;
    }

    /**
     * Get protectedEmail
     */
    public function setProtectedEmail()
    {
        $this->protectedEmail = $this->textToPng($this->getCustomer()->getUser()->getEmail());
    }

    /**
     * Get protectedEmail
     */
    public function getProtectedEmail()
    {
        if(empty($this->protectedEmail)) {
            $this->setProtectedEmail();
        }
        return $this->protectedEmail;
    }

}
