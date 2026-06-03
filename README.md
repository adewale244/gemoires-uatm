# gemoires-uatm
bash

cat > /mnt/user-data/outputs/README.md << 'EOF'
<div align="center">

# 📚 GéMoires — UATM GASA Formation

**Bibliothèque numérique académique de l'Université Africaine de Technologie et de Management**

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.4-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

</div>

---

## 🎯 Présentation du projet

GéMoires est une plateforme web complète de gestion et de consultation des mémoires académiques de l'UATM GASA Formation. Elle permet aux étudiants, professeurs et au personnel administratif d'accéder aux mémoires **24h/24**, depuis n'importe quel appareil, **sans possibilité de téléchargement ni de copie**.

### Fonctionnalités principales

- 🔐 **Authentification sécurisée** avec gestion des rôles (admin, directeur, professeur, diplômé, consultant)
- 📄 **Visionneuse PDF intégrée** (PDF.js) — lecture sans téléchargement possible
- 📤 **Soumission de mémoires** avec workflow de validation professeur
- 🔔 **Notifications en temps réel** (in-app + email automatique via PHPMailer)
- 🔍 **Recherche avancée** par titre, filière, année, niveau
- ❤️ **Interactions** — likes, commentaires, favoris
- 📊 **Import massif** de mémoires anciens via fichier Excel (PhpSpreadsheet)
- 📈 **Tableau de bord statistique** avec graphiques
- 🚩 **Modération** des commentaires signalés

---

## 🏗️ Architecture technique

Le projet suit une architecture **MVC (Modèle - Vue - Contrôleur)** en PHP natif, sans framework.

```
gemoires/
├── app/
│   ├── controllers/         # Contrôleurs MVC
│   │   ├── AuthController.php
│   │   ├── AdminController.php
│   │   ├── MemoiresController.php
│   │   ├── ProfesseurController.php
│   │   ├── UtilisateurController.php
│   │   ├── UploadController.php
│   │   ├── NotificationController.php
│   │   └── ProfilController.php
│   ├── models/              # Modèles (à venir)
│   └── core/
│       ├── App.php          # Routeur principal
│       ├── Controller.php   # Classe mère des contrôleurs
│       ├── Database.php     # Singleton PDO
│       └── Mailer.php       # Service d'envoi d'emails
├── config/
│   ├── config.php           # Constantes globales
│   ├── database.php         # Paramètres BDD
│   └── mail.php             # Configuration SMTP
├── views/
│   ├── auth/                # Pages de connexion
│   ├── admin/               # Dashboard, utilisateurs, upload, stats
│   ├── memoires/            # Bibliothèque, consultation, soumission
│   ├── professeur/          # Espace de validation
│   ├── notifications/       # Centre de notifications
│   ├── profil/              # Page profil utilisateur
│   └── partials/            # Composants réutilisables (navbar)
├── public/
│   ├── css/
│   ├── js/
│   └── assets/              # Logo UATM, images
├── storage/
│   └── memoires/            # Fichiers PDF stockés
├── vendor/                  # Dépendances Composer
├── .htaccess                # Réécriture d'URL Apache
└── index.php                # Point d'entrée
```

---

## 🛠️ Stack technique

| Couche | Technologie |
|--------|------------|
| Backend | PHP 8.3 — Architecture MVC maison |
| Base de données | MySQL 8.4 — PDO avec requêtes préparées |
| Frontend | HTML5, Tailwind CSS, JavaScript vanilla |
| Visionneuse PDF | PDF.js (lecture sécurisée sans téléchargement) |
| Import Excel | PhpSpreadsheet 2.x |
| Emails | PHPMailer + SMTP Gmail |
| Serveur local | WampServer (Apache + PHP + MySQL) |
| Versioning | Git + GitHub |

---

## 👥 Acteurs du système

| Rôle | Droits |
|------|--------|
| **Administrateur** | Accès total, gestion système |
| **Directeur des études** | Création de comptes, upload mémoires anciens (unitaire ou masse Excel) |
| **Professeur** | Validation / rejet des mémoires soumis, consultation |
| **Étudiant diplômé** (L3 / M2) | Soumission de mémoire, consultation, like, commentaire, favori |
| **Étudiant consultant** (L1 / L2 / M1) | Consultation, like, commentaire, favori |

---

## ⚙️ Prérequis

- [WampServer](https://www.wampserver.com/) (ou XAMPP / Laragon) avec PHP ≥ 8.0 et MySQL
- [Composer](https://getcomposer.org/) pour les dépendances PHP
- [Git](https://git-scm.com/)
- Un compte Gmail avec mot de passe d'application (pour les emails)

---

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/adewale244/gemoires-uatm.git
cd gemoires-uatm
```

### 2. Placer dans le répertoire du serveur

Copier le dossier dans `C:\wamp64\www\` (ou l'équivalent de votre serveur local).

### 3. Installer les dépendances

```bash
composer install
```

### 4. Créer la base de données

- Ouvrir [phpMyAdmin](http://localhost/phpmyadmin)
- Créer une base de données nommée `gemoires_db` en `utf8mb4_general_ci`
- Importer le fichier `database/gemoires_db.sql` (si disponible) ou exécuter le script SQL de création des tables

### 5. Configurer la connexion BDD

Modifier `config/database.php` :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gemoires_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Votre mot de passe MySQL
define('DB_CHARSET', 'utf8mb4');
```

### 6. Configurer l'URL de l'application

Modifier `config/config.php` :

```php
define('APP_URL', 'http://localhost/gemoires');
```

### 7. Configurer les emails (optionnel)

Modifier `config/mail.php` avec vos identifiants Gmail :

```php
define('MAIL_USERNAME', 'votre.email@gmail.com');
define('MAIL_PASSWORD', 'votre_mot_de_passe_app');
```

### 8. Accéder à l'application

Ouvrir [http://localhost/gemoires](http://localhost/gemoires)

**Compte administrateur par défaut :**

| Champ | Valeur |
|-------|--------|
| Email | `admin@uatm.bj` |
| Mot de passe | `password` |

---

## 🗄️ Schéma de la base de données

La base de données contient **11 tables** :

`utilisateurs` · `memoires` · `workflow` · `commentaires` · `likes` · `notifications` · `favoris` · `recherches` · `consultations` · `signalements` · `config_systeme`

---

## 📋 Workflow de validation d'un mémoire

```
Étudiant diplômé          Professeur encadreur        Plateforme
      │                          │                         │
      │── Soumet mémoire ──────► │                         │
      │                          │◄── Notification ────────│
      │                          │── Prend une décision    │
      │                          │     ├── ✅ Approuvé ───►│── Mémoire publié
      │◄── Notification ─────────│     ├── ⚠️ Correction   │── Mémoire en correction
      │◄── Email automatique ────│     └── ❌ Rejeté ──────│── Mémoire rejeté
```

---

## 🔒 Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Requêtes SQL préparées (protection contre les injections SQL)
- Vérification des rôles sur chaque route
- Lecture PDF sécurisée sans possibilité de téléchargement (`#toolbar=0`)
- Blocage du clic droit et des raccourcis clavier (Ctrl+S, Ctrl+P)
- Sessions PHP sécurisées

---

## 📸 Aperçu de l'interface

| Page | Description |
|------|-------------|
| Page de connexion | Interface double panneau aux couleurs UATM (bleu marine & or) |
| Dashboard admin | Statistiques en temps réel, derniers mémoires et comptes |
| Bibliothèque | Grille de cartes avec recherche et filtres avancés |
| Consultation PDF | Visionneuse PDF intégrée avec panneau commentaires |
| Espace professeur | File de validation avec modal de décision |

---

## 🗺️ Feuille de route

- [x] Authentification multi-rôles
- [x] Dashboard administrateur
- [x] Gestion des utilisateurs
- [x] Bibliothèque avec recherche et filtres
- [x] Visionneuse PDF sécurisée (PDF.js)
- [x] Soumission et workflow de validation
- [x] Notifications in-app
- [x] Emails automatiques (PHPMailer)
- [x] Upload unitaire et import Excel en masse
- [x] Likes, commentaires, favoris
- [x] Page profil utilisateur
- [x] Statistiques et graphiques
- [x] Modération des signalements
- [ ] Application mobile (PWA)
- [ ] Export statistiques PDF
- [ ] Système de tags sur les mémoires

---

## 👨‍💻 Auteur

Développé dans le cadre d'un projet académique à l'**UATM GASA Formation** (Université Africaine de Technologie et de Management).

---

## 📄 Licence

Ce projet est à usage académique — UATM GASA Formation © 2025.

---

<div align="center">
  <strong>GéMoires</strong> — Bibliothèque numérique académique UATM GASA
</div>
EOF
echo "README créé avec succès"
