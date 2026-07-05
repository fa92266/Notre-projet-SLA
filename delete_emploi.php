<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// 🔒 SÉCURITÉ : Seul l'admin a le droit de supprimer un créneau
if(!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); 
    exit();
}

include "db.php";

if (isset($_GET['id'])) {
    $id_delete = intval($_GET['id']);
    $conn->query("DELETE FROM empleo_temps WHERE id = $id_delete");
}

// Redirection instantanée vers l'emploi du temps après la suppression
header("Location: emploi_temps.php");
exit();
?>