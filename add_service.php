<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// 🔒 SÉCURITÉ : Seul l'administrateur peut configurer les filières et services
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); exit();
}

include "db.php";
$message = "";

// ➕ ACTION 1 : AJOUTER UN NOUVEAU SERVICE OU FILIÈRE
if (isset($_POST['ajouter_service'])) {
    $nom_service = mysqli_real_escape_string($conn, $_POST['nom_service']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO services (nom_service, description) VALUES ('$nom_service', '$description')";
    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success border-0 shadow-sm'><i class='fas fa-check-circle me-2'></i>Le service / filière a été enregistré avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i>Erreur lors de l'enregistrement : " . $conn->error . "</div>";
    }
}

// ❌ ACTION 2 : SUPPRIMER UN SERVICE
if (isset($_GET['supprimer'])) {
    $id_delete = intval($_GET['supprimer']);
    if ($conn->query("DELETE FROM services WHERE id=$id_delete")) {
        $message = "<div class='alert alert-warning border-0 shadow-sm'><i class='fas fa-trash-alt me-2'></i>Le service a été retiré de la plateforme.</div>";
    }
}

// 📋 RÉCUPÉRATION DES SERVICES POUR LA LISTE DYNAMIQUE
$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services & Filières - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .form-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .service-item { background: white; border-radius: 12px; border-left: 5px solid #0b2a5b; transition: 0.3s; }
        .service-item:hover { transform: translateX(5px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .btn-custom { background: #0b2a5b; color: white; font-weight: 600; }
        .btn-custom:hover { background: #ffc107; color: black; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container pb-5">
        <div class="mb-4">
            <h2 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i> Infrastructure : Services & Filières</h2>
            <p class="text-muted">Créez et organisez les départements pédagogiques et administratifs de l'établissement.</p>
        </div>

        <?= $message ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card form-card p-4">
                    <h4 class="fw-bold text-dark mb-4">✨ Ajouter une formation</h4>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nom du service ou de la filière</label>
                            <input type="text" name="nom_service" class="form-control form-control-lg fs-6" placeholder="Ex: Secrétariat Bureautique" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Description détaillée ou objectifs</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Décrivez le programme de la filière ou le rôle de ce département administratif..." required></textarea>
                        </div>
                        <button type="submit" name="ajouter_service" class="btn btn-custom w-100 py-2">
                            <i class="fas fa-plus me-1"></i> Enregistrer la filière
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card form-card p-4">
                    <h4 class="fw-bold text-dark mb-4">⚙️ Départements actifs (<?= $services->num_rows ?>)</h4>
                    
                    <div class="row g-3">
                        <?php if($services->num_rows > 0): ?>
                            <?php while($serv = $services->fetch_assoc()): ?>
                                <div class="col-12 p-3 service-item shadow-sm d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1"><i class="fas fa-graduation-cap text-secondary me-2"></i><?= htmlspecialchars($serv['nom_service']) ?></h6>
                                        <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($serv['description'])) ?></p>
                                    </div>
                                    <a href="add_service.php?supprimer=<?= $serv['id'] ?>" class="btn btn-sm btn-outline-danger ms-3" onclick="return confirm('Supprimer définitivement cette filière ? Les étudiants associés perdront ce lien.');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                <p class="lead mb-0">Aucun service ni filière n'est configuré pour le moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>