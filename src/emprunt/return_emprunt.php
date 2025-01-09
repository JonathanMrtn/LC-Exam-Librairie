<?php
require('../../config.php');

// Assurez-vous que l'ID du livre que vous souhaitez supprimer est passé en tant que paramètre (par exemple, dans l'URL).
if (empty($_GET['emprunt_id'])) {
    header('Location: emprunts.php'); // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
    exit();
}

$emprunt_id = $_GET['emprunt_id'];

// Vérifiez si l'utilisateur est authentifié et a le rôle approprié (par exemple, "admin" ou "gestionnaire") pour accéder à cette fonctionnalité.
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // on récupère l'id du livre emprunté
    $query = "SELECT id_livre FROM emprunts WHERE id = :emprunt_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':emprunt_id' => $emprunt_id));
    $emprunt = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$emprunt) {
        header('Location: emprunts.php');
        exit();
    }
    else
    {
        // Supprimez le livre de la base de données en utilisant l'ID du livre
        $deleteQuery = "DELETE FROM emprunts WHERE id = :emprunt_id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute(array(':emprunt_id' => $emprunt_id));
        if (!$deleteStmt) {
            header('Location: emprunts.php');
            exit();
        }
        else
        {
            // On remet le livre en disponible dans la table des livres
            $updateQuery = "UPDATE livres SET statut = 'disponible' WHERE id = :livre_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute(array(':livre_id' => $emprunt['livre_id']));
            if (!$updateStmt) {
                header('Location: emprunts.php');
                exit();
            }
            else
            {
                // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
                header('Location: emprunts.php');
                exit();
            }
        }
    }

}

$titre = "Confirmation du rendu";
require_once ('../template/header.php');
?>
        <div class="container mt-5">
            <h1 class="text-danger">Confirmation du rendu</h1>
            <p>Êtes-vous sûr de vouloir rendre cet emprunt ?</p>
            <form method="post" action="return_emprunt.php?emprunt_id=<?php echo $emprunt_id; ?>">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Oui</button>
            </form>
            <button type="button" class="btn btn-info" onclick="window.history.back();">Non</button>
        </div>
<?php
require_once ('../template/footer.php');