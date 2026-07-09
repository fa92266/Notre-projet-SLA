<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// 🔒 SÉCURITÉ : Seul l'admin a le droit d'accéder à cette page
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); 
    exit();
}

include "db.php";

// 🔍 Gestion de la recherche et du filtre par classe
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filtre_classe = isset($_GET['classe']) ? mysqli_real_escape_string($conn, $_GET['classe']) : '';

// Construction de la requête SQL (Uniquement les étudiants)
$query = "SELECT id, nom, prenom, email, classe FROM utilisateurs WHERE role = 'etudiant'";

if (!empty($search)) {
    $query .= " AND (nom LIKE '%$search%' OR prenom LIKE '%$search%' OR email LIKE '%$search%')";
}

if (!empty($filtre_classe)) {
    $query .= " AND classe = '$filtre_classe'";
}

$query .= " ORDER BY classe ASC, nom ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .student-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table th { background: #0b2a5b; color: white; border: none; padding: 15px; }
        .btn-custom { background: #0b2a5b; color: white; }
        .btn-custom:hover { background: #ffc107; color: black; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        <div class="card student-card p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-user-graduate text-primary me-2"></i> 
                    Liste des Étudiants inscrits (<?= $result->num_rows ?>)
                </h4>
                <a href="add_adhesion.php" class="btn btn-success fw-semibold shadow-sm">
                    <i class="fas fa-plus me-2"></i>Inscrire un nouvel élève
                </a>
            </div>

            <form method="GET" action="" class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, prénom..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="classe" class="form-select">
                        <option value="">Tous les niveaux (L1, L2, L3)</option>
                        <option value="L1" <?= $filtre_classe == 'L1' ? 'selected' : '' ?>>Licence 1 (L1)</option>
                        <option value="L2" <?= $filtre_classe == 'L2' ? 'selected' : '' ?>>Licence 2 (L2)</option>
                        <option value="L3" <?= $filtre_classe == 'L3' ? 'selected' : '' ?>>Licence 3 (L3)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-custom w-100 fw-bold"><i class="fas fa-filter me-2"></i>Filtrer / Rechercher</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Identité</th>
                            <th>Adresse Email</th>
                            <th>Classe / Niveau</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['prenom']) ?></div>
                                        <div class="text-muted small text-uppercase"><?= htmlspecialchars($row['nom']) ?></div>
                                    </td>
                                    <td class="small"><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold">
                                            <i class="fas fa-graduation-cap me-1"></i><?= htmlspecialchars($row['classe'] ?? 'Non définie') ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="add_adhesion.php?supprimer=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Voulez-vous vraiment désinscrire cet étudiant ?');">
                                            <i class="fas fa-user-minus"></i> Retirer
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i> Aucun étudiant trouvé.
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
