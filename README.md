# 🎓 SenLearn Academy

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white"/>
  <img src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/>
  <img src="https://img.shields.io/badge/Git-GitHub-F05032?style=for-the-badge&logo=git&logoColor=white"/>
</p>

<p align="center">

# 📚 Plateforme Intelligente de Gestion Académique

**Projet universitaire développé en PHP, MySQL et Bootstrap**

</p>

---

# 📖 Présentation

**SenLearn Academy** est une plateforme web de gestion académique développée dans le cadre d'un projet universitaire.

Elle a pour objectif de moderniser la gestion des établissements scolaires en proposant une solution numérique permettant de gérer efficacement les étudiants, les enseignants, les cours ainsi que les activités académiques.

Grâce à une interface simple, intuitive et sécurisée, la plateforme facilite le travail administratif et améliore le suivi pédagogique.

---

# 🎯 Objectifs

- Digitaliser la gestion académique.
- Centraliser les informations des étudiants et enseignants.
- Simplifier les tâches administratives.
- Améliorer le suivi pédagogique.
- Offrir une plateforme moderne et intuitive.

---

# ✨ Fonctionnalités

## 🔐 Authentification

- Connexion sécurisée
- Gestion des sessions
- Déconnexion
- Contrôle d'accès selon le rôle

---

## 👨‍🎓 Gestion des étudiants

- Ajouter un étudiant
- Modifier un étudiant
- Supprimer un étudiant
- Rechercher un étudiant
- Consulter la liste des étudiants

---

## 👨‍🏫 Gestion des professeurs

- Ajouter un professeur
- Modifier un professeur
- Supprimer un professeur
- Consulter la liste des professeurs

---

## 📚 Gestion des cours

- Ajouter un cours
- Modifier un cours
- Supprimer un cours
- Consulter les cours

---

## 📝 Gestion des notes et des devoirs

- Ajouter des notes
- Gérer les devoirs
- Consulter les résultats
- Calcul automatique des moyennes

---

## 📅 Gestion des emplois du temps

- Ajouter un emploi du temps
- Modifier un emploi du temps
- Consulter les horaires

---

## 📖 Gestion de la bibliothèque

- Ajouter des ouvrages
- Consulter les ressources
- Gérer les documents pédagogiques

---

## 📌 Gestion des absences

- Enregistrer une absence
- Consulter les absences
- Assurer le suivi des étudiants

---

## 📊 Tableau de bord

- Vue d'ensemble des activités
- Accès rapide aux différents modules

---

# 👥 Acteurs de la plateforme

### 👨‍💼 Administrateur

- Gestion des étudiants
- Gestion des enseignants
- Gestion des cours
- Gestion des notes
- Gestion des emplois du temps
- Gestion de la bibliothèque
- Gestion des absences

### 👨‍🏫 Enseignant

- Consulter les étudiants
- Ajouter des notes
- Gérer les devoirs
- Consulter les emplois du temps

### 👨‍🎓 Étudiant

- Consulter ses informations
- Consulter ses notes
- Consulter son emploi du temps

---

# 💻 Technologies utilisées

## Front-end

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

## Back-end

- PHP

## Base de données

- MySQL

---

# 🛠️ Outils utilisés

| Outil | Utilisation |
|---------|-------------|
| Visual Studio Code | Développement de l'application |
| XAMPP | Serveur local Apache, PHP et MySQL |
| phpMyAdmin | Administration de la base de données |
| Git | Gestion des versions |
| GitHub | Hébergement du code source |
| Figma | Conception des maquettes |
| Trello | Gestion et suivi du projet |
| OBS Studio | Enregistrement de la vidéo de démonstration |

---

# 📂 Structure du projet

```
SenLearn Academy
│
├── css/
├── js/
├── images/
├── config/
├── includes/
├── database/
│
├── index.php
├── login.php
├── dashboard.php
│
├── professeur.php
├── ajouter_professeur.php
├── supprimer_professeur.php
│
└── README.md
```

---
# 📋 Méthodologie

Le projet a été réalisé selon les étapes suivantes :

- Analyse des besoins
- Analyse SWOT et QQOQCP
- Spécifications fonctionnelles
- Modélisation UML
- Maquettage des interfaces avec Figma
- Développement de la plateforme
- Tests
- Démonstration avec OBS Studio

---

# ⚙️ Installation

## 1. Cloner le projet

```bash
git clone https://github.com/fa92266/Notre-projet-SLA.git
```

---

## 2. Accéder au dossier
```bash
cd Notre-projet-SLA
```

---

## 3. Copier le projet dans XAMPP

```
C:\xampp\htdocs\
```

ou sous Linux

```
/var/www/html/
```

---

## 4. Démarrer XAMPP

Lancer :

- Apache
- MySQL

---

## 5. Importer la base de données


Créer une base de données dans **phpMyAdmin** puis importer le fichier SQL.

---

## 6. Configurer la connexion

Modifier les informations de connexion dans le fichier de configuration :

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "nom_de_la_base";
```

---

## 7. Lancer le projet

```
http://localhost/Notre-projet-SLA
# 🎨 Maquettes Figma

Les interfaces graphiques ont été conçues avec **Figma** avant le développement.

👉 https://www.figma.com/design/AqwOujtmdByYR5a2UwJpoj/Projet_Scolaire

---

# 📂 Dépôt GitHub

👉 https://github.com/fa92266/Notre-projet-SLA

---

# 📸 Captures d'écran

Vous pouvez ajouter ici :

- 🏠 Page d'accueil
- 🔐 Connexion
- 📊 Tableau de bord
- 👨‍🎓 Gestion des étudiants
- 👨‍🏫 Gestion des professeurs
- 📚 Gestion des cours
- 📝 Gestion des notes
- 📅 Gestion des emplois du temps
- 📖 Bibliothèque
# 🚀 Perspectives d'amélioration

- Génération automatique des bulletins PDF
- Notifications
- Messagerie interne
- Application mobile
- API REST
- Tableau de bord statistique
- Export PDF et Excel
- Paiement en ligne des frais de scolarité

---

# 👨‍💻 Équipe de développement

Le projet **SenLearn Academy** a été réalisé par :

- 👩 Fatou Mbaye
- 👨 Deybou Amadou Ba
- 👨 Ameth Simal
- 👩 Youma Aissa Coulibaly
- 👩 Rougui Sow
- 👩 Dialika Mané
- 👩 Mame Sokhna Tall Lakor
- 👨 Ndambao Diouf
# 🤝 Contribution

Les contributions sont les bienvenues.

```bash
git checkout -b nouvelle-fonctionnalite

git commit -m "Ajout d'une nouvelle fonctionnalité"

git push origin nouvelle-fonctionnalite
```

Puis ouvrir une **Pull Request**.

---

# 📄 Licence

Ce projet est réalisé dans un cadre académique.

Il est destiné à des fins pédagogiques et de démonstration.

---

# 🙏 Remerciements

Nous remercions notre encadrant ainsi que tous les membres de l'équipe pour leur implication dans la réalisation de ce projet.


---

<p align="center">

## ⭐ SenLearn Academy ⭐

### Une plateforme moderne pour une gestion académique intelligente.

Développée avec ❤️ par l'équipe **SenLearn Academy**.

</p>
