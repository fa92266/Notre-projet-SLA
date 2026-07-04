<?php
session_start();

// 1. VÉRIFICATION DE LA SESSION
// Si l'utilisateur est déjà connecté, on l'envoie directement sur son tableau de bord
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
// Sinon, le code ci-dessous (la page d'accueil) s'affiche naturellement
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SenLearn Academy - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
            /* 🖼️ L'image de l'UVS remplace le dégradé et prend tout l'écran */
            background-image: url('image/accueil.jpg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        /* 🟦 Filtre sombre sur l'image pour que les textes blancs restent très lisibles */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(15, 23, 42, 0.65); 
            z-index: 1;
        }

        /* 🧭 BARRE DE NAVIGATION */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: #fff !important;
            letter-spacing: -0.5px;
        }

        /* 🏠 ZONE CENTRALE (HERO SECTION) */
        .hero-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            position: relative;
            z-index: 2;
        }
        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 50px 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 700px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .hero-card h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .hero-card p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 35px;
            color: #f1f5f9;
            line-height: 1.6;
        }

        /* 🎛️ STYLISATION DES BOUTONS */
        .btn-custom {
            padding: 14px 35px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        
        /* Bouton Connexion (Blanc Épuré) */
        .btn-login {
            background-color: white;
            color: #0f172a;
            border: 1px solid white;
        }
        .btn-login:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
        }
/* Bouton Inscription (Flou Moderne / Pas d'orange agressif) */
        .btn-register {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .btn-register:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom navbar-dark py-3">
        <div class="container">
           <a class="navbar-brand" href="#">
                <img src="image/logo.png.png" alt="Logo" style="height: 35px; width: auto; margin-right: 10px;">
                SenLearn Academy
            </a>
            <span class="navbar-text text-white-50 d-none d-md-inline" style="font-size: 14px;">
                Université Virtuelle - Portail Scolaire
            </span>
        </div>
    </nav>

    <div class="hero-container">
        <div class="hero-card">
            <h1>Bienvenue sur SenLearn Academy</h1>
            <p>
                Votre plateforme moderne d’apprentissage en ligne et de gestion académique. 
                Accédez à vos cours, consultez vos emplois du temps et suivez vos performances en quelques clics.
            </p>
            
            <div class="d-flex flex-column flex-sm-row justify-content-center">
                <a href="login.php" class="btn-custom btn-login">
                    <span>🔑 Se connecter</span>
                </a>
                <a href="register.php" class="btn-custom btn-register">
                    <span>📝 Créer un compte</span>
                </a>
            </div>
        </div>
    </div>

</body>
</html>