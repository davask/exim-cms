<?php

namespace Dwl\I18nBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * AuthorityTranslation
 *
 * @ORM\Table(name="authority_translation", indexes={
 *      @ORM\Index(name="authority_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class AuthorityTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}
