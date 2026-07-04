<?php
if (session_status() == PHP_SESSION_NONE) { 
    session_start(); 
}

// 🔒 SÉCURITÉ : L'utilisateur doit être connecté
if(!isset($_SESSION['user'])){
    header("Location: login.php"); 
    exit();
}

include "db.php";

// 🔧 REPARATION 1 : Récupération de l'ID avec fallback si 'user_id' ou 'id' est utilisé
$etudiant_nom = $_SESSION['prenom'] ?? 'Étudiant'; 
$etudiant_id = $_SESSION['user_id'] ?? ($_SESSION['id'] ?? 0); 

// 🔧 REPARATION 2 : Détection automatique du nom de la colonne dans la table 'notes'
$colonne_etudiant = 'etudiant_id'; // Par défaut

$check_column = mysqli_query($conn, "SHOW COLUMNS FROM notes LIKE 'id_etudiant'");
if ($check_column && mysqli_num_rows($check_column) > 0) {
    $colonne_etudiant = 'id_etudiant';
} else {
    $check_column_alt = mysqli_query($conn, "SHOW COLUMNS FROM notes LIKE 'id_utilisateur'");
    if ($check_column_alt && mysqli_num_rows($check_column_alt) > 0) {
        $colonne_etudiant = 'id_utilisateur';
    }
}

// 📊 Requête SQL dynamique et sécurisée
$query = "SELECT * FROM notes WHERE $colonne_etudiant = '$etudiant_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Variables pour le calcul de la moyenne
$somme_notes = 0;
$somme_coefficients = 0;
$notes_tableau = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notes_tableau[] = $row;
        $coef = $row['coefficient'] ?? 1; // Par défaut coefficient 1 si vide
        $somme_notes += ($row['note'] * $coef);
        $somme_coefficients += $coef;
    }
}

// Calcul de la moyenne générale
$moyenne_generale = $somme_coefficients > 0 ? round($somme_notes / $somme_coefficients, 2) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Bulletin de Notes - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .bulletin-card { border: none; border-radius: 20px; background: white; }
        .table-thead { background-color: #0b2a5b; color: white; }
        
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .bulletin-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <?php include "menu.php"; ?>
    </div>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="dashboard.php" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Retour au Tableau de bord
            </a>
            
            <?php if (!empty($notes_tableau)): ?>
                <button onclick="window.print();" class="btn btn-danger shadow-sm">
                    <i class="fas fa-file-pdf me-2"></i>Télécharger mon Bulletin (PDF)
                </button>
            <?php else: ?>
                <button class="btn btn-danger shadow-sm" disabled>
                    <i class="fas fa-file-pdf me-2"></i>Bulletin indisponible
                </button>
            <?php endif; ?>
        </div>

        <div class="card bulletin-card shadow-sm p-4">
            <div class="card-body">
                
                <div class="row align-items-center border-bottom pb-4 mb-4">
                    <div class="col-md-6">
                        <h2 class="fw-bold text-dark mb-0">SenLearn Academy</h2>
                        <p class="text-muted small mb-0">Plateforme de Suivi Pédagogique</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <h4 class="fw-bold mb-1">BULLETIN DE NOTES</h4>
                        <p class="text-secondary mb-0">Étudiant : <strong><?= htmlspecialchars($etudiant_nom) ?></strong></p>
                        <p class="text-muted small mb-0">Généré le : <?= date('d/m/Y') ?></p>
                    </div>
                </div>

                <?php if (!empty($notes_tableau)): ?>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-thead">
                                <tr>
                                    <th>Matière / Module</th>
                                    <th class="text-center">Coefficient</th>
                                    <th class="text-center">Note / 20</th>
                                    <th class="text-center">Appréciation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notes_tableau as $n): ?>
                                    <tr>
                                        <td class="fw-semibold text-dark"><?= htmlspecialchars($n['matiere'] ?? ($n['nom_cours'] ?? 'Module')) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($n['coefficient'] ?? '1') ?></td>
                                        <td class="text-center fw-bold <?= ($n['note'] >= 10) ? 'text-success' : 'text-danger' ?>">
                                            <?= htmlspecialchars($n['note']) ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php 
                                                if($n['note'] >= 16) echo "Excellent";
                                                elseif($n['note'] >= 14) echo "Très Bien";
                                                elseif($n['note'] >= 12) echo "Bien";
                                                elseif($n['note'] >= 10) echo "Passable";
                                                else echo "Insuffisant";
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border border-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-secondary text-uppercase small">Moyenne Générale :</span>
                                <h3 class="fw-bold mb-0 <?= ($moyenne_generale >= 10) ? 'text-success' : 'text-danger' ?>">
                                    <?= $moyenne_generale ?> / 20
                                </h3>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="alert alert-warning text-center py-5 my-3">
                        <i class="fas fa-folder-open fs-1 d-block mb-3 text-warning"></i>
                        Aucune note n'a encore été enregistrée pour votre compte sur ce semestre.
                    </div>
                <?php endif; ?>

                <div class="border-top pt-4 mt-5 text-center text-muted small">
                    <p class="mb-0">Ce document est un bulletin officiel délivré par SenLearn Academy.</p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>