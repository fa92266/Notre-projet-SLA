<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🔒 SÉCURITÉ : L'utilisateur doit être connecté pour accéder à la bibliothèque
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include "db.php";

// Avant d'afficher, on vérifie si la table existe (au cas où)
// On récupère tous les livres de la bibliothèque
$query = "SELECT * FROM bibliotheque ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque Numérique - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .main-title { color: #0b2a5b; font-weight: 800; border-left: 8px solid #ffc107; padding-left: 15px; }
        .book-card { background: white; border-radius: 15px; border: none; transition: 0.3s; height: 100%; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .book-icon { font-size: 3rem; color: #0b2a5b; }
    </style>
</head>
<body>

    <!-- Inclusion de ton menu global mis à jour -->
    <?php include "menu.php"; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="main-title mb-1">📖 BIBLIOTHÈQUE NUMÉRIQUE</h2>
                <p class="text-muted mb-0">Téléchargez vos manuels scolaires, guides pratiques et ressources de référence.</p>
            </div>
            <a href="dashboard.php" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card book-card p-4 shadow-sm d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <i class="fas fa-book-reader book-icon"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($row['titre']) ?></h5>
                                    <span class="badge bg-light text-secondary border px-2 py-1 small">
                                        <i class="fas fa-layer-group me-1"></i> <?= htmlspecialchars($row['categorie'] ?? 'Général') ?>
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($row['description'] ?? 'Aucune description disponible pour cet ouvrage.') ?>
                            </p>

                            <div>
                                <?php if(!empty($row['fichier'])): ?>
                                    <!-- Bouton de téléchargement direct rangé dans le dossier uploads -->
                                    <a href="uploads/<?= $row['fichier'] ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 fw-bold py-2 rounded-3">
                                        <i class="fas fa-download me-1"></i> Lire / Télécharger
                                    </a>
<?php else: ?>
                                    <button class="btn btn-light btn-sm w-100 disabled text-muted"><i class="fas fa-link-slash"></i> Aucun fichier joint</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Message si la bibliothèque est vide -->
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-book-open fa-4x mb-3 text-secondary" style="opacity: 0.4;"></i>
                    <p class="lead mb-0">La bibliothèque est vide pour le moment.</p>
                    <?php if($_SESSION['role'] == 'professeur' || $_SESSION['role'] == 'admin'): ?>
                        <small>Utilisez l'option d'ajout de ressources pour enrichir la bibliothèque.</small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>