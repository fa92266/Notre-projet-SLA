<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 Sécurité : Seul un utilisateur connecté peut voir son planning
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

// ✅ REQUÊTE CORRIGÉE : On trie simplement par Jour, ou par ID pour éviter l'erreur de colonne manquante
$query = "SELECT * FROM emploi_temps ORDER BY id DESC"; 
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Emploi du Temps - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📅 Mon Emploi du Temps</h2>
            <a href="dashboard.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour au Dashboard</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Jour</th>
                                    <th>Matière</th>
                                    <th>Enseignant</th>
                                    <th>Salle / Lien</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-bold text-primary"><?= htmlspecialchars($row['jour'] ?? 'Non défini') ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['matiere'] ?? ($row['nom_cours'] ?? 'Cours')) ?></td>
                                        <td><?= htmlspecialchars($row['professeur'] ?? ($row['enseignant'] ?? 'À définir')) ?></td>
                                        <td>
                                            <span class="text-muted"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['salle'] ?? 'En ligne') ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0 text-center">
                        <i class="fas fa-calendar-times fs-3 d-block mb-2"></i>
                        Aucun cours n'est planifié dans votre emploi du temps pour le moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>