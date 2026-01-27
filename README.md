## Installation (dev)

La base PostgreSQL est lancée en local via Docker (volume local).  
Chaque membre doit donc initialiser sa propre base sur sa machine.

### Prérequis
- PHP + Composer
- Docker Desktop

### 1) Installer les dépendances PHP
```bash
composer install
```

### 2) Lancer PostgreSQL
```bash
docker compose up -d
docker compose ps
```
PostgreSQL est exposé en local sur 127.0.0.1:5432

### 3) Configurer la base dans Symfony (local)
Créer un fichier .env.local à la racine (à mettre dans .gitignore)
``DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"``

### 4) Initialiser la base
``php bin/console doctrine:database:create``

### 5) Lancer le serveur Symfony
``symfony serve -d``
Puis ouvrir : http://127.0.0.1:8000


