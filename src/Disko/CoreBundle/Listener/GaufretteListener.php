<?php
namespace Disko\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;

/**
 * Class GaufretteListener
 *
 * @package Disko\CoreBundle\Listener
 */
class GaufretteListener
{
    /**
     * Const Trait Need to activate listener
     */
    const TRAIT_GAUFRETTE = 'Disko\\CoreBundle\\Traits\\GaufretteTrait';

    // Attribute for file system gaufrette
    public $fileSystem = null;

    /**
     * Set file system to redisturb
     *
     * @param $fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem->get('current');
    }

    /**
     * On post load
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre update
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre persist
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre remove
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * Try enabled
     *
     * @param LifecycleEventArgs $args
     */
    public function tryEnabled(LifecycleEventArgs $args)
    {
        if (in_array(self::TRAIT_GAUFRETTE, class_uses($args->getEntity()))) {

            $args->getEntity()->setFileSystem($this->fileSystem);
            if ($args->getEntity()->isFirstPreUpload())
            {
                $args->getEntity()->preUploads();
            }
        }
    }


}