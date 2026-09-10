# Gestion des Événements — Medina Group

Plateforme web de gestion et de promotion des événements culturels et professionnels, développée pour **Medina Group** dans le cadre d'un stage ingénieur.

##  À propos

Cette plateforme permet à Medina Group de centraliser la gestion complète de ses événements : création, promotion, inscriptions des visiteurs, gestion des sponsors et génération de badges personnalisés. Elle offre un tableau de bord dédié selon le rôle de l'utilisateur (Administrateur, Responsable, Agent).

## Fonctionnalités principales

-  **Gestion des événements** : création, modification, suppression et consultation des événements (titre, dates, lieu, description)
- **Gestion des utilisateurs** : gestion des comptes avec différents rôles (Admin, Responsable, Agent)
- **Gestion des inscriptions** : inscription des visiteurs aux événements avec suivi en temps réel
- **Gestion des sponsors** : ajout et gestion des logos sponsors par événement (upload d'images)
- **Génération de badges** : création et impression de badges visiteurs personnalisés (nom, événement, dates, lieu, sponsors)
- **Authentification & rôles** : système de connexion sécurisé avec accès différencié selon le rôle
- **Tableaux de bord dédiés** : dashboard Admin, Responsable et Agent avec vues adaptées à chaque rôle

## 🛠️ Stack technique

| Catégorie | Technologies |
|---|---|
| **Backend** | PHP 8, Symfony 6/7 |
| **Base de données** | MySQL / MariaDB |
| **ORM** | Doctrine |
| **Templating** | Twig |
| **Gestion de version** | Git |
| **Autres** | Symfony Forms, Symfony Security, Doctrine Migrations |

##  Architecture du projet

```
├── src/
│   ├── Controller/       # Contrôleurs (Event, Inscription, User, Dashboard...)
│   ├── Entity/           # Entités Doctrine (Event, Inscription, User)
│   ├── Form/             # Formulaires Symfony (EventType, InscriptionType...)
│   └── Repository/       # Repositories Doctrine
├── templates/            # Vues Twig (par rôle : admin, agent, responsable)
├── migrations/           # Migrations de base de données
├── public/uploads/       # Fichiers uploadés (logos, sponsors)
└── config/               # Configuration Symfony
```

## Rôles et permissions

| Rôle | Accès |
|---|---|
| **Admin** | Gestion complète : événements, utilisateurs, inscriptions, sponsors |
| **Responsable** | Gestion des événements et inscriptions dont il a la charge |
| **Agent** | Gestion des inscriptions et génération des badges sur le terrain |

## Installation locale

### Prérequis
- PHP >= 8.1
- Composer
- Symfony CLI (recommandé)
- MySQL / MariaDB

### Étapes

```bash
# Cloner le dépôt
git clone https://github.com/MarahMhadbhi/gestion-evenements-medina.git
cd gestion-evenements-medina

# Installer les dépendances
composer install

# Configurer la base de données (créer un .env.local)
# DATABASE_URL="mysql://root:@127.0.0.1:3306/lapaix?serverVersion=mariadb-10.4.32"

# Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur local
symfony server:start
```

L'application sera accessible sur `http://localhost:8000`.


## Contexte du projet

Projet développé dans le cadre d'un **stage ingénieur (6 semaines, juin-juillet 2025)** au sein de la **Société La Paix**, pour le compte de **Medina Group**.

## Auteure

**Marah Mhadbhi**
Étudiante Ingénieure en Software Architecture Engineering — ESPRIT
[GitHub](https://github.com/MarahMhadbhi)

## Licence

Projet réalisé dans un cadre académique et professionnel — usage interne à Medina Group.
