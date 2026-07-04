<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || !isset($_GET['id'])){ header("Location: login.php"); exit(); }

include "db.php";
$cours_id = intval($_GET['id']);

// 1. Récupérer les détails de ce cours spécifique
$cours_query = $conn->query("SELECT * FROM cours WHERE id = $cours_id");
$cours = $cours_query->fetch_assoc();

if (!$cours) {
    die("Cours introuvable.");
}

// 2. Traitement de l'envoi d'un message dans le chat
if (isset($_POST['envoyer_msg'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $auteur = $_SESSION['prenom'];
    $role = $_SESSION['role'];

    if (!empty($message)) {
        $conn->query("INSERT INTO messages_cours (cours_id, auteur, role, message) VALUES ($cours_id, '$auteur', '$role', '$message')");
        // Rafraîchir pour voir le message
        header("Location: salle_cours.php?id=" . $cours_id);
        exit();
    }
}

// 3. Récupérer les messages du chat pour ce cours
$messages = $conn->query("SELECT * FROM messages_cours WHERE cours_id = $cours_id ORDER BY date_envoi ASC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Salle de Cours : <?= htmlspecialchars($cours['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .room-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .chat-box { height: 350px; overflow-y: scroll; background: #f8f9fa; border-radius: 15px; padding: 15px; }
        .msg-prof { background: #fff3e0; border-left: 4px solid #ff9800; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; }
        .msg-etu { background: #e3f2fd; border-left: 4px solid #2196f3; padding: 8px 12px; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <?php include "menu.php"; ?>

    <div class="container pb-5">
        <div class="mb-4">
            <a href="liste_cours.php" class="btn btn-sm btn-outline-secondary rounded-pill mb-3"><i class="fas fa-arrow-left me-1"></i> Quitter la salle</a>
            <h2 class="fw-bold text-dark">🚪 Salle de classe : <?= htmlspecialchars($cours['titre']) ?></h2>
            <span class="text-secondary">Enseignant responsable : <strong>Prof. <?= htmlspecialchars($cours['enseignant']) ?></strong></span>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card room-card p-4 h-100">
                    <h4 class="fw-bold text-primary mb-3"><i class="fas fa-file-alt me-2"></i> Support de Cours & Directives</h4>
                    <p class="text-dark bg-light p-3 rounded-3 border" style="white-space: pre-line;">
                        <?= htmlspecialchars($cours['description']) ?>
                    </p>

                    <?php if(!empty($cours['fichier'])): ?>
                        <div class="mt-4 p-3 border border-success rounded-3 bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <i class="far fa-file-pdf fa-2x text-danger me-2"></i>
                                <span class="fw-semibold">Document officiel du cours</span>
                            </div>
                            <a href="uploads/<?= $cours['fichier'] ?>" target="_blank" class="btn btn-success font-weight-bold">
                                <i class="fas fa-download me-1"></i> Télécharger le cours
                            </a>
                        </div>
                    <?php else: ?>
<div class="alert alert-warning mt-4 small"><i class="fas fa-info-circle me-1"></i> Aucun document lourd n'est joint à cette leçon.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card room-card p-4">
                    <h4 class="fw-bold text-dark mb-3"><i class="fas fa-comments text-info me-2"></i> Fil de Discussion</h4>
                    
                    <div class="chat-box mb-3" id="chatBox">
                        <?php if($messages->num_rows > 0): ?>
                            <?php while($msg = $messages->fetch_assoc()): ?>
                                <div class="<?= $msg['role'] === 'professeur' || $msg['role'] === 'admin' ? 'msg-prof' : 'msg-etu' ?>">
                                    <div class="d-flex justify-content-between font-weight-bold small text-secondary mb-1">
                                        <span><strong><?= htmlspecialchars($msg['auteur']) ?></strong> (<?= ucfirst($msg['role']) ?>)</span>
                                        <span style="font-size: 10px;"><?= date('H:i', strtotime($msg['date_envoi'])) ?></span>
                                    </div>
                                    <div class="text-dark"><?= htmlspecialchars($msg['message']) ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-5 small">Posez la première question au professeur ici !</p>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Écrire un message à la classe..." required autocomplete="off">
                            <button type="submit" name="envoyer_msg" class="btn btn-info text-white"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var chatBox = document.getElementById("chatBox");
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</body>
</html>