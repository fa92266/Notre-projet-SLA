<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ DE BASE : L'utilisateur doit être connecté
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

$role = $_SESSION['role'] ?? 'etudiant';
$prenom = $_SESSION['prenom'] ?? 'Utilisateur';

// 📊 Statistiques (uniquement pour l'admin)
$total_etudiants = 0; $total_profs = 0; $total_cours = 0; $total_notes = 0; $total_absences = 0;

if ($role === 'admin') {
    $total_etudiants = $conn->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role='etudiant'")->fetch_assoc()['total'];
    $total_profs     = $conn->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role='professeur'")->fetch_assoc()['total'];
    $total_cours     = $conn->query("SELECT COUNT(*) as total FROM cours")->fetch_assoc()['total'] ?? 0;
    $total_notes     = $conn->query("SELECT COUNT(*) as total FROM notes")->fetch_assoc()['total'] ?? 0;
    
    // Protection si la table absences n'existe pas encore
    $check_absences  = $conn->query("SHOW TABLES LIKE 'absences'");
    if($check_absences && $check_absences->num_rows > 0) {
        $total_absences = $conn->query("SELECT COUNT(*) as total FROM absences")->fetch_assoc()['total'] ?? 0;
    } else {
        $total_absences = 0; 
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .welcome-banner { background: linear-gradient(135deg, #0b2a5b 0%, #1d4ed8 100%); color: white; border-radius: 20px; }
        .welcome-banner-prof { background: linear-gradient(135deg, #047857 0%, #10b981 100%); color: white; border-radius: 20px; }
        .welcome-banner-student { background: linear-gradient(135deg, #0f172a 0%, #334155 100%); color: white; border-radius: 20px; }
        .stat-card { background: white; border: none; border-radius: 15px; }
        .quick-link-btn { background: white; border: 2px solid #e5e7eb; border-radius: 15px; padding: 20px; transition: all 0.2s ease; text-align: left; height: 100%; }
        .quick-link-btn:hover { border-color: #0b2a5b; background: #f8fafc; }
        .chart-container { background: white; border-radius: 15px; border: none; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        
        <?php if ($role === 'admin'): ?>

            <div class="p-5 mb-5 welcome-banner shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="fw-bold mb-2">Bienvenue, <?= htmlspecialchars($prenom) ?> ! 👑</h1>
                    <p class="mb-0 opacity-75">Aperçu global de la plateforme SenLearn Academy.</p>
                </div>
                <div><span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">Administrateur</span></div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4"><div class="card stat-card p-4 shadow-sm">👥 Étudiants : <strong><?= $total_etudiants ?></strong></div></div>
                <div class="col-md-4"><div class="card stat-card p-4 shadow-sm">👨‍🏫 Professeurs : <strong><?= $total_profs ?></strong></div></div>
                <div class="col-md-4"><div class="card stat-card p-4 shadow-sm">📅 Cours Actifs : <strong><?= $total_cours ?></strong></div></div>
            </div>

            <h5 class="fw-bold text-secondary mb-4 text-uppercase">Actions Administrateur</h5>
            <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-4 mb-5">
                <div class="col"><a href="add_adhesion.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-user-plus me-2 text-primary"></i>Inscrire un membre</h6></a></div>
                <div class="col"><a href="liste_etudiants.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-users me-2 text-info"></i>Gestion des Étudiants</h6></a></div>
                <div class="col"><a href="liste_notes.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-list-ol me-2 text-danger"></i>Gestion des Notes</h6></a></div>
                <div class="col"><a href="add_emploi.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-calendar-alt opacity-75 me-2 text-warning"></i>Configurer le Planning</h6></a></div>
                
                <div class="col"><a href="ajouter_cours.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle me-2 text-dark"></i>Ajouter Cours</h6></a></div>
                <div class="col"><a href="liste_cours.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-book-open me-2 text-secondary"></i>Liste des Cours</h6></a></div>
                
                <div class="col"><a href="ajouter_bibliotheque.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Ajouter Livre</h6></a></div>
                <div class="col"><a href="liste_bibliotheque.php" class="text-decoration-none d-block quick-link-btn shadow-sm"><h6 class="fw-bold text-dark mb-0"><i class="fas fa-book me-2 text-primary"></i>Liste Bibliothèque</h6></a></div>
            </div>
<div class="col">
    <a href="list_absence.php" class="text-decoration-none d-block quick-link-btn shadow-sm">
        <h6 class="fw-bold text-dark mb-0">
            <i class="fas fa-clipboard-list me-2 text-warning"></i>Registre des Absences
        </h6>
    </a>
</div>
            <h5 class="fw-bold text-secondary mb-4 text-uppercase">Analyse de l'Établissement</h5>
            <div class="row mb-5">
                <div class="col-md-8 mx-auto">
                    <div class="card chart-container shadow-sm p-4">
                        <canvas id="chartStats" style="max-height: 380px;"></canvas>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const ctx = document.getElementById('chartStats').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Étudiants 👥', 'Professeurs 👨‍🏫', 'Notes Saisies 📝', 'Absences ❌'],
                            datasets: [{
                                label: 'Effectif total',
                                data: [<?= $total_etudiants ?>, <?= $total_profs ?>, <?= $total_notes ?>, <?= $total_absences ?>],
                                backgroundColor: [
                                    'rgba(13, 110, 253, 0.8)', 
                                    'rgba(25, 135, 84, 0.8)',  
                                    'rgba(255, 193, 7, 0.8)',  
                                    'rgba(220, 53, 69, 0.8)'   
                                ],
                                borderColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                                borderWidth: 2,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                });
            </script>

        <?php elseif ($role === 'professeur' || $role === 'prof'): ?>

            <div class="p-5 mb-5 welcome-banner-prof shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="fw-bold mb-2">Bienvenue, Cher Enseignant <?= htmlspecialchars($prenom) ?> ! 👨‍🏫</h1>
                    <p class="mb-0 opacity-75">Espace de gestion de vos cours et des évaluations.</p>
                </div>
                <div><span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">Espace Enseignant</span></div>
            </div>

            <h5 class="fw-bold text-secondary mb-4 text-uppercase">Mes Outils Pédagogiques</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="ajouter_note.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-success"><i class="fas fa-marker"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Saisir les Notes</h5>
                                <p class="text-muted small mb-0">Entrer les notes des examens et devoirs pour vos classes.</p>
                            </div>
                        </div>
                    </a>
                </div>
<div class="col-md-3">
    <a href="list_absence.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
        <div class="d-flex align-items-center gap-3">
            <div class="fs-2 text-warning"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <h5 class="fw-bold text-dark mb-1">Registre Absences</h5>
                <p class="text-muted small mb-0">Consulter et gérer l'historique global des absences.</p>
            </div>
        </div>
    </a>
</div>
                <div class="col-md-4">
                    <a href="liste_notes.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-danger"><i class="fas fa-list-ol"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Liste des Notes</h5>
                                <p class="text-muted small mb-0">Voir, vérifier et supprimer les notes déjà enregistrées.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="voir_emploi.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-warning"><i class="fas fa-book"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Mes Classes & Emploi du temps</h5>
                                <p class="text-muted small mb-0">Consulter les horaires de vos prochains cours magistraux.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        <?php else: ?>

            <div class="p-5 mb-5 welcome-banner-student shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="fw-bold mb-2">Bienvenue, <?= htmlspecialchars($prenom) ?> ! 👋</h1>
                    <p class="mb-0 opacity-75">Accède facilement à ton suivi pédagogique et à tes salles de classe.</p>
                </div>
                <div><span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">Espace Étudiant</span></div>
            </div>

            <h5 class="fw-bold text-secondary mb-4 text-uppercase">Mon Espace d'Apprentissage</h5>
            <div class="row g-4">
                <div class="col-md-3">
                    <a href="notes.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-info"><i class="fas fa-file-invoice"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Mes Notes & Bulletins</h5>
                                <p class="text-muted small mb-0">Consulter mes notes de modules.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="voir_emploi.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-success"><i class="fas fa-video"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Suivre mes Cours</h5>
                                <p class="text-muted small mb-0">Voir le planning, la salle et rejoindre le cours en direct.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="voir_cours.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-primary"><i class="fas fa-book-open"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Supports de Cours</h5>
                                <p class="text-muted small mb-0">Télécharger les fichiers PDF, Word et documents.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="consulter_bibliotheque.php" class="text-decoration-none d-block quick-link-btn shadow-sm py-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-2 text-secondary"><i class="fas fa-bookmark"></i></div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Bibliothèque</h5>
                                <p class="text-muted small mb-0">Parcourir et lire les ressources de l'établissement.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</body>
</html>