<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateMalformedStringException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * @throws DateMalformedStringException
     */
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

        // Création de posts
        $slugger = new AsciiSlugger();

        for ($i = 1; $i <= 5; $i++) {
            $title = sprintf('Post Lorem Ipsum #%d', $i);

            $post = new Post();
            $post->setTitle($title);
            $post->setContent(sprintf("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent maximus ut augue et aliquam. Praesent ut purus interdum, commodo mi et, venenatis elit. Cras mattis sem id efficitur euismod. In efficitur ipsum ut varius blandit. Aliquam ac orci risus. Integer accumsan urna nisl, a mollis sapien condimentum id. Morbi nec augue diam. Morbi ut tellus diam. Nunc ornare luctus fermentum. Etiam malesuada dui at volutpat iaculis. Morbi dui ante, laoreet in aliquet ut, dignissim sit amet leo. Donec at magna suscipit, tempor elit eu, commodo felis. Suspendisse finibus auctor varius. Donec sodales fringilla erat, eget varius nisi imperdiet nec. Integer eu sapien nunc. Praesent id arcu in massa placerat placerat. Pellentesque vitae ante et elit rutrum dictum nec ut lacus. Phasellus id tincidunt tortor. Sed egestas dui orci, interdum semper neque tempus quis. Nulla interdum, risus quis blandit hendrerit, quam massa tincidunt tortor, id viverra lacus lacus id neque. Praesent vel ipsum odio. Quisque bibendum, eros quis vestibulum pharetra, nunc erat viverra sem, at posuere elit mi sit amet dolor.", $i));
            $post->setPublishedAt(new \DateTimeImmutable(sprintf('-%d days', 6 - $i)));

            $author = ($i % 2 === 1) ? $admin : $user;
            $post->setAuthor($author);

            $baseSlug = strtolower($slugger->slug($title)->toString());
            $slug = $baseSlug;
            $suffix = 2;

            while ($manager->getRepository(Post::class)->findOneBy(['slug' => $slug]) !== null) {
                $slug = $baseSlug . '-' . $suffix;
                $suffix++;
            }

            $post->setSlug($slug);

            $manager->persist($post);
        }

        // Implémenter ici les autres ajouts en BDD (Post, Comments, etc.)

        $manager->flush();
    }
}
