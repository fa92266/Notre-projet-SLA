<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "db.php"; // Assure-toi que ton fichier de connexion BD est bon

$erreur = "";

if (isset($_POST['connexion'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password']; 

    // Requête pour chercher l'utilisateur
    $query = "SELECT * FROM utilisateurs WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Vérification du mot de passe (en texte brut OU haché)
        if ($password === $user['mot_de_passe'] || password_verify($password, $user['mot_de_passe'])) {
            
            // On enregistre les infos importantes en session
            $_SESSION['user'] = $user['email'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role']; // Étudiant ou Admin
            
            // ✅ ACTION DE RÉPARATION : Enregistrement de l'identifiant unique (ID) en session
            $_SESSION['user_id'] = $user['id']; // Clé principale utilisée dans notes.php
            $_SESSION['id'] = $user['id'];      // Clé alternative de secours

            // ✅ Redirection directe et obligatoire vers le dashboard
            header("Location: dashboard.php");
            exit(); 
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Aucun compte trouvé avec cette adresse email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SenLearn Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --text-main: #1e293b;      
            --text-muted: #64748b;     
            --input-border: #cbd5e1;   
            --focus-color: #0f172a;   
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('image/accueil.jpg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(15, 23, 42, 0.6); 
            z-index: 1;
        }

        .login-card-center {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.96); 
            backdrop-filter: blur(8px); 
            width: 100%;
            max-width: 440px;
            padding: 45px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-logo-center {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 700;
            font-size: 22px;
            color: var(--text-main);
            margin-bottom: 25px;
            text-decoration: none;
        }

        .login-card-center h3 {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .custom-input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-input-box .form-control {
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 15px;
            color: var(--text-main);
            transition: all 0.15s ease;
            background-color: #ffffff;
        }

        .custom-input-box .form-control:focus {
            border-color: var(--focus-color);
            box-shadow: 0 0 0 1px var(--focus-color);
        }

        .eye-toggle-icon {
            position: absolute;
            right: 16px;
            cursor: pointer;
            color: #94a3b8;
            font-size: 14px;
            z-index: 10;
        }

        .eye-toggle-icon:hover {
            color: var(--text-main);
        }

        .btn-submit-dark {
            background-color: #000000;
            color: #ffffff;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            border: none;
            font-size: 15px;
            width: 100%;
            transition: opacity 0.15s;
        }

        .btn-submit-dark:hover {
            opacity: 0.9;
            cursor: pointer;
        }

        .footer-copyright {
            position: absolute;
            bottom: 20px;
            z-index: 2;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            text-align: center;
            width: 100%;
            left: 0;
            pointer-events: none;
        }

        @media (max-width: 480px) {
            .login-card-center {
                margin: 20px;
                padding: 35px 25px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-card-center">
    <a href="#" class="brand-logo-center">
        <img src="image/logo.png.png" alt="Logo SenLearn Academy" style="max-width: 110px; height: auto; margin-bottom: 10px;">
        <span>SenLearn Academy</span>
    </a>
    

    <div class="mb-4 text-center">
        <h3>Se connecter</h3>
        <p style="color: var(--text-muted); font-size: 14.5px; margin-bottom: 0;">Espace Numérique Ouvert</p>
    </div>

    <?php if(!empty($erreur)): ?>
        <div class="alert alert-danger border-0 small py-2.5 mb-4" style="background: #fff1f2; color: #be123c; border-radius: 10px; font-weight: 500;">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $erreur ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" id="singleCardForm">
        <div class="mb-3">
            <label class="form-label">Adresse e-mail</label>
            <div class="custom-input-box">
                <input type="email" name="email" class="form-control" placeholder="nom@exemple.com" required autocomplete="username">
            </div>
        </div>
        
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Mot de passe</label>
                <a href="forgot_password.php" style="font-size: 13px; color: #000000; text-decoration: none; font-weight: 500;">Oublié ?</a>
            </div>
            <div class="custom-input-box">
                <input type="password" name="password" id="passwordField" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                <i class="fas fa-eye eye-toggle-icon" id="togglePasswordBtn"></i>
            </div>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="rememberMeCheck">
            <label class="form-check-label text-muted" style="font-size: 13.5px;" for="rememberMeCheck">
                Rester connecté
            </label>
        </div>
        
        <button type="submit" name="connexion" class="btn-submit-dark" id="loginBtn">
            Connexion
        </button>
    </form>

    <div class="text-center mt-4" style="font-size: 14px; color: var(--text-muted);">
        Nouveau étudiant ? <a href="register.php" style="color: #000000; text-decoration: none; font-weight: 600;">Créer un compte</a>
    </div>
</div>

<div class="footer-copyright">
    © 2026 SenLearn Academy — Tous droits réservés.
</div>

<script>
    const togglePasswordBtn = document.querySelector('#togglePasswordBtn');
    const passwordField = document.querySelector('#passwordField');

    togglePasswordBtn.addEventListener('click', function () {
        const isPassword = passwordField.getAttribute('type') === 'password';
        passwordField.setAttribute('type', isPassword ? 'text' : 'password');
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('singleCardForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Authentification...';
        btn.style.pointerEvents = 'none';
        btn.style.opacity = '0.8';
    });
</script>
</body>
</html>