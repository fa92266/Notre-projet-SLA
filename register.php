<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";
$message = "";

if (isset($_POST['inscription'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 
    
    // On force la récupération en minuscules pour correspondre à l'ENUM ('admin', 'professeur', 'etudiant')
    $role = strtolower($_POST['role']); 

    $check_email = $conn->query("SELECT id FROM utilisateurs WHERE email='$email'");
    if ($check_email->num_rows > 0) {
        $message = "<div class='alert alert-danger'>Cet email est déjà utilisé !</div>";
    } else {
        // Enregistrement direct (en texte clair ou haché selon ton choix précédent, ici adapté à ton login actuel)
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES ('$nom', '$prenom', '$email', '$password', '$role')";
        if ($conn->query($sql)) {
            $message = "<div class='alert alert-success'>Compte créé avec succès ! <a href='login.php' class='fw-bold text-dark'>Se connecter</a></div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur MySQL : " . $conn->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: white; }
        .card-register { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; width: 100%; max-width: 500px; padding: 30px; }
        .form-control, .form-select { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; }
        .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.2); color: white; box-shadow: 0 0 8px #ffc107; border-color: #ffc107; }
        .form-select option { color: black; }
    </style>
</head>
<body>
<div class="card-register shadow">
    <h3 class="text-center mb-4">🎓 Créer un compte</h3>
    <?= $message ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
            </div>
            <div class="col-md-6 mb-3">
                <input type="text" name="nom" class="form-control" placeholder="Nom" required>
            </div>
        </div>
        <input type="email" name="email" class="form-control mb-3" placeholder="Adresse Email" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Mot de passe" required>
        
        <label class="form-label text-white-50 small">Type de compte :</label>
        <select name="role" class="form-select mb-4" required>
            <option value="etudiant">Étudiant</option>
            <option value="professeur">Professeur / Enseignant</option>
            <option value="admin">Administrateur</option>
        </select>
        
        <button type="submit" name="inscription" class="btn btn-warning w-100 fw-bold text-dark">S'INSCRIRE</button>
        <a href="login.php" class="text-center d-block mt-3 text-white-50 small text-decoration-none">Déjà inscrit ? <strong class="text-warning">Se connecter</strong></a>
    </form>
</div>
</body>
</html>