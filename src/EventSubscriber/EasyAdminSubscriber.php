<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['hashPassword'],
                ['setPostDefaults'],
            ],
            BeforeEntityUpdatedEvent::class => ['hashPassword'],
            BeforeEntityDeletedEvent::class => ['preventDelete'],
        ];
    }

    public function setPostDefaults(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Post) {
            if ($entity->getAuthor() === null) {
                $entity->setAuthor($this->security->getUser());
            }
            if ($entity->getPublishedAt() === null) {
                $entity->setPublishedAt(new \DateTimeImmutable());
            }
        }
    }


    public function hashPassword($event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        if (!$entity->getPlainPassword()) {
            return;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entity,
            $entity->getPlainPassword()
        );

        $entity->setPassword($hashedPassword);
    }

    public function preventDelete(BeforeEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        // Empêcher la suppression du dernier admin en base de données
        if (in_array('ROLE_ADMIN', $entity->getRoles())) {
            $adminCount = $this->entityManager->getRepository(User::class)->countAdmins();

            if ($adminCount <= 1) {
                throw new AccessDeniedException('This user cannot be deleted: at least one administrator must remain.');
            }
        }

        // Empêche l'auto-suppression du compte courant
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        if ($currentUser && $currentUser->getId() === $entity->getId()) {
            throw new AccessDeniedException('You cannot delete your own account while logged in.');
        }
    }
}
