<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// On inclut la connexion à la base de données
include "db.php";

$message = "";
$type_message = "";

// Gestion des petits messages de succès
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'deleted') {
        $message = "Le professeur a été supprimé.";
        $type_message = "success";
    } elseif ($_GET['status'] == 'added') {
        $message = "Le professeur a été ajouté.";
        $type_message = "success";
    }
}

// 🔍 REQUÊTE SIMPLIFIÉE : On récupère TOUS les professeurs, peu importe les majuscules/minuscules
$query = "SELECT * FROM utilisateurs WHERE LOWER(role)='professeur' OR role='Professeur' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Professeurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; font-family: 'Segoe UI', sans-serif; padding-top: 30px; }
        .main-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 40px; }
        .table img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
        .btn-add { background-color: #0b2a5b; color: white; font-weight: 600; border-radius: 10px; padding: 10px 20px; text-decoration: none; }
        .btn-add:hover { background-color: #163f7a; color: white; }
        .badge-matiere { background-color: #eff6ff; color: #2563eb; font-weight: 600; padding: 5px 12px; border-radius: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark"><i class="fas fa-chalkboard-teacher me-2"></i> Liste des Professeurs</h2>
            <a href="ajouter_professeur.php" class="btn-add">
                <i class="fas fa-user-plus me-2"></i> Ajouter un professeur
            </a>
        </div>

        <?php if(!empty($message)): ?>
            <div class="alert alert-<?= $type_message ?> text-center"><?= $message ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Nom & Prénom</th>
                        <th>Matière</th>
                        <th>Email</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <?php $photo = !empty($row['photo']) ? $row['photo'] : 'image/default_avatar.png'; ?>
                                    <img src="<?= $photo ?>" alt="Avatar">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></td>
                                <td><span class="badge-matiere"><?= !empty($row['matiere']) ? htmlspecialchars($row['matiere']) : 'Non définie' ?></span></td>
                                <td class="text-muted"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="text-end">
                                    <a href="supprimer_professeur.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-3" onclick="return confirm('Supprimer ce professeur ?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                Aucun professeur trouvé dans la base de données.
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