# SnowTricks

Bienvenue sur **SnowTricks**, un site communautaire dédié aux passionnés de snowboard ! Ce projet a été développé avec le framework Symfony et permet aux utilisateurs de répertorier, partager et discuter des figures (tricks) de snowboard.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/23b27b977e44431ca7daaf41509d9b56)](https://app.codacy.com/gh/Judes77850/snowtricks/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Fonctionnalités

- **Inscription et Connexion** : Les utilisateurs peuvent s'inscrire et se connecter pour accéder aux fonctionnalités du site.
- **Gestion des Tricks** : Les utilisateurs peuvent créer, modifier et supprimer des tricks. Chaque trick est accompagné d'une description, d'images et de vidéos.
- **Commentaires** : Les utilisateurs peuvent commenter les tricks pour partager leurs avis ou poser des questions.
- **Galerie** : Une galerie d'images et de vidéos est disponible pour chaque trick.
- **Modération** : Les administrateurs peuvent modérer les commentaires et les tricks.

## Prérequis

- **PHP 8.1 ou supérieur**
- **Composer**
- **Symfony CLI**
- **MySQL** ou tout autre SGBD compatible avec Doctrine

## Installation

### 1. Cloner le dépôt

bash
git clone https://github.com/Judes77850/snowtricks.git
cd snowtricks

### 2. Installer les dépendences

bash
composer install

### 3. Modification du fichier .env

Pour configurer votre connexion à la base de données, ouvrez le fichier .env et modifiez la ligne suivante :

plaintext
Copier le code
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
Remplacez !ChangeMe! par votre mot de passe et ajustez le reste de l'URL selon votre configuration (type de base de données, hôte, port, nom de la base, etc.).

Exemple pour MySQL :
plaintext
Copier le code
DATABASE_URL="mysql://app:monMotDePasse@127.0.0.1:3306/mon_app?serverVersion=8&charset=utf8mb4"
N'oubliez pas de sauvegarder vos modifications.

### 4. Chargement des Fixtures

Pour charger les fixtures dans votre application, suivez ces étapes :

Assurez-vous que les fixtures sont définies dans le répertoire approprié (généralement src/DataFixtures).
Exécutez la commande suivante pour charger les fixtures dans votre base de données :
bash
Copier le code
php bin/console doctrine:fixtures:load
Confirmez le chargement : Lors de l'exécution de la commande, vous serez invité à confirmer que vous souhaitez supprimer les données existantes dans la base de données (sauf si vous utilisez l'option --append). Tapez yes pour continuer.
Vérifiez les données : Une fois les fixtures chargées, vous pouvez vérifier que les données ont bien été insérées dans votre base de données.
Options supplémentaires
Pour ajouter des données sans supprimer les données existantes, utilisez :
bash
Copier le code
php bin/console doctrine:fixtures:load --append


