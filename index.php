<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: src/auth/login.php');
    exit();
}

include("config.php");
include("src/template/header.php");

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'login') {
    echo "Avant inclusion du login.php"; // Débogage
    include("src/auth/login.php");
    echo "Après inclusion du login.php"; // Débogage
} elseif ($page === 'books') {
    include("src/book/books.php");
} else {
    header('Location: src/home/home.php');
}

include("src/template/footer.php");
?>
