# UE L316 - Dev Backend Symfony - Semaine 4 (Groupe F)

## Membres du groupe

| Etudiant.e  |   Alias    |
| :---------: | :--------: |
| Mathilde C. | Clouddy23  |
|   Kamo G.   | Spaghette5 |
| Mathieu L.  |  mathleys  |
| Filippos K. |  filkat34  |

## Dépôt Github

Un dépôt de code pour le développement du projet a été créé sur Github : [https://github.com/Clouddy23/ue316-groupe-f](https://github.com/Clouddy23/ue316-groupe-f).

## Hebérgement

Le site a été mis en production sur l'URL : [https://evergreen.alwaysdata.net/](https://evergreen.alwaysdata.net/).

## Contexte

Vous êtes développeur web junior dans une agence digitale. Pour le compte de l’un de vos clients, vous devez créer un site web dynamique sans utiliser un CMS. Le choix du framework Symfony a été validé par le client.

## Missions

Après une brève présentation du client, le chef de projet vous présente les fonctionnalités attendues.

### Front office

- [x] une page d’accueil essentiellement graphique contenant les 3 dernières actualités
- [x] une page d’actualités
- [x] une page pour chaque actualité avec la possibilité de commentaire et de signaler les commentaires
- [x] une page de présentation du client
- [x] une page de contact
- [x] une page d’inscription
- [x] une page de connexion

### Backoffice

- [x] gestion des posts
- [x] gestion des commentaires
- [x] gestion des utilisateurs

### Livrables

- [x] Spécifications fonctionnelles du projet
- [x] Le diagramme de classe de la solution cible
- [x] Le repository GITHUB du projet
- [x] Le lien vers l’application hébergée sur le web

## Spécifications fonctionnelles

### Symfony et bundles

Ce projet est développé avec _Symfony 8.0_. Quelques bundles supplémentaires ont été installés pour les besoins de ce projet comme :

- `symfony/security-bundle`  pour gérer l'authentification des utilisateurs
- `symfony/mailer` - pour l'envoi d'emails du formulaire de contact
- `doctrine/doctrine-fixtures-bundle` - pour charger des données de test en base de données

Le front-end de l'application utilise _Bootstrap 4.3.1_ comme framework CSS. Bootstrap est chargé via CDN directement dans le template de base (`base.html.twig`).

### Base de données

Le SGBD choisi pour le développement est _SQLite_ parce qu'il permet de partager la base de données en un seul fichier.

Pour créer la base de données et charger les données de test, exécutez les commandes suivantes :

```bash
# Exécuter les migrations pour créer les tables
php bin/console doctrine:migrations:migrate

# Charger les données de test (fixtures)
php bin/console doctrine:fixtures:load
```

Les fixtures créent :

- 2 utilisateurs : `admin@evergreen.fr` (mot de passe: `admin123`) et `user@evergreen.fr` (mot de passe: `user123`)
- 5 articles de blog
- 3 commentaires dont 1 signalé

Voici le diagramme de classes utilisé pour implémenter ce site web :

![diagrammeClasses](/out/docs/diagramme-classes/Diagramme%20de%20classes%20-%20Evergreen.png)
