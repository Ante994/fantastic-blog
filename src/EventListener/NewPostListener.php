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

    private $security;

    /**
     * NewPostListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
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

        /** @var User $user */
        $user = $this->security->getUser();
        $entity->setAuthor($user);
        $entity->setStatus(['hidden']);
    }
}
