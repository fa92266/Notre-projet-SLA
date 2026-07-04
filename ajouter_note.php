<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : Seul un admin ou un professeur peut ajouter des notes
$role = $_SESSION['role'] ?? 'etudiant';
if (!isset($_SESSION['user']) || ($role !== 'admin' && $role !== 'professeur' && $role !== 'prof')) {
    header("Location: dashboard.php"); 
    exit();
}

include "db.php";

$message = "";

// 💾 Traitement du formulaire quand le prof clique sur "Enregistrer"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $etudiant_id = mysqli_real_escape_string($conn, $_POST['etudiant_id']);
    $matiere = mysqli_real_escape_string($conn, $_POST['matiere']);
    $note = floatval($_POST['note']);

    if (!empty($etudiant_id) && !empty($matiere) && $note >= 0 && $note <= 20) {
        // Requête SANS la colonne coefficient
        $sql = "INSERT INTO notes (etudiant_id, matiere, note) VALUES ('$etudiant_id', '$matiere', '$note')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>✅ Note enregistrée avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Erreur lors de l'enregistrement : " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠️ Veuillez remplir correctement tous les champs (Note entre 0 et 20).</div>";
    }
}

// 👥 On récupère la liste des étudiants pour le menu déroulant
$query_etudiants = "SELECT id, prenom, nom FROM utilisateurs WHERE role = 'etudiant' ORDER BY prenom ASC";
$result_etudiants = mysqli_query($conn, $query_etudiants);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisir les Notes - SenLearn Academy</title>
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
                    <h3 class="fw-bold text-dark mb-0">👨‍🏫 Saisie des Notes</h3>
                    <a href="dashboard.php" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-2"></i>Retour</a>
                </div>

                <?= $message ?>

                <div class="card form-card shadow-sm p-4">
                    <div class="card-body">
                        <form action="ajouter_note.php" method="POST">
                            
                            <div class="mb-3">
                                <label for="etudiant_id" class="form-label fw-bold">Sélectionner l'Étudiant</label>
                                <select class="form-select" id="etudiant_id" name="etudiant_id" required>
                                    <option value="">-- Choisir un étudiant --</option>
                                    <?php if ($result_etudiants && mysqli_num_rows($result_etudiants) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($result_etudiants)): ?>
                                            <option value="<?= $row['id'] ?>">
                                                <?= htmlspecialchars($row['prenom']) ?> <?= htmlspecialchars($row['nom']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="coefficient" class="form-label fw-bold">Coefficient</label>
                                    <input type="number" class="form-control" id="coefficient" name="coefficient" value="1" min="1" max="10" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="note" class="form-label fw-bold">Note sur 20</label>
                                    <input type="number" class="form-control" id="note" name="note" step="0.1" min="0" max="20" placeholder="0.00" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3 mt-3">
                                <i class="fas fa-save me-2"></i>Enregistrer la Note
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>