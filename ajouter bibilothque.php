<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : Seul l'administrateur peut ajouter des livres
$role = $_SESSION['role'] ?? 'etudiant';
if (!isset($_SESSION['user']) || $role !== 'admin') {
    header("Location: dashboard.php"); 
    exit();
}

include "db.php";

$message = "";

// 💾 Traitement du formulaire quand l'admin valide
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = mysqli_real_escape_string($conn, $_POST['titre']);
    $auteur = mysqli_real_escape_string($conn, $_POST['auteur']);
    $categorie = mysqli_real_escape_string($conn, $_POST['categorie']);
    $lien_document = mysqli_real_escape_string($conn, $_POST['lien_document']);

    if (!empty($titre) && !empty($auteur) && !empty($lien_document)) {
        // Insertion dans la table bibliotheque
        $sql = "INSERT INTO bibliotheque (titre, auteur, categorie, lien_document) 
                VALUES ('$titre', '$auteur', '$categorie', '$lien_document')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>✅ Ressource ajoutée à la bibliothèque avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Erreur lors de l'ajout : " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠️ Veuillez remplir tous les champs obligatoires.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter à la Bibliothèque - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .form-card { border: none; border-radius: 15px; background: white; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold text-dark mb-0">📚 Ajouter un Document / Livre</h3>
                    <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Retour</a>
                </div>

                <?= $message ?>

                <div class="card form-card shadow-sm p-4">
                    <div class="card-body">
                        <form action="ajouter_bibliotheque.php" method="POST">
                            
                            <div class="mb-3">
                                <label for="titre" class="form-label fw-bold">Titre de l'ouvrage</label>
                                <input type="text" class="form-control" id="titre" name="titre" placeholder="Ex: Introduction à PHP & MySQL" required>
                            </div>

                            <div class="mb-3">
                                <label for="auteur" class="form-label fw-bold">Auteur</label>
                                <input type="text" class="form-control" id="auteur" name="auteur" placeholder="Ex: Jean Dupont" required>
                            </div>

                            <div class="mb-3">
                                <label for="categorie" class="form-label fw-bold">Catégorie / Filière</label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="Informatique">Informatique / Développement</option>
                                    <option value="Management">Management & Gestion</option>
                                    <option value="Mathématiques">Mathématiques / Algèbre</option>
                                    <option value="Langues">Langues & Communication</option>
                                    <option value="Autre">Autre ressource</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="lien_document" class="form-label fw-bold">Lien URL du document (PDF / Drive)</label>
                                <input type="url" class="form-control" id="lien_document" name="lien_document" placeholder="https://drive.google.com/... ou un lien PDF" required>
                                <div class="form-text text-muted">Collez ici le lien de partage de votre fichier de cours ou livre.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 mt-3">
                                <i class="fas fa-plus me-2"></i>Ajouter à la Bibliothèque
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>