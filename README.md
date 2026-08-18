# Klaxon

Klaxon est une application intranet de covoiturage entre les agences d’une entreprise. Elle affiche au public les trajets futurs qui disposent encore de places, permet aux employés connectés de proposer et gérer leurs trajets, et fournit à l’administrateur les écrans de consultation et de gestion demandés dans le brief.

## Fonctionnalités

- Liste publique des trajets disponibles, triés par date de départ croissante.
- Connexion sécurisée par mot de passe, session, contrôle des rôles et jetons CSRF.
- Détails du conducteur dans une fenêtre modale pour les employés connectés.
- Création, modification et suppression d’un trajet par son auteur.
- Administration de tous les trajets et consultation des employés issus du système RH.
- Création, modification et suppression des agences réservées à l’administrateur.
- Validation de cohérence des agences, dates et nombres de places.

Les employés sont volontairement disponibles en lecture seule : l’application ne permet ni leur création, ni leur modification, ni leur suppression.

## Prérequis

- PHP 8.3 ou version compatible, avec PDO MySQL ;
- MySQL ou MariaDB ;
- Composer ;
- Node.js et npm pour compiler Sass.

## Installation

1. Cloner le dépôt puis ouvrir un terminal dans son dossier.
2. Installer les dépendances PHP et front-end :

   ```bash
   composer install
   npm install
   ```

3. Créer et alimenter la base de données avec `database/schema.sql`, puis `database/seed.sql`. Par exemple :

   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p klaxon < database/seed.sql
   ```

4. Adapter au besoin les paramètres de connexion dans `config/database.php`.
5. Compiler le thème Bootstrap personnalisé :

   ```bash
   npm run sass:build
   ```

6. Lancer le serveur local :

   ```bash
   php -S localhost:8000
   ```

L’application est alors accessible à l’adresse `http://localhost:8000`.

## Comptes de démonstration

| Rôle | Adresse email | Mot de passe |
|---|---|---|
| Administrateur | `alice.durand@entreprise.test` | `Admin123!` |
| Utilisateur | `hugo.martin@entreprise.test` | `User123!` |

Les trajets du jeu d’essai utilisent des dates calculées par rapport au jour d’import afin que la page d’accueil contienne toujours des exemples pertinents.

## Qualité et tests

Créer une base vide nommée `klaxon_test` avant le premier lancement des tests :

```sql
CREATE DATABASE klaxon_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

Puis exécuter :

```bash
composer analyse
composer test
```

PHPStan analyse le code au niveau 5. PHPUnit couvre notamment toutes les écritures applicatives dans les tables `trips` et `agencies`.

## Architecture et documentation

- `index.php` : routeur frontal ;
- `src/Controller` : préparation des actions et contrôles d’accès ;
- `src/Repository` : accès PDO à la base de données ;
- `src/Validation` : règles métier réutilisables ;
- `src/View` : vues PHP Bootstrap ;
- `assets/scss/main.scss` : palette appliquée via les variables Sass de Bootstrap ;
- `database` : schémas et jeu d’essai ;
- `tests` : tests PHPUnit ;
- `docs/mcd.png` : modèle conceptuel de données ;
- `docs/mld.txt` : modèle logique de données.

La palette imposée est centralisée dans le fichier Sass : `#f1f8fc`, `#0074c7`, `#00497c`, `#384050`, `#cd2c2e` et `#82b864`.

## Dépôt

Le dépôt déclaré par le projet est : <https://github.com/Khallas111/SitePhp>.
