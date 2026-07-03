<?php
if(!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 mb-4">
    <a class="navbar-brand fw-bold text-warning d-flex align-items-center" href="dashboard.php">
        <img src="image/logo.png.png" alt="Logo" style="height: 40px; width: auto; margin-right: 10px;">
        SenLearn Academy
    </a>
    
    <div class="navbar-nav ms-auto align-items-center">
        <span class="navbar-text text-white me-3">En ligne : <?= $_SESSION['prenom'] ?> (<?= ucfirst($role) ?>)</span>
        
        <a class="nav-link" href="dashboard.php">Espace Privé</a>
        <a class="nav-link fw-bold text-info" href="liste_cours.php">📖 Plateforme d'Études</a>
        <a class="nav-link" href="bibliotheque.php">📖 Bibliothèque</a>
        <a class="nav-link" href="emploi_temps.php">📅 Emploi du Temps</a>
        <a class="nav-link" href="devoirs.php">📝 Devoirs</a>

        <?php if($role == 'professeur' || $role == 'admin'): ?>
            <a class="btn btn-warning btn-sm mx-2 fw-bold text-dark rounded-pill px-3" href="creer_classe.php">
                <i class="fas fa-video me-1"></i> Lancer Live
            </a>
        <?php endif; ?>

        <?php if($role == 'etudiant'): ?>
            <a class="btn btn-success btn-sm mx-2 fw-bold rounded-pill px-3" href="liste_cours_live.php">
                <i class="fas fa-dot-circle me-1 text-blink"></i> Rejoindre Live
            </a>
        <?php endif; ?>

        <?php if($role == 'professeur' || $role == 'admin'): ?>
            <a class="nav-link" href="add_cour.php">➕ Ajouter Cours</a>
            <a class="nav-link" href="add_devoir.php">📚 Donner Devoir</a>
            <a class="nav-link" href="add_note.php">📝 Notes</a>
            <a class="nav-link" href="add_absence.php">❌ Absences</a>
            <a class="nav-link" href="professeurs.php">👨‍🏫 Liste Professeurs</a>
        <?php endif; ?>

        <?php if($role == 'admin'): ?>
            <a class="nav-link" href="add_adhesion.php">⚙️ Adhésions</a>
            <a class="nav-link" href="add_service.php">🌍 Services</a>
            <a class="nav-link" href="add_emploi.php">⚙️Config Panning</a>
            <a class="nav-link" href="liste_etudiants.php">👨‍Grad Liste Étudiants</a>
            <li class="nav-item" style="list-style: none;">
                <a class="nav-link" href="liste_bibliotheque.php">
                    <i class="fas fa-book me-1"></i> Gérer la Bibliothèque
                </a>
            </li>
        <?php endif; ?>

        <a class="btn btn-danger btn-sm ms-2" href="logout.php">Déconnexion</a>
    </div>
</nav>

<style>
@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
}
.text-blink {
    color: #ff3333;
    animation: blink 1.2s infinite;
}
</style>