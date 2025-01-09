<?php
require('../../config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est authentifié et a le rôle approprié (par exemple, "admin" ou "gestionnaire") pour accéder à cette fonctionnalité.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Assurez-vous que l'ID du livre que vous souhaitez modifier est passé en tant que paramètre (par exemple, dans l'URL).
if (!isset($_GET['emprunt_id'])) {
    header('Location: emprunt.php'); // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
    exit();
}

$emprunt_id = $_GET['emprunt_id'];

// Récupérez les détails du livre à partir de la base de données pour les afficher dans le formulaire de modification.
$query = "SELECT * FROM emprunts WHERE id = :emprunt_id";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':emprunt_id' => $emprunt_id));
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérez les données du formulaire de modification
    $description = $_POST['description'];
    $date_emprunt = $_POST['date_emprunt'];
    $signalement = $_POST['signalement']; 

    // Mettez à jour les détails du livre dans la base de données
    $updateQuery = "UPDATE emprunts SET date_emprunt = :date_emprunt, signalement = :signalement, description = :description WHERE id = :emprunt_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(array(
        ':date_emprunt' => $date_emprunt,
        ':signalement' => $signalement,
        ':description' => $description,
        ':emprunt_id' => $emprunt_id
    ));

    // Redirigez l'utilisateur vers la page de détails du livre mis à jour ou une autre page appropriée.
    header('Location: emprunt.php');
    exit();
}
$titre = "Modifier un emprunt";
require_once ('../template/header.php');
?>
        <form method="post">
            <label for="date_emprunt">Date d'emprunt</label>
            <input type="date" name="date_emprunt" value="<?php echo $book['date_emprunt']; ?>" required>
            <br>
            <label for="description">Description :</label>
            <textarea name="description" required><?php echo $book['description']; ?></textarea>
            <br>
            <label for="signalement">Signaler l'emprunt</label>
            <input type="checkbox" name="signalement" value="1" <?php echo $book['signalement'] ? 'checked' : ''; ?>>
            <br>
            <button type="submit">Enregistrer les Modifications</button>
        </form>
        <button onclick="window.location.href ='emprunt.php'">Retour à la Liste des emprunts</a>
<?php
require_once('../template/footer.php');