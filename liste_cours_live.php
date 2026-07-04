<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
include "db.php";

// Récupère les cours lancés les dernières 2 heures
$query = "SELECT * FROM classes_virtuelles WHERE date_creation >= NOW() - INTERVAL 2 HOUR ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cours en Direct - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="fw-bold mb-4">🔴 Cours en direct disponibles</h2>
        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card p-3 shadow-sm border-0 bg-white">
                            <span class="badge bg-danger p-2 mb-2 w-25">En Direct</span>
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['matiere']) ?></h5>
                            <p class="text-muted small mb-3">Enseignant : Prof. <?= htmlspecialchars($row['enseignant']) ?></p>
                            <a href="classe_live.php?salle=<?= $row['nom_salle'] ?>" class="btn btn-primary w-100">
                                Rejoindre le cours
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <p class="lead">Aucun cours en direct pour le moment. Repassez plus tard !</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>