<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer l'utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@evergreen.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Créer l'utilisateur standard
        $user = new User();
        $user->setEmail('user@evergreen.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        // Créer les posts
        $postsData = [
            [
                'title' => 'Evergreen fête ses 5 ans !',
                'slug' => 'evergreen-fete-ses-5-ans',
                'content' => "C'est avec une immense fierté que nous célébrons aujourd'hui le 5ème anniversaire d'Evergreen ! Depuis 2021, notre mission reste inchangée : accompagner les entreprises et les particuliers vers un avenir plus durable. En 5 ans, nous avons réalisé plus de 500 audits carbone, accompagné 200 entreprises dans leur transition écologique et contribué à réduire plus de 50 000 tonnes de CO2. Merci à nos clients, partenaires et collaborateurs pour leur confiance. L'aventure continue !",
                'publishedAt' => new \DateTimeImmutable('2026-02-01 10:00:00'),
            ],
            [
                'title' => "Appel à projets : Solutions numériques pour réduire l'empreinte carbone",
                'slug' => 'appel-projets-solutions-numeriques-carbone',
                'content' => "Evergreen lance son premier appel à projets destiné aux startups et innovateurs du numérique ! Nous recherchons des solutions digitales innovantes permettant de mesurer, réduire ou compenser l'empreinte carbone des entreprises. Les projets sélectionnés bénéficieront d'un accompagnement personnalisé, d'une dotation de 10 000€ et d'une visibilité auprès de notre réseau de partenaires. Date limite de candidature : 15 mars 2026. Entrepreneurs engagés, c'est le moment de faire la différence !",
                'publishedAt' => new \DateTimeImmutable('2026-02-03 14:30:00'),
            ],
            [
                'title' => 'Sobscore : Premier lauréat de notre appel à projets !',
                'slug' => 'sobscore-premier-laureat-appel-projets',
                'content' => "Nous avons le plaisir d'annoncer que Sobscore est le premier lauréat de notre appel à projets \"Solutions numériques pour réduire l'empreinte carbone\" ! Sobscore propose une application innovante qui calcule le score carbone de vos achats en temps réel grâce à l'intelligence artificielle. En scannant simplement un produit, les consommateurs peuvent connaître son impact environnemental et découvrir des alternatives plus durables. Félicitations à toute l'équipe Sobscore !",
                'publishedAt' => new \DateTimeImmutable('2026-02-05 09:00:00'),
            ],
            [
                'title' => "GreenTrack : Deuxième lauréat de l'appel à projets Evergreen",
                'slug' => 'greentrack-deuxieme-laureat-appel-projets',
                'content' => "Nous sommes ravis d'annoncer GreenTrack comme deuxième lauréat de notre appel à projets ! GreenTrack a développé une plateforme SaaS permettant aux entreprises de suivre en temps réel les émissions carbone de leur chaîne logistique. Grâce à des capteurs IoT et une interface intuitive, les responsables supply chain peuvent identifier les points critiques et optimiser leurs flux de transport. Une solution prometteuse pour décarboner la logistique !",
                'publishedAt' => new \DateTimeImmutable('2026-02-06 11:00:00'),
            ],
            [
                'title' => 'Bilan 2025 : Une année record pour la transition écologique',
                'slug' => 'bilan-2025-annee-record-transition-ecologique',
                'content' => "L'année 2025 a été exceptionnelle pour Evergreen et nos clients ! Nous avons accompagné 85 nouvelles entreprises, réalisé 150 audits carbone et formé plus de 1000 collaborateurs aux enjeux climatiques. Le point fort de l'année : le lancement de notre nouveau service de compensation carbone locale, en partenariat avec des projets de reforestation en France. Ensemble, nous avons planté plus de 10 000 arbres. Rendez-vous en 2026 pour continuer cette belle dynamique !",
                'publishedAt' => new \DateTimeImmutable('2026-01-15 16:00:00'),
            ],
        ];

        $posts = [];
        foreach ($postsData as $postData) {
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setSlug($postData['slug']);
            $post->setContent($postData['content']);
            $post->setPublishedAt($postData['publishedAt']);
            $post->setAuthor($admin);
            $manager->persist($post);
            $posts[] = $post;
        }

        // Créer les commentaires de l'utilisateur standard
        $commentsData = [
            [
                'content' => "Félicitations pour ces 5 années ! J'ai découvert Evergreen l'année dernière et votre accompagnement a transformé notre approche environnementale. Continuez ainsi !",
                'createdAt' => new \DateTimeImmutable('2026-02-01 14:30:00'),
                'isFlagged' => false,
                'postIndex' => 0, // Post "Evergreen fête ses 5 ans !"
            ],
            [
                'content' => "Super initiative ! Je vais proposer notre startup GreenMeter à cet appel à projets. Nous développons une solution de mesure d'empreinte carbone pour les PME.",
                'createdAt' => new \DateTimeImmutable('2026-02-04 09:15:00'),
                'isFlagged' => false,
                'postIndex' => 1, // Post "Appel à projets"
            ],
            [
                'content' => "Ce projet est une arnaque, ils ont copié notre idée ! Je vais porter plainte contre Evergreen et Sobscore !!!",
                'createdAt' => new \DateTimeImmutable('2026-02-05 16:45:00'),
                'isFlagged' => true, // Commentaire signalé
                'postIndex' => 2, // Post "Sobscore : Premier lauréat"
            ],
        ];

        foreach ($commentsData as $commentData) {
            $comment = new Comment();
            $comment->setContent($commentData['content']);
            $comment->setCreatedAt($commentData['createdAt']);
            $comment->setIsFlagged($commentData['isFlagged']);
            $comment->setPost($posts[$commentData['postIndex']]);
            $comment->setAuthor($user);
            $manager->persist($comment);
        }

        $manager->flush();
    }
}
