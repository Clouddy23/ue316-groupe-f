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


| Branches                                                    | Responsable(s)                      |
| ----------------------------------------------------------- | --------------------                |
| **feat/env-setup** : environnement dev (Docker/DB, ports)   | Mathilde                            |
| **feat/auth** : user + login + register                     | Mathilde                            |
| **feat/posts-front** : pages accueil + liste + détail       |                                     |
| **feat/comments** : ajout commentaire + signalement         |                                     |
| **feat/admin** : backoffice (CRUD + modération)             |                                     |
| **feat/contact** : formulaire contact + mail                |                                     |
| Tests fonctionnels                                          |                                     |
| Documentation : webographie + README.md                     | Mathilde                            |

### Calendrier de suivi du projet

| Échéance | Objectif                                                                                                                                                                                  |
| :------: | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
|  25/01   | Installation del’environnement de développement Symfony (IDE, serveur local, Composer/Symfony CLI) puis création et lancement du projet Symfony en local.                                 |
|  01/02   | Mise en place de la BDD via .env, ajout de l’authentification, création de l’entité Post, génération du CRUD et installation/configuration d’un bundle d’administration (EasyAdmin)       |

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

WARNING! You are about to execute a migration in database "app" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]: > yes
```

### Génération de la page de connexion
```bash
php bin/console make:auth

What style of authentication do you want? [Empty authenticator]:
  [0] Empty authenticator
  [1] Login form authenticator
> 1

The class name of the authenticator to create (e.g. AppCustomAuthenticator):
> AppAuthenticator

Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
> SecurityController

Do you want to generate a '/logout' URL? (yes/no) [yes]:
> yes

Do you want to support remember me? (yes/no) [yes]:
> yes

How should remember me be activated? [Activate when the user checks a box]:
  [0] Activate when the user checks a box
  [1] Always activate remember me
> 0
```


