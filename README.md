# UEL316 - Application web d’actualités avec Symfony 7

## Membres du groupe

| Etudiant.e  |   Alias    |
| :---------: | :--------: |
| Mathilde C. | Clouddy23  |
|   Kamo G.   | Spaghette5 |
| Mathieu L.  |  mathleys  |
| Filippos K. |  filkat34  |

## Branches 

- feat/auth : user + login + register
- feat/posts-front : pages accueil + liste + détail
- feat/comments : ajout commentaire + signalement
- feat/admin : backoffice (CRUD + modération)
- feat/contact : formulaire contact + mail

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

### 2) Lancement de PostgreSQL
```bash
docker compose up -d
docker compose ps
```
PostgreSQL est en local sur 127.0.0.1:5432

### 3) Configuration de la BDD dans Symfony (local)
Créarion d'un fichier .env.local à la racine (à mettre dans .gitignore)

```bash
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

### 4) Initialisation de la BDD
```bash
php bin/console doctrine:database:create
```

### 5) Lancement du serveur Symfony
```bash
symfony serve -d
```

Puis ouvrir : http://127.0.0.1:8000

## BRANCHE ``feat/auth``


