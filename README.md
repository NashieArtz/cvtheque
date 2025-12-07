# 📂 CVThèque - Gestionnaire de Profils Numériques

Une application web complète permettant aux utilisateurs de créer et gérer leurs CVs en ligne, et aux administrateurs de modérer et rechercher des talents via un tableau de bord avancé.

Développé en PHP Natif (sans framework) pour maîtriser les concepts fondamentaux du web et de la sécurité.

## 🚀 Fonctionnalités

### 👤 Pour les Utilisateurs (Candidats)

Authentification sécurisée : Inscription, Connexion, Hashage des mots de passe.

Profil Dynamique : Édition des infos personnelles, upload d'avatar.

Gestion des Compétences : Ajout de tags (Hard Skills, Soft Skills, Hobbies) en JavaScript natif.

Expériences & Formations : Ajout et modification du parcours professionnel.

Export PDF : Génération automatique du CV au format PDF.

Dark Mode : Thème clair/sombre persistant.

### 🛡️ Pour les Administrateurs

Tableau de Bord : Vue d'ensemble des inscrits.

Moteur de Recherche Avancé : Filtrage par ville, compétence, statut, permis, etc.

Modération : Activation/Désactivation de comptes (Soft delete) ou suppression définitive (Cascade delete).

### 🛠️ Stack Technique

Back-end : PHP 8+ (PDO, Programmation Procédurale & Orientée Objet).

Base de Données : MySQL / MariaDB (Modèle relationnel complexe).

Front-end : HTML5, CSS3, Bootstrap 5, JavaScript (Vanilla).

Outils Tiers : html2pdf.js (Génération PDF).

## ⚙️ Installation

Pré-requis

Un serveur local type XAMPP, WAMP ou Laragon.

PHP 8.0 ou supérieur.

### Étapes

Cloner le dépôt :

git clone [https://github.com/NashieArtz/cvtheque.git](https://github.com/ton-pseudo/cvtheque.git)


### Base de données :

Ouvrez PHPMyAdmin.

Créez une base de données nommée cvtheque.

Importez le fichier cvtheque.sql situé à la racine du projet.

(Optionnel) Importez seed_10_users.sql pour avoir des données de test.

### Configuration :

Vérifiez le fichier config/database.php (ou équivalent) pour adapter les identifiants SQL (root / sans mot de passe par défaut sur XAMPP).

### Lancer :

Accédez à http://localhost/cvtheque/index.php.

## 🗄️ Structure de la Base de Données

Le projet repose sur une architecture relationnelle stricte pour garantir l'intégrité des données :

user : Table centrale.

skills & user_has_skills : Relation Many-to-Many pour gérer les compétences sans doublons.

address : Relation One-to-One.

role : Gestion des permissions (Admin/User).

## 🔒 Sécurité

Ce projet met un point d'honneur à respecter les bonnes pratiques de sécurité web :

Injections SQL : Utilisation systématique de requêtes préparées (PDO::prepare).

Faille XSS : Échappement des sorties via htmlspecialchars().

Uploads : Vérification des types MIME pour les images.

CSRF/Session : Gestion stricte des sessions utilisateurs.

## 📸 Aperçus

[À Venir]

Page de Profil

Dashboard Admin avec les filtres

Le rendu PDF

## 📝 Auteur

Ange WU - Développeur Fullstack - www.linkedin.com/in/ange-wu-959357229 \n
Florent Zysk - Développeur Back-End - https://www.linkedin.com/in/florentzysk/ \n
Mathieu Leboucher - Développeur Front-End - https://www.linkedin.com/in/mathieu-leboucher-24b57139b/

Projet réalisé dans le cadre de la formation Développement Web à la Need For School.
