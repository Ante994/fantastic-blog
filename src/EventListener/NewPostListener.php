<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 24.12.18.
 * Time: 07:14
 */

namespace App\EventListener;

use App\Entity\Post;
use App\Entity\User;
use Cocur\Slugify\SlugifyInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Listener for post creation, setting current user as author and hidden status
 *
 * Class NewPostListener
 * @package App\EventListener
 */
class NewPostListener
{
    private $slugger;
    private $security;

    /**
     * NewPostListener constructor.
     * @param Security $security
     * @param SlugifyInterface $slugger
     */
    public function __construct(Security $security, SlugifyInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Post) {
            return;
        }

        // If we use data fixtures then user is set random
        if (!$entity->getAuthor()) {
            /** @var User $user */
            $user = $this->security->getUser();
            $entity->setAuthor($user);
        }

        // Generating slug for translations from post title
        $postTranslation = $entity->getPostTranslation();
        $postTranslation->setSlugEn($this->slugger->slugify($postTranslation->getTitleEn()));
        $postTranslation->setSlugHr($this->slugger->slugify($postTranslation->getTitleHr()));

        $entity->setStatus(['enabled']);
    }
}