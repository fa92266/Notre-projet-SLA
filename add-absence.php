<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || ($_SESSION['role'] !== 'professeur' && $_SESSION['role'] !== 'admin')){
    header("Location: login.php");
    exit();
}

include "db.php";
$message = "";

if (isset($_POST['ajouter_absence'])) {
    $etudiant_id = mysqli_real_escape_string($conn, $_POST['etudiant_id']);
    $date_absence = mysqli_real_escape_string($conn, $_POST['date_absence']);
    $justifie = mysqli_real_escape_string($conn, $_POST['justifie']);

    $sql = "INSERT INTO absences (etudiant_id, date_absence, justifie) VALUES ('$etudiant_id', '$date_absence', '$justifie')";
    if ($conn->query($sql)) {
        $message = "<div class='alert alert-warning border-0 shadow-sm'><i class='fas fa-user-times me-2'></i>Absence signalée avec succès.</div>";
    } else {
        $message = "<div class='alert alert-danger border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i>Erreur : " . $conn->error . "</div>";
    }
}

// Récupération des étudiants
$etudiants = $conn->query("SELECT id, prenom, nom FROM utilisateurs WHERE role='etudiant'");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler une Absence - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: #0b2a5b; color: white; }
        .form-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-submit { background: #dc3545; color: white; font-weight: 600; border-radius: 10px; transition: 0.3s; }
        .btn-submit:hover { background: #ffc107; color: black; }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-3 shadow mb-5">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1 text-white"><i class="fas fa-user-clock me-2"></i> Session Enseignant : Registre d'Absences</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card form-card p-5">
                    <h3 class="text-dark fw-bold mb-4">🔔 Noter une absence</h3>
                    <?= $message ?>
                    
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Sélectionner l'Étudiant absent</label>
                            <select name="etudiant_id" class="form-select form-select-lg fs-6" required>
                                <option value="">-- Choisissez un élève --</option>
                                <?php while($etu = $etudiants->fetch_assoc()): ?>
                                    <option value="<?= $etu['id'] ?>"><?= htmlspecialchars($etu['prenom']) . " " . htmlspecialchars($etu['nom']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Date du cours manqué</label>
                            <input type="date" name="date_absence" class="form-control form-control-lg fs-6" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Statut de l'absence</label>
                            <select name="justifie" class="form-select form-select-lg fs-6" required>
                                <option value="Non justifiée">❌ Non justifiée</option>
                                <option value="Justifiée">📁 Justifiée (Certificat médical / Motif valable)</option>
                            </select>
                        </div>
                        <button type="submit" name="ajouter_absence" class="btn btn-submit w-100 py-3 mt-2">
                            <i class="fas fa-bell me-2"></i> Enregistrer l'absence
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
