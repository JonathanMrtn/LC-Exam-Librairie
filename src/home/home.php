<?php
require('../../config.php');


// Récupérer le nombre total de livres
$queryTotalBooks = "SELECT COUNT(*) as total_books FROM livres";
$stmtTotalBooks = $pdo->prepare($queryTotalBooks);
$stmtTotalBooks->execute();
$resultTotalBooks = $stmtTotalBooks->fetch(PDO::FETCH_ASSOC);


// Récupérer le nombre d'utilisateurs enregistrés
$queryTotalUsers = "SELECT COUNT(*) as total_users FROM utilisateurs";
$stmtTotalUsers = $pdo->prepare($queryTotalUsers);
$stmtTotalUsers->execute();
$resultTotalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC);
require_once('../template/header.php');
?>

<div class="wrapper">
        <!-- Page Content -->
        <div id="content">
            <div class="container">
                
                <!-- Votre contenu principal va ici -->
                <div id="content">
                <h1>Dashboard</h1>
                <div class="container">
        
                <div class="statistic">
                    <h3>Total des Livres</h3>
                    <p><?php echo $resultTotalBooks['total_books']; ?></p>
                </div>

                <div class="statistic">
                    <h3>Utilisateurs Enregistrés</h3>
                    <p><?php echo $resultTotalUsers['total_users']; ?></p>
                </div>
                <!-- ... Autres statistiques ... -->
            </div>
        </div>
