<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Suppression physique de la photo sur le serveur si elle existe
    $select_photo = mysqli_query($conn, "SELECT photo FROM utilisateurs WHERE id=$id");
    if ($row = mysqli_fetch_assoc($select_photo)) {
        if (!empty($row['photo']) && $row['photo'] != "image/default_avatar.png" && file_exists($row['photo'])) {
            unlink($row['photo']);
        }
    }

    $delete = "DELETE FROM utilisateurs WHERE id=$id";
    if (mysqli_query($conn, $delete)) {
        header("Location: professeurs.php?status=deleted");
        exit();
    } else {
        header("Location: professeurs.php?status=error");
        exit();
    }
} else {
    header("Location: professeurs.php");
    exit();
}
?>