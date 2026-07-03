<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
include "db.php";

// Récupération des fichiers de la base de données
$query = "SELECT * FROM bibliotheque ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary-dark: #0f2027; --accent-gold: #ffc107; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .header-section { background: linear-gradient(135deg, var(--primary-dark), #2c5364); color: white; padding: 40px 0; border-bottom-left-radius: 50px; border-bottom-right-radius: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .doc-card { background: rgba(255, 255, 255, 0.9); border: none; border-radius: 20px; transition: all 0.3s ease; border-left: 5px solid #dc3545; }
        .doc-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .doc-icon { font-size: 40px; color: #dc3545; background: rgba(220, 53, 69, 0.1); padding: 15px; border-radius: 15px; }
        .btn-download { background: var(--primary-dark); color: white; border-radius: 10px; font-weight: 600; text-decoration: none; display: block; text-align: center; padding: 10px; transition: 0.3s; }
        .btn-download:hover { background: var(--accent-gold); color: black; }
    </style>
</head>
<body>

<div class="header-section text-center mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold">📚 Bibliothèque Numérique</h1>
        <p class="lead opacity-75">Retrouvez tous vos supports de cours officiels</p>
        <a href="dashboard.php" class="btn btn-outline-light rounded-pill mt-3 px-4">
            <i class="fas fa-home me-2"></i> Tableau de bord
        </a>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
<div class="card doc-card p-4 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-file-pdf doc-icon me-3"></i>
                            <div style="flex: 1; min-width: 0;">
                                <h5 class="mb-0 fw-bold text-truncate" title="<?= htmlspecialchars($row['titre']) ?>"><?= htmlspecialchars($row['titre']) ?></h5>
                                <small class="text-muted">Document PDF</small>
                            </div>
                        </div>
                        <p class="text-muted small">Support mis à disposition par l'administration ou votre enseignant.</p>
                        <a href="uploads/<?= htmlspecialchars($row['fichier']) ?>" target="_blank" class="btn btn-download mt-2">
                            <i class="fas fa-eye me-2"></i> Ouvrir / Télécharger
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-light p-5 shadow-sm rounded-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="lead text-muted mb-0">Aucun document disponible pour le moment.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>