<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include "db.php";
$erreur = "";

if (isset($_POST['ajouter'])) {
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $matiere = mysqli_real_escape_string($conn, $_POST['matiere']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    
    $photo_destination = "image/default_avatar.png"; 
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $filename = $_FILES['photo']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = "prof_" . time() . "." . $file_ext;
        
        if (!is_dir('image')) {
            mkdir('image', 0777, true);
        }
        if (move_uploaded_file($_FILES['photo']['tmp_name'], "image/" . $new_filename)) {
            $photo_destination = "image/" . $new_filename;
        }
    }

    $verif = mysqli_query($conn, "SELECT id FROM utilisateurs WHERE email='$email'");
    if (mysqli_num_rows($verif) > 0) {
        $erreur = "Cet e-mail existe déjà.";
    } else {
        // Enregistrement explicite avec le rôle 'Professeur'
        $insert = "INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role, matiere, photo) 
                   VALUES ('$prenom', '$nom', '$email', '$password', 'Professeur', '$matiere', '$photo_destination')";
        
        if (mysqli_query($conn, $insert)) {
            header("Location: professeurs.php?status=added");
            exit();
        } else {
            $erreur = "Erreur : " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Professeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; padding: 50px 0; font-family: sans-serif; }
        .form-card { background: white; border-radius: 20px; max-width: 550px; margin: auto; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <a href="professeurs.php" class="text-muted small text-decoration-none d-block mb-3">← Retour à la liste</a>
        <h4 class="fw-bold mb-4">Ajouter un Enseignant</h4>
        
        <?php if(!empty($erreur)): ?>
            <div class="alert alert-danger small"><?= $erreur ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label font-weight-bold">Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Matière</label>
                <input type="text" name="matiere" class="form-control" required placeholder="Ex: Informatique">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Photo (Optionnel)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <button type="submit" name="ajouter" class="btn btn-primary w-100">Enregistrer</button>
        </form>
    </div>
</div>
</body>
</html>