<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 18.12.18.
 * Time: 09:24
 */

namespace App\EventListener;


use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationListener
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $entity->setEnabled(1);
        $entity->setRoles(['ROLE_USER']);
        $entity->setRegistrationDate(new \DateTime());
        $entity->setLastLogin(new \DateTime());
        $entity->setDisplayName($entity->getFirstname().' '.$entity->getLastname());
        $password = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($password);
    }
}
