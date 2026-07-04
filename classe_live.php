<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user']) || !isset($_GET['salle'])){ 
    header("Location: login.php"); exit(); 
}

$nom_salle = $_GET['salle'];
$pseudo = $_SESSION['prenom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classe Virtuelle Live - SenLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { margin: 0; padding: 0; height: 100%; background-color: #1a1a1a; color: white; }
        #meet-container { width: 100%; height: 85vh; background: #000; }
    </style>
    <script src="https://meet.jit.si/external_api.js"></script>
</head>
<body>

    <div class="container-fluid py-3 bg-dark d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-white">🎓 Classe Virtuelle en Direct</h4>
        <a href="dashboard.php" class="btn btn-danger btn-sm">Quitter le cours</a>
    </div>

    <div id="meet-container"></div>

    <script>
        const domain = "meet.jit.si";
        const options = {
            roomName: "<?= $nom_salle ?>",
            width: "100%",
            height: "100%",
            parentNode: document.querySelector('#meet-container'),
            userInfo: {
                displayName: "<?= $pseudo ?>"
            },
            interfaceConfigOverwrite: {
                DEFAULT_BACKGROUND: '#1a1a1a',
                SHOW_JITSI_WATERMARK: false
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script>

</body>
</html>