<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
include "db.php";
$message = "";

// Traitement du rendu de devoir par l'étudiant
if (isset($_POST['rendre_devoir'])) {
    $devoir_id = $_POST['devoir_id'];
    $etudiant = $_SESSION['prenom'];

    if (!empty($_FILES['travail']['name'])) {
        $fichier_nom = time() . '_rendu_' . $_FILES['travail']['name'];
        if(move_uploaded_file($_FILES['travail']['tmp_name'], 'uploads/' . $fichier_nom)) {
            
            $sql = "UPDATE devoirs SET fichier_rendu='$fichier_nom', etudiant_rendu='$etudiant', statut='Rendu' WHERE id='$devoir_id'";
            if ($conn->query($sql)) {
                $message = "<div class='alert alert-success border-0 shadow-sm'>✔️ Votre devoir a bien été envoyé au professeur !</div>";
            }
        }
    }
}

$query = "SELECT * FROM devoirs ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Devoirs - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: #0b2a5b; color: white; }
        .devoir-card { background: white; border-radius: 15px; border: none; transition: 0.3s; }
        .devoir-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom py-3 shadow mb-5">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1 text-white">📝 Mes Devoirs & Évaluations</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <?= $message ?>
        <div class="row g-4">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6">
                        <div class="card devoir-card p-4 shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary px-3 py-2 rounded-pill"><?= htmlspecialchars($row['matiere']) ?></span>
                                <span class="badge <?= $row['statut'] === 'Rendu' ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $row['statut'] ?></span>
                            </div>
                            <h5 class="fw-bold text-dark mt-2"><?= htmlspecialchars($row['titre']) ?></h5>
                            <p class="text-muted small mb-3"><?= htmlspecialchars($row['description']) ?></p>
                            
                            <div class="p-2 bg-light rounded mb-3 small text-secondary">
                                <div><i class="far fa-user me-2"></i>Donné par : <strong><?= $row['enseignant'] ?></strong></div>
                                <div><i class="far fa-calendar-alt me-2"></i>À rendre avant le : <strong class="text-danger"><?= $row['date_limite'] ?></strong></div>
                            </div>

                            <?php if(!empty($row['fichier_sujet'])): ?>
                                <a href="uploads/<?= $row['fichier_sujet'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm mb-3 w-100">
                                    <i class="fas fa-download me-2"></i> Télécharger le sujet du prof
                                </a>
                            <?php endif; ?>

                            <?php if ($row['statut'] !== 'Rendu'): ?>
                                <form method="POST" enctype="multipart/form-data"
                                class="border-top pt-3">
                                    <input type="hidden" name="devoir_id" value="<?= $row['id'] ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="travail" class="form-control" required>
                                        <button type="submit" name="rendre_devoir" class="btn btn-success"><i class="fas fa-upload me-1"></i> Rendre</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="text-center text-success small fw-bold pt-2 border-top">
                                    <i class="fas fa-check-circle me-1"></i> Vous avez déjà envoyé votre travail (<?= htmlspecialchars($row['fichier_rendu']) ?>).
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                    <p class="lead">Aucun devoir programmé pour le moment !</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>