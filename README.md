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


| Branches                                                    | Responsable(s) |
| ----------------------------------------------------------- |----------------|
| **feat/env-setup** : environnement dev (Docker/DB, ports)   | Mathilde       |
| **feat/auth** : user + login + register                     | Mathilde       |
| **feat/posts-front** : pages accueil + liste + détail       |                |
| **feat/comments** : ajout commentaire + signalement         |                |
| **feat/admin** : backoffice (CRUD + modération)             | Mathieu        |
| **feat/contact** : formulaire contact + mail                |                |
| Tests fonctionnels                                          |                |
| Documentation : webographie + README.md                     | Mathilde       |

### Calendrier de suivi du projet

| Échéance | Objectif                                                                                                                                                                            |
| :------: |:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
|  25/01   | Installation de l’environnement de développement Symfony (IDE, serveur local, Composer/Symfony CLI) puis création et lancement du projet Symfony en local.                          |
|  01/02   | Mise en place de la BDD via .env, ajout de l’authentification, création de l’entité Post, génération du CRUD et installation/configuration d’un bundle d’administration (EasyAdmin) |

## BRANCHE ``feat/env-setup``

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

## Chargement des données
Une fixture a été mise en place afin de charger facilement et rapidement des données exemples en base de données.
Cela évite notamment de créer manuellement et plusieurs fois des Users, des Posts etc...

Actuellement, cette fixture permet :
* de créer un administrateur dont les identifiants sont :
  * **email** : admin@example.com
  * **mot de passe** : adminpass
* de créer un utilisateur dont les identifiants sont
  * **email** : user@example.com
  * **mot de passe** : userpass

Il est possible de rajouter la génération d'autres entités, comme Post, Comment etc.
Pour la modifier, il suffit d'éditer ce fichier : `src/DataFixtures/AppFixtures.php`.

Pour charger ces données dans votre base de données, il suffit d'éxécuter la commande suivante :
```bash
php bin/console doctrine:fixtures:load
```

_**⚠️ Attention** : le chargement d'une fixture purge la base de données. Toutes les données présentes dans votre BDD seront écrasées._

## BRANCHE ``feat/auth``

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

### Création de l'entité ``User``
```bash
php bin/console make:user

•	The name of the security user class (e.g. User) [User] > User
•	Do you want to store user data in the database (via Doctrine)? (yes/no) [yes] > yes
•	Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]: > email
•	Does this app need to hash/check user passwords? (yes/no) [yes]: > yes
```
### Migration de ``User`` & Création tables
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
- ``password_hashers`` pour ``User``
- ``provider`` basé ``User`` + ``email`` 
- ``firewall`` main avec : ``custom_authenticator``, ``logout``, ``rememeber_me``

**Ajout d'un ``acces_control`` dans ``security.yaml`` pour un front public et un admin sécurisé :**
```bash
access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/register, roles: PUBLIC_ACCESS }
    - { path: ^/admin, roles: ROLE_ADMIN }
```

### Configuration du fichier ``routes.yaml``

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

### Adaptation à la syntaxe Symfony 7 du fichier ``RegistrationFormType.php``
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

### Ajout des liens Login/Register/Logout dans templates > base.html/twig**

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

## BRANCHE ``feat/admin``
### Ajout de l'administrateur en base de données
Il faut commencer par l'ajout d'un administrateur en base de données, en effet seuls les administrateurs peuvent accéder au backoffice.

Pour se faire, il suffit de charger la fixture (`src/DataFixtures/AppFixtures.php`) comme expliqué dans la partie **Chargement des données** :
```bash
php bin/console doctrine:fixtures:load
```


### Accès au backoffice
Les utilisateurs avec le rôle ROLE_ADMIN ont accès au backoffice. L'accès se fait via la route `/admin`, qui renvoi sur le dashboard du backoffice.

### Fonctionnalités implémentées en backoffice

Les fonctionnalités suivantes sont déjà implémentées :
* **Dashboard** : aperçu rapide des informations, comme le nombre d'utilisateurs, le nombre d'administrateurs et le nombre de posts.
* **La gestion des Users avec** :
  * la possibilité de consulter la liste de tous les utilisateurs et leurs informations
  * la possibilité d'éditer les informations des utilisateurs existants
  * la possibilité de créer de nouveaux utilisateurs
  * la possibilité de supprimer des utilisateurs

Des **mesures de sécurités** ont également été implémentés :
* L'administrateur actuellement connecté ne peut pas supprimer son propre compte
* L'impossibilité de supprimer le dernier administrateur présent en base de données
* Les mots de passe modifiés lors de l'édition d'un utilisateur sont re-hachés automatiquement
* Lors de l'édition d'un mot de passe, impossible de voir le mot de passe défini précédemment

D'autres fonctions restent à implémenter, selon l'avancée du projet.
