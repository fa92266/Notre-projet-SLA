<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
include "db.php";

// Récupérer tous les cours publiés
$query = "SELECT * FROM cours ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plateforme de Formation - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .cours-card { background: white; border-radius: 15px; border: none; transition: 0.3s; }
        .cours-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-4">
        <div class="mb-4">
            <h2 class="fw-bold text-dark">📖 Plateforme de Formation</h2>
            <p class="text-muted">Sélectionnez un cours pour accéder à votre salle de classe virtuelle et aux ressources.</p>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card cours-card p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge bg-secondary mb-2"><i class="fas fa-user-tie me-1"></i> Prof. <?= htmlspecialchars($row['enseignant']) ?></span>
                                <h5 class="fw-bold text-dark mb-3"><?= htmlspecialchars($row['titre']) ?></h5>
                                <p class="text-muted small text-truncate"><?= htmlspecialchars($row['description']) ?></p>
                            </div>
                            
                            <a href="salle_cours.php?id=<?= $row['id'] ?>" class="btn btn-primary w-100 mt-3 fw-bold">
                                <i class="fas fa-door-open me-2"></i> Entrer dans la salle
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-book-reader fa-3x mb-3 text-secondary"></i>
                    <p class="lead">Aucun cours n'est disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>