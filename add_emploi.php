<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// 🔒 Vérification de connexion globale
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

$user_role = $_SESSION['role'];
$user_email = $_SESSION['user'];

// 🔍 Logique de filtrage intelligente
if ($user_role === 'admin') {
    // L'admin voit absolument tous les cours planifiés
    $sql = "SELECT * FROM emploi_temps ORDER BY FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), heure ASC";
} else {
    // Récupérer d'abord la classe de l'étudiant ou du prof connecté
    $user_info = $conn->query("SELECT classe FROM utilisateurs WHERE email = '$user_email'")->fetch_assoc();
    $user_classe = $user_info['classe'] ?? 'Aucune';

    // L'étudiant/prof ne voit que les cours dédiés à sa classe
    $sql = "SELECT * FROM emploi_temps WHERE classe = '$user_classe' ORDER BY FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'), heure ASC";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .schedule-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table th { background: #0b2a5b; color: white; border: none; padding: 15px; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        <div class="card schedule-card p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-calendar-alt text-primary me-2"></i> 
                    PLANNING DE LA SEMAINE 
                    <?php if($user_role !== 'admin'): ?>
                        <span class="text-secondary fs-5">(Classe : <?= htmlspecialchars($user_classe) ?>)</span>
                    <?php endif; ?>
                </h4>
                
                <?php if($user_role === 'admin'): ?>
                    <a href="add_emploi.php" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-plus me-2"></i> Ajouter un cours
                    </a>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>JOUR</th>
                            <th>HORAIRE</th>
                            <th>MATIÈRE / COURS</th>
                            <?php if($user_role === 'admin'): ?>
                                <th>CLASSE</th>
                                <th class="text-center">ACTION</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><i class="far fa-clock text-primary me-2"></i><?= htmlspecialchars($row['jour']) ?></td>
                                    <td class="text-secondary fw-semibold"><?= htmlspecialchars($row['heure']) ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['matiere']) ?></div>
                            </td>
                            <?php if($user_role === 'admin'): ?>
                                        <td>
                                            <span class="badge bg-info text-dark fw-bold"><?= htmlspecialchars($row['classe']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="delete_emploi.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous supprimer ce cours du planning ?');">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $user_role === 'admin' ? 5 : 3 ?>" class="text-center py-5 text-muted">
                                    <div class="fs-1 text-light-dark mb-2">📅</div>
                                    Aucun cours n'a été planifié pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>