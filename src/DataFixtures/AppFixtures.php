<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur avec ROLE_ADMIN
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'adminpass');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        // Création d'un utilisateur classique
        $user = new User();
        $user->setEmail('user@example.com');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'userpass');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $manager->flush();

        // Implémenter ici les autres ajouts en BDD (Post, Comments, etc.)
    }
}
