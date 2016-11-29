<?php

namespace Dwl\Lcdd\SearchBundle\Component\Question;

class QuesionDefinition
{
    /**
     * @var QuesionManagerInterface
     */
    protected $manager;

    /**
     * @var QuesionProviderInterface
     */
    protected $provider;

    /**
     * @param QuesionProviderInterface $provider
     * @param QuesionManagerInterface  $manager
     */
    public function __construct(QuesionProviderInterface $provider, QuesionManagerInterface $manager)
    {
        $this->provider = $provider;
        $this->manager = $manager;
    }

    /**
     * @return QuesionManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return QuesionProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
