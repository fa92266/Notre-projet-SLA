<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 Sécurité : Seul un utilisateur connecté peut voir les cours
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

// On récupère les cours de la base de données
// Note : Si ta table s'appelle autrement (ex: 'documents', 'supports'), change le nom ici
$query = "SELECT * FROM cours ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .card-module { border: none; border-radius: 15px; transition: transform 0.2s; }
        .card-module:hover { transform: translateY(-3px); }
        .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    </style>
</head>
<body class="bg-light">

    <?php include "menu.php"; ?>
<div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">📚 Mes Modules & Supports de Cours</h2>
                <p class="text-muted mb-0">Téléchargez vos ressources pédagogiques et fiches de révision.</p>
            </div>
            <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Retour</a>
        </div>

        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card card-module shadow-sm p-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-box bg-primary-subtle text-primary">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($row['intitule'] ?? ($row['nom_cours'] ?? 'Module')) ?></h5>
                                        <span class="text-muted small">Par : <?= htmlspecialchars($row['enseignant'] ?? ($row['prof'] ?? 'Professeur')) ?></span>
                                    </div>
                                </div>
                                
                                <p class="text-muted small mb-3">
                                    <?= htmlspecialchars($row['description'] ?? 'Aucune description disponible pour ce cours.') ?>
                                </p>

                                <?php if(!empty($row['fichier'])): ?>
                                    <a href="uploads/<?= $row['fichier'] ?>" target="_blank" class="btn btn-primary w-100 rounded-3">
                                        <i class="fas fa-file-download me-2"></i>Ouvrir le support (PDF)
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-outline-secondary w-100 rounded-3" disabled>
                                        <i class="fas fa-exclamation-circle me-2"></i>Aucun fichier joint
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-folder-open fs-2 d-block mb-2"></i>
                        Aucun document ou cours n'a été publié pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>