<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : Seul un admin ou un professeur peut voir toutes les notes
$role = $_SESSION['role'] ?? 'etudiant';
if (!isset($_SESSION['user']) || ($role !== 'admin' && $role !== 'professeur' && $role !== 'prof')) {
    header("Location: dashboard.php"); 
    exit();
}

include "db.php";

$message = "";

// 🗑️ Action de suppression d'une note (si l'admin clique sur supprimer)
if (isset($_GET['supprimer'])) {
    $id_note = intval($_GET['supprimer']);
    $delete_query = "DELETE FROM notes WHERE id = $id_note";
    if (mysqli_query($conn, $delete_query)) {
        $message = "<div class='alert alert-success'>✅ Note supprimée avec succès.</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Erreur de suppression.</div>";
    }
}

// 📊 Requête SQL sans coefficient
$query = "SELECT notes.*, utilisateurs.prenom, utilisateurs.nom 
          FROM notes 
          INNER JOIN utilisateurs ON notes.etudiant_id = utilisateurs.id 
          ORDER BY notes.id DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Notes - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .table-card { border: none; border-radius: 15px; background: white; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">📋 Gestion & Liste des Notes</h3>
                <p class="text-muted mb-0">Historique complet des notes saisies sur la plateforme.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="ajouter_note.php" class="btn btn-success shadow-sm"><i class="fas fa-plus me-2"></i>Saisir une note</a>
                <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Retour</a>
            </div>
        </div>

        <?= $message ?>

        <div class="card table-card shadow-sm p-4">
            <div class="card-body">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Matière / Module</th>
                                    <th class="text-center">Note / 20</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-semibold text-dark">
                                            <i class="fas fa-user text-secondary me-2"></i>
                                            <?= htmlspecialchars($row['prenom']) ?> <?= htmlspecialchars($row['nom']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['matiere']) ?></td>
                                        <td class="text-center fw-bold <?= ($row['note'] >= 10) ? 'text-success' : 'text-danger' ?>">
                                            <?= htmlspecialchars($row['note']) ?> / 20
                                            </td>
                                        <td class="text-center">
                                            <!-- Bouton Supprimer -->
                                            <a href="liste_notes.php?supprimer=<?= $row['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?');">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center py-4 mb-0">
                        <i class="fas fa-info-circle fs-3 d-block mb-2"></i>
                        Aucune note n'a encore été saisie dans le système.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>