<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérification des droits d'accès
if(!isset($_SESSION['user']) || ($_SESSION['role'] !== 'professeur' && $_SESSION['role'] !== 'admin')){
    header("Location: login.php");
    exit();
}

include "db.php";
$message = "";

// Gestion de la suppression d'une absence (Optionnel mais très pratique)
if (isset($_GET['supprimer'])) {
    $id_absence = mysqli_real_escape_string($conn, $_GET['supprimer']);
    $sql_delete = "DELETE FROM absences WHERE id = '$id_absence'";
    
    if ($conn->query($sql_delete)) {
        $message = "<div class='alert alert-success border-0 shadow-sm'><i class='fas fa-check-circle me-2'></i>Absence retirée avec succès.</div>";
    } else {
        $message = "<div class='alert alert-danger border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i>Erreur de suppression.</div>";
    }
}

// Récupération de l'historique des absences avec une jointure SQL pour avoir les noms des étudiants
$query = "SELECT a.id, a.date_absence, a.justifie, u.prenom, u.nom 
          FROM absences a 
          INNER JOIN utilisateurs u ON a.etudiant_id = u.id 
          ORDER BY a.date_absence DESC";

$liste_absences = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre des Absences - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: #0b2a5b; color: white; }
        .table-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .thead-custom { background: #f8f9fa; color: #495057; }
        .badge-justifie { background-color: #d1e7dd; color: #0f5132; font-weight: 600; }
        .badge-non-justifie { background-color: #f8d7da; color: #842029; font-weight: 600; }
        .btn-action { border-radius: 8px; transition: 0.3s; }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-3 shadow mb-5">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1 text-white">
                <i class="fas fa-clipboard-list me-2"></i> Session Enseignant : Registre des Absences
            </span>
            <div>
                <a href="add_absence.php" class="btn btn-warning btn-sm me-2 fw-semibold">
                    <i class="fas fa-plus me-1"></i> Signaler une absence
                </a>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card table-card p-4 p-md-5">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark fw-bold m-0">📅 Historique global des absences</h3>
                        <span class="badge bg-secondary py-2 px-3 rounded-pill fs-6">
                            <?= $liste_absences->num_rows ?> enregistrement(s)
                        </span>
                    </div>

                    <?= $message ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-custom border-bottom">
                                <tr>
                                    <th class="py-3">Étudiant</th>
                                    <th class="py-3">Date de l'absence</th>
                                    <th class="py-3">Statut</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($liste_absences->num_rows > 0): ?>
                                    <?php while($row = $liste_absences->fetch_assoc()): ?>
                                        <tr>
                                            <td class="fw-semibold text-dark py-3">
                                                <i class="fas fa-user text-secondary me-2"></i>
                                                <?= htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) ?>
                                            </td>
                                            <td class="text-secondary py-3">
                                                <i class="far fa-calendar-alt me-2"></i>
                                                <?= date('d/m/Y', strtotime($row['date_absence'])) ?>
                                            </td>
                                            <td class="py-3">
                                                <?php if($row['justifie'] === 'Justifiée'): ?>
                                                    <span class="badge badge-justifie px-3 py-2 rounded-pill">
                                                        <i class="fas fa-check me-1"></i> Justifiée
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-non-justifie px-3 py-2 rounded-pill">
                                                        <i class="fas fa-times me-1"></i> Non justifiée
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 text-center">
                                                <a href="liste_absence.php?supprimer=<?= $row['id'] ?>" 
                                                   class="btn btn-outline-danger btn-sm btn-action"
                                                   onclick="return confirm('Voulez-vous vraiment annuler cette absence ?');"
                                                   title="Annuler l'absence">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-secondary">
                                            <i class="fas fa-user-check fa-3x mb-3 text-muted"></i>
                                            <p class="mb-0 fs-5">Aucune absence enregistrée pour le moment.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>