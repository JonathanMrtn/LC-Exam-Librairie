<?php
require('../../config.php');

// Assurez-vous que l'ID du livre que vous souhaitez supprimer est passé en tant que paramètre (par exemple, dans l'URL).
if (!isset($_GET['emprunt_id'])) {
    header('Location: emprunt.php'); // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
    exit();
}

$emprunt_id = $_GET['emprunt_id'];

// Vérifiez si l'utilisateur est authentifié et a le rôle approprié (par exemple, "admin" ou "gestionnaire") pour accéder à cette fonctionnalité.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Supprimez le livre de la base de données en utilisant l'ID du livre
$deleteQuery = "DELETE FROM emprunts WHERE id = :emprunt_id";
$deleteStmt = $pdo->prepare($deleteQuery);
$deleteStmt->execute(array(':emprunt_id' => $emprunt_id));

// Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
header('Location: emprunt.php');
exit();
?>
