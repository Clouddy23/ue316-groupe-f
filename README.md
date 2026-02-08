# UEL316 - Application web d’actualités avec Symfony 7

## Objectifs

L’objectif principal de ce projet est de concevoir et développer une application web dynamique sans recours à un CMS, en utilisant le **framework Symfony 7**, afin de répondre aux besoins d’un client souhaitant disposer d’un site d’actualités moderne et administrable.

- [x] Mettre en œuvre une **architecture MVC** propre et structurée avec Symfony.
- [x] Développer un **front office** accessible aux visiteurs, permettant : la consultation des actualités, l’affichage des trois dernières actualités sur la page d’accueil, la lecture détaillée d’une actualité, l’ajout et le signalement de commentaires, l’inscription et la connexion des utilisateurs, l’accès à des pages informatives (présentation du client, contact).
- [x] Mettre en place un **système d’authentification sécurisé** avec gestion des rôles utilisateurs.
- [x] Développer un **backoffice** réservé aux administrateurs pour : la gestion des actualités (création, modification, suppression), la modération des commentaires, la gestion des utilisateurs.
- [x] Assurer la persistance des données via **Doctrine ORM** et une **base de données relationnelle**.

## Principe général de collaboration

### Membres du groupe

| Etudiant.e  |   Alias    |
| :---------: | :--------: |
| Mathilde C. | Clouddy23  |
|   Kamo G.   | Spaghette5 |
| Mathieu L.  |  mathleys  |
| Filippos K. |  filkat34  |

### Répartition du travail

Chacun des membres du groupe a contribué au projet selon ses disponibilités et compétences, tout le monde s'est montré impliqué et investi dans le travail demandé.

| Branche | Responsable(s)              | Contenu |
|---|-----------------------------|---|
| **mathilde** | Mathilde (S1)               | Installation de l'environnement, création et lancement du projet Symfony GitHub |
| **feat/env-setup** | Mathilde                    | Mise en place environnement local (Docker PostgreSQL, `.env.local`, init DB) |
| **feat/auth** | Mathilde                    | Authentification (création de l'entité `User`, migrations, login/logout, register, configuration `security.yaml`, liens Twig) |
| **feat/admin** | Mathieu                     | Backoffice `/admin` (dashboard, CRUD Users, règles de sécurité avec auto-protection admin, re-hash des mots de passe) |
| **feat/contact** | Filippos                    | Page contact (formulaire de contact + envoi mail avec Mailer, SMTP/Mailpit, Messenger en synchrone) |
| **feat/posts-back** | Kamo                        | Publications backend (création de l'entité `Post`, migrations, CRUD et endpoints) |
| **feat/posts-front** | Mathieu                     | Publications frontend (accueil, liste des posts, détail d’un post) |
| **feat/comments** | Kamo                            | Commentaires (création de l'entité `Comment`, ajout, affichage, signalement) |
| **feat/posts-images**| Kamo                         | Gestion complète des images (Upload avec VichUploader, redimensionnement, affichage conditionnel) |
| **tests** |                             | Tests fonctionnels |
| **docs** | Mathilde, Filippos, Mathieu | README, webographie |

### Calendrier de suivi du projet

| Échéance | Objectif |
| :------: | :--- |
| 25/01 | Installation de l’environnement de développement Symfony (PHPStorm, serveur local, Composer/Symfony CLI) + Création et lancement du projet Symfony en local. |
| 01/02 | Mise en place BDD/Docker PostgreSQL, ajout de l’authentification (login/logout/register, création des entités `User` et `Post`, génération du CRUD pour les 2 entités, installation/configuration bundle d’administration (EasyAdmin) avec accès sécurisé `/admin`, création du formulaire de contact avec envoi d’email, configuration SMTP (Mailpit) et Messenger en mode synchrone. |
| 08/02 | Terminer entité `Post` (front), ajouter entité `Comment` (création + affichage + signalement), finaliser le formulaire contact (destinataire réel + validations), compléter la sécurité (droits/roles), démarrer les tests, mettre à jour de README |


## BRANCHE `feat/env-setup`

La base PostgreSQL est lancée en local via Docker (volume local).  
Chaque membre doit donc initialiser sa propre base sur sa machine.

### Prérequis :

- PHP + Composer
- Docker Desktop

### Installation des dépendances PHP

```bash
composer install
```

### Lancement de PostgreSQL

```bash
docker compose up -d
docker compose ps
```

PostgreSQL est en local sur 127.0.0.1:5432

### Configuration de la BDD dans Symfony (local)

Créarion d'un fichier .env.local à la racine (à mettre dans .gitignore)

```bash
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

### Initialisation de la BDD

```bash
php bin/console doctrine:database:create
```

### Lancement du serveur Symfony

```bash
symfony serve -d
```

Puis ouvrir : http://127.0.0.1:8000

## BRANCHE `feat/auth`

### Vérification de l'environnement

```bash
docker compose up -d
docker compose ps
```

### Installation des dépendances

```bash
composer require symfony/security-bundle symfony/twig-bundle symfony/validator
composer require --dev symfony/maker-bundle
```

### Création de l'entité `User`

```bash
php bin/console make:user

•	The name of the security user class (e.g. User) [User] > User
•	Do you want to store user data in the database (via Doctrine)? (yes/no) [yes] > yes
•	Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]: > email
•	Does this app need to hash/check user passwords? (yes/no) [yes]: > yes
```

### Migration de `User` & Création tables

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate

•	WARNING! You are about to execute a migration in database "app" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:
> yes
```

### Génération de la page de connexion

```bash
php bin/console make:auth

•	What style of authentication do you want? [Empty authenticator]:
  [0] Empty authenticator
  [1] Login form authenticator
> 1

•	The class name of the authenticator to create (e.g. AppCustomAuthenticator):
> AppAuthenticator

•	Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
> SecurityController

•	Do you want to generate a '/logout' URL? (yes/no) [yes]:
> yes

•	Do you want to support remember me? (yes/no) [yes]:
> yes

•	How should remember me be activated? [Activate when the user checks a box]:
  [0] Activate when the user checks a box
  [1] Always activate remember me
> 0
```

### Génération de la page d'inscription

```bash
php bin/console make:registration-form

•	Do you want to add a #[UniqueEntity] validation attribute to your User class to make sure duplicate accounts aren't created? (yes/no) [yes]:
> yes

•	Do you want to send an email to verify the user's email address after registration? (yes/no) [yes]:
> no

•	Do you want to automatically authenticate the user after registration? (yes/no) [yes]:
> yes

•	Do you want to generate PHPUnit tests? [Experimental] (yes/no) [no]:
> no
```

### Réglage de la sécurité

**Vérification dans config > packages > security.yaml :**

- `password_hashers` pour `User`
- `provider` basé `User` + `email`
- `firewall` main avec : `custom_authenticator`, `logout`, `rememeber_me`

**Ajout d'un `acces_control` dans `security.yaml` pour un front public et un admin sécurisé :**

```bash
access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/register, roles: PUBLIC_ACCESS }
    - { path: ^/admin, roles: ROLE_ADMIN }
```

### Configuration du fichier `routes.yaml`

**Chargement des controlleurs**

```bash
controllers:
    resource: ../src/Controller/
    type: attribute

index:
    path: /
    controller: App\Controller\DefaultController::index
```

**Vider le cache**

```bash
php bin/console cache:clear
```

**Vérification des routes**

```bash
php bin/console debug:router
```

### Adaptation à la syntaxe Symfony 7 du fichier `RegistrationFormType.php`

Adapter la syntaxe des contraintes de validation du formulaire d’inscription aux pratiques de Symfony 7, en remplaçant l’ancienne configuration par tableaux par l’utilisation des arguments nommés.

```bash
->add('agreeTerms', CheckboxType::class, [
    'mapped' => false,
    'constraints' => [
        new IsTrue(
            message: 'You should agree to our terms.'
        ),
    ],
])
```

```bash
    'constraints' => [
        new NotBlank(
            message: 'Please enter a password',
        ),
        new Length(
            min: 6,
            minMessage: 'Your password should be at least {{ limit }} characters',
            max: 4096,
        ),
    ],
])
```

### Ajout des liens Login/Register/Logout dans templates > base.html/twig\*\*

- Ajout d'un bouton LOGOUT si connecté
- Ajout des boutons LOGIN/REGISTER si non connecté

```bash
    <body>
        <nav>
        {% if app.user %}
            <span>Connecté en tant que {{ app.user.email }}</span>
            <a href="{{ path('app_logout') }}">Déconnexion</a>
        {% else %}
            <a href="{{ path('app_login') }}">Connexion</a>
            <a href="{{ path('app_register') }}">Inscription</a>
        {% endif %}
        </nav>

        {% block body %}{% endblock %}
    </body>
```

### Préparation d'un admin pour la future branche `feat/admin`\*\*

**Création de l'admin**
http://127.0.0.1:8000/register

- email : admin@test.fr
- mot de passe : !changeme!test

**Passage de l'utilisateur admin en BDD**

```bash
docker compose exec database psql -U app -d app
```

## BRANCHE `feat/contact`

L'objectif de cette branche est de créer un formulaire de contact par mail. Les commandes suivantes ont été utilisées pour créer à la fois le contrôleur et le formulaire :

```bash
php bin/console make:controller ContactController
php bin/console make:form ContactFormType
```

Nous avons ensuite rédigé le contrôleur du formulaire :

```php
#[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = (new Email())
                ->from('no-reply@siteclient')
                ->replyTo($data['email'])
                ->to('admin@example.com') // A changer avec mail du client
                ->subject($data['subject'])
                ->text(
                    "Name: ".$data['name']."\n".
                    "Email: ".$data['email']."\n".
                    "Message: \n".$data['message']
                );

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent!');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
```

Dans l'`env.dev`que nous avons ajouté l'adresse du service SMTP utilisé par le serveur Symfony : `MAILER_DSN=smtp://127.0.0.1:1025`

Pour finir, afin que les mails soient envoyés directement à des fins de test dès la soumission du formulaire et sans être mis en file d'attente, nous avons modifié le fichier `messenger.yaml` pour permettre l'envoi synchrone :

- nous avons décommenté : `sync: 'sync://'`
- et modifié : `Symfony\Component\Mailer\Messenger\SendEmailMessage: async` en `Symfony\Component\Mailer\Messenger\SendEmailMessage: sync`

Nous avons ensuite à des fins de test, rempli le formulaire et envoyé le message.

![Mailpit](/docs/mailpit.png)
