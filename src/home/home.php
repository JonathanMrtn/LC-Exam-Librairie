<?php
require('../../config.php');


// Récupérer le nombre total de livres
$queryTotalBooks = "SELECT COUNT(*) as total_books FROM livres WHERE statut = 'disponible'";
$stmtTotalBooks = $pdo->prepare($queryTotalBooks);
$stmtTotalBooks->execute();
$resultTotalBooks = $stmtTotalBooks->fetch(PDO::FETCH_ASSOC);


// Récupérer le nombre d'utilisateurs enregistrés
$queryTotalUsers = "SELECT COUNT(*) as total_users FROM utilisateurs";
$stmtTotalUsers = $pdo->prepare($queryTotalUsers);
$stmtTotalUsers->execute();
$resultTotalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre d'emprunt par utilisateur enregistrés
$queryTotalEmprunts = "SELECT COUNT(*) as total_emprunts FROM emprunts WHERE id_utilisateur = :id_utilisateur";
$stmtTotalEmprunts = $pdo->prepare($queryTotalEmprunts);
$stmtTotalEmprunts->execute(array(':id_utilisateur' => $_SESSION['user_id']));
$resultTotalEmprunts = $stmtTotalEmprunts->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur est admin
if ($_SESSION['role'] == 'admin') {
    // Récupérer le nombre total d'emprunts
    $queryTotalEmpruntsAdmin = "SELECT COUNT(*) as total_emprunts_admin FROM emprunts";
    $stmtTotalEmpruntsAdmin = $pdo->prepare($queryTotalEmpruntsAdmin);
    $stmtTotalEmpruntsAdmin->execute();
    $resultTotalEmpruntsAdmin = $stmtTotalEmpruntsAdmin->fetch(PDO::FETCH_ASSOC);

    // Récupérer le nombre total de livres
    $queryTotalBooksAdmin = "SELECT COUNT(*) as total_books_admin FROM livres";
    $stmtTotalBooksAdmin = $pdo->prepare($queryTotalBooksAdmin);
    $stmtTotalBooksAdmin->execute();
    $resultTotalBooksAdmin = $stmtTotalBooksAdmin->fetch(PDO::FETCH_ASSOC);
}

// il faut rajouter une requête permettant de connaître le nombre d'emprunt de l'utilisateur connecté qui sont dépassé de plus de 30 jours
// Récupérer le nombre d'emprunt dépassé de plus de 30 jours
$queryTotalEmpruntsDepasse = "SELECT COUNT(*) as total_emprunts_depasse FROM emprunts WHERE id_utilisateur = :id_utilisateur AND date_emprunt < DATE_SUB(NOW(), INTERVAL 30 DAY)";
$stmtTotalEmpruntsDepasse = $pdo->prepare($queryTotalEmpruntsDepasse);
$stmtTotalEmpruntsDepasse->execute(array(':id_utilisateur' => $_SESSION['user_id']));
$resultTotalEmpruntsDepasse = $stmtTotalEmpruntsDepasse->fetch(PDO::FETCH_ASSOC);

$titre = "Dashboard";
require_once('../template/header.php');
?>
        <div class="wrapper">
            <!-- Page Content -->
            <div id="content">
                <div class="container">
                    <?php
                    if ($resultTotalEmpruntsDepasse['total_emprunts_depasse'] > 0) {
                        echo "<div class='alert alert-warning'>
                                Vous avez " . $resultTotalEmpruntsDepasse['total_emprunts_depasse'] . " emprunt(s) dépassé(s) de plus de 30 jours. Veuillez les rendre dès que possible.
                              </div>";
                    }
                    ?>
                    <?php
                    if ($_SESSION['role'] == 'admin') {
                        echo "<h2>Statistiques Administrateur</h2>";
                        echo "<div class='statistic'>
                            <h3>Total des Emprunts Enregistrés</h3>
                            <p>" . $resultTotalEmpruntsAdmin['total_emprunts_admin'] . "</p>
                        </div>";

                        echo "<div class='statistic'>
                            <h3>Total des livres Enregistrés</h3>
                            <p>" . $resultTotalBooksAdmin['total_books_admin'] . "</p>
                        </div>";
                        echo "<h2>Statistiques Générales</h2>";
                    } else {
                        echo "<h2>Statistiques Utilisateur</h2>";
                    }
                    ?>

                    <div class="statistic">
                        <h3>Total des Livres disponibles</h3>
                        <p><?php echo $resultTotalBooks['total_books']; ?></p>
                    </div>

                    <div class="statistic">
                        <h3>Utilisateurs Enregistrés</h3>
                        <p><?php echo $resultTotalUsers['total_users']; ?></p>
                    </div>

                    <div class="statistic">
                        <h3>Emprunts Enregistrés</h3>
                        <p><?php echo $resultTotalEmprunts['total_emprunts']; ?></p>
                    </div>
                    <!-- ... Autres statistiques ... -->
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>
<?php
require_once('../template/footer.php');