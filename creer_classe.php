<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || ($_SESSION['role'] !== 'professeur' && $_SESSION['role'] !== 'admin')){
    header("Location: login.php"); exit();
}
include "db.php";
$message = "";

if (isset($_POST['lancer_cours'])) {
    $matiere = mysqli_real_escape_string($conn, $_POST['matiere']);
    $enseignant = $_SESSION['prenom'];
    // On crée un nom de salle unique pour éviter que les cours se mélangent
    $nom_salle = "SenLearn_" . uniqid();

    $sql = "INSERT INTO classes_virtuelles (matiere, enseignant, nom_salle) VALUES ('$matiere', '$enseignant', '$nom_salle')";
    if ($conn->query($sql)) {
        // Redirection directe vers la classe virtuelle
        header("Location: classe_live.php?salle=" . $nom_salle);
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Erreur : " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Démarrer un Cours Live - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4 border-0 rounded-4">
                    <h3 class="fw-bold text-center mb-4">🎥 Lancer un cours en direct</h3>
                    <?= $message ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom du cours / Matière</label>
                            <input type="text" name="matiere" class="form-control form-control-lg" placeholder="Ex: Informatique Bureautique" required>
                        </div>
                        <button type="submit" name="lancer_cours" class="btn btn-danger w-100 py-2 fw-bold">
                            <i class="fas fa-video me-2"></i> Ouvrir la classe virtuelle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>