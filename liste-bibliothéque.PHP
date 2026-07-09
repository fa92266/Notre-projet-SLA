<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : Seul l'administrateur a le droit de gérer cette liste
$role = $_SESSION['role'] ?? 'etudiant';
if (!isset($_SESSION['user']) || $role !== 'admin') {
    header("Location: dashboard.php"); 
    exit();
}

include "db.php";

$message = "";

// 🗑️ Traitement de la suppression si l'admin clique sur le bouton "Supprimer"
if (isset($_GET['supprimer'])) {
    $id_livre = intval($_GET['supprimer']);
    
    $sql_delete = "DELETE FROM bibliotheque WHERE id = $id_livre";
    if (mysqli_query($conn, $sql_delete)) {
        $message = "<div class='alert alert-success'>🗑️ L'ouvrage a bien été supprimé de la bibliothèque.</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Erreur lors de la suppression : " . mysqli_error($conn) . "</div>";
    }
}

// 📊 Récupération de tous les livres enregistrés
$query = "SELECT * FROM bibliotheque ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Bibliothèque - SenLearn Academy</title>
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
                <h3 class="fw-bold text-dark mb-1">⚙️ Gestion de la Bibliothèque</h3>
                <p class="text-muted mb-0">Visualisez, contrôlez et supprimez les ressources numériques de la plateforme.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="ajouter_bibliotheque.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Ajouter un livre</a>
                <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Retour</a>
            </div>
        </div>

        <?= $message ?>

        <div class="card table-card shadow-sm p-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Titre</th>
                                <th scope="col">Auteur</th>
                                <th scope="col">Catégorie</th>
                                <th scope="col">Lien du Document</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($row['titre']) ?></td>
                                        <td><i class="fas fa-user text-muted me-2"></i><?= htmlspecialchars($row['auteur']) ?></td>
                                        <td><span class="badge bg-info text-white"><?= htmlspecialchars($row['categorie']) ?></span></td>
                                        <td>
                                            <a href="<?= htmlspecialchars($row['lien_document']) ?>" target="_blank" class="text-decoration-none text-primary small">
                                                <i class="fas fa-external-link-alt me-1"></i> Ouvrir le lien
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="liste_bibliotheque.php?supprimer=<?= $row['id'] ?>" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');" 
                                               class="btn btn-sm btn-outline-danger rounded-3 px-3">
                                                <i class="fas fa-trash-alt me-1"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-book-open fs-2 d-block mb-2 opacity-50"></i>
                                        Aucun livre n'est enregistré pour le moment.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
