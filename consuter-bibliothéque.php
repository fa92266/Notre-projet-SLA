<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : L'utilisateur doit être connecté
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

// Récupération du filtre de catégorie si l'étudiant clique sur un filtre
$categorie_filtre = isset($_GET['categorie']) ? mysqli_real_escape_string($conn, $_GET['categorie']) : '';

// Requête SQL dynamique selon le filtre
if (!empty($categorie_filtre)) {
    $query = "SELECT * FROM bibliotheque WHERE categorie = '$categorie_filtre' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM bibliotheque ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque Numérique - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .book-card { border: none; border-radius: 15px; background: white; transition: transform 0.2s, box-shadow 0.2s; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .icon-box { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
        .hover-link:hover {
    color: #0b2a5b !important; /* Change la couleur au survol */
    text-decoration: underline !important; /* Souligne le titre */
}
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">📚 Bibliothèque Numérique</h3>
                <p class="text-muted mb-0">Accédez aux manuels, guides et supports de cours partagés par l'administration.</p>
            </div>
            <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Tableau de bord</a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="consulter_bibliotheque.php" class="btn btn-sm <?= empty($categorie_filtre) ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">Tout voir</a>
            <a href="consulter_bibliotheque.php?categorie=Informatique" class="btn btn-sm <?= $categorie_filtre === 'Informatique' ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill px-3">💻 Informatique</a>
            <a href="consulter_bibliotheque.php?categorie=Management" class="btn btn-sm <?= $categorie_filtre === 'Management' ? 'btn-success' : 'btn-outline-success' ?> rounded-pill px-3">📈 Management</a>
            <a href="consulter_bibliotheque.php?categorie=Mathématiques" class="btn btn-sm <?= $categorie_filtre === 'Mathématiques' ? 'btn-warning text-dark' : 'btn-outline-warning' ?> rounded-pill px-3">📐 Mathématiques</a>
            <a href="consulter_bibliotheque.php?categorie=Langues" class="btn btn-sm <?= $categorie_filtre === 'Langues' ? 'btn-info text-white' : 'btn-outline-info' ?> rounded-pill px-3">🗣️ Langues</a>
        </div>

        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card book-card shadow-sm h-100 p-3">
                            <div class="card-body d-flex flex-column justify-content-between">
                                
                                <div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="icon-box bg-light text-primary fs-4">
                                            <i class="fas <?= $row['categorie'] === 'Informatique' ? 'fa-code' : 'fa-book' ?>"></i>
                                        </div>
                                        <div>
                                            <div>
    <span class="badge bg-secondary mb-1"><?= htmlspecialchars($row['categorie']) ?></span>
    <h5 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">
        <a href="<?= htmlspecialchars($row['lien_document']) ?>" target="_blank" class="text-decoration-none text-dark hover-link">
            <?= htmlspecialchars($row['titre']) ?>
        </a>
    </h5>
</div>
                                    
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-user-pen me-2"></i>Auteur : <strong><?= htmlspecialchars($row['auteur']) ?></strong>
                                    </p>
                                </div>

                                <div class="mt-3">
                                    <a href="<?= htmlspecialchars($row['lien_document']) ?>" target="_blank" class="btn btn-outline-primary w-100 rounded-3 py-2 fw-bold">
                                        <i class="fas fa-external-link-alt me-2"></i>Ouvrir / Télécharger
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-5 shadow-sm rounded-3">
                        <i class="fas fa-book-open fs-1 d-block mb-3 text-secondary"></i>
                        Aucun ouvrage disponible dans cette catégorie pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
