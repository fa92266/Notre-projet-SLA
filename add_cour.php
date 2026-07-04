<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Seuls les profs et admins peuvent publier des fichiers de cours
if(!isset($_SESSION['user']) || ($_SESSION['role'] !== 'professeur' && $_SESSION['role'] !== 'admin')){
    header("Location: login.php");
    exit();
}

include "db.php";
$message = "";

if (isset($_POST['ajouter_cours'])) {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $enseignant = $_SESSION['prenom'];
    $nom_fichier = "";

    // 📁 GESTION DU TÉLÉCHARGEMENT DU FICHIER (PDF, DOCX, PPTX)
    if (isset($_FILES['fichier_cours']) && $_FILES['fichier_cours']['error'] == 0) {
        $extension_autorisees = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg'];
        $infos_fichier = pathinfo($_FILES['fichier_cours']['name']);
        $extension_upload = strtolower($infos_fichier['extension']);

        if (in_array($extension_upload, $extension_autorisees)) {
            // On crée un nom unique avec le timestamp pour éviter d'écraser un autre fichier portant le même nom
            $nom_fichier = time() . '_' . basename($_FILES['fichier_cours']['name']);
            
            // On déplace le fichier vers le dossier "uploads"
            if (!move_uploaded_file($_FILES['fichier_cours']['tmp_name'], 'uploads/' . $nom_fichier)) {
                $message = "<div class='alert alert-danger border-0 shadow-sm'>Erreur lors du déplacement du fichier vers le serveur.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger border-0 shadow-sm'>Format de fichier non autorisé (Seuls PDF, Word, PowerPoint et images sont acceptés).</div>";
        }
    }

    // Si aucune erreur de fichier n'est survenue, on insère dans la base
    if (empty($message)) {
        $sql = "INSERT INTO cours (titre, description, enseignant, fichier) VALUES ('$titre', '$description', '$enseignant', '$nom_fichier')";
        if ($conn->query($sql)) {
            $message = "<div class='alert alert-success border-0 shadow-sm'><i class='fas fa-check-circle me-2'></i>Le cours et son document ont été publiés avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger border-0 shadow-sm'><i class='fas fa-exclamation-triangle me-2'></i>Erreur MySQL : " . $conn->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background: #0b2a5b; color: white; }
        .form-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-submit { background: #0b2a5b; color: white; font-weight: 600; border-radius: 10px; transition: 0.3s; }
        .btn-submit:hover { background: #ffc107; color: black; }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom py-3 shadow mb-5">
        <div class="container d-flex justify-content-between">
            <span class="navbar-brand mb-0 h1 text-white"><i class="fas fa-book-open me-2"></i> Espace Enseignant : Publier un Support</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card form-card p-5">
                    <h3 class="text-dark fw-bold mb-4">📚 Publier un nouveau cours</h3>
                    <?= $message ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Titre du cours</label>
                            <input type="text" name="titre" class="form-control form-control-lg fs-6" placeholder="Ex: Gestion du Courrier Arrivant / Départ" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Introduction ou Consignes du professeur</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Lisez attentivement le document joint avant le prochain cours live..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark"><i class="fas fa-paperclip me-1 text-primary"></i> Joindre le document de cours (PDF, Word, PPT)</label>
                            <input type="file" name="fichier_cours" class="form-control" required>
                        </div>
                        <button type="submit" name="ajouter_cours" class="btn btn-submit w-100 py-3 mt-2">
                            <i class="fas fa-upload me-2"></i> Mettre le cours en ligne
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>