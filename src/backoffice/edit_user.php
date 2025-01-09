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
if (!isset($_GET['user_id'])) {
    header('Location: backoffice.php'); // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
    exit();
}

$user_id = $_GET['user_id'];

// Récupérez les détails du livre à partir de la base de données pour les afficher dans le formulaire de modification.
$query = "SELECT * FROM utilisateurs WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':user_id' => $user_id));
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
if ($utilisateur) {
    $utilisateur['date_inscription'] = date('Y-m-d', strtotime($utilisateur['date_inscription']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_inscription = $_POST['date_inscription'];
    $role = $_POST['role'];

    // Récupérez les données du formulaire de modification
    // Mettez à jour les détails du livre dans la base de données
    $updateQuery = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, date_inscription = :date_inscription, role = :role WHERE id = :user_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(array(
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':date_inscription' => $date_inscription,
        ':role' => $role,
        ':user_id' => $user_id
    ));

    // Redirigez l'utilisateur vers la page de détails du livre mis à jour ou une autre page appropriée.
    header('Location: backoffice.php');
    exit();
}
$titre = "Modifier un utilisateur";
require_once ('../template/header.php');
?>
        <div class="container mt-5">
            <h2>Modifier un utilisateur</h2>
            <form method="post">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" name="nom" value="<?php echo $utilisateur['nom']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prenom</label>
                    <input type="text" class="form-control" name="prenom" value="<?php echo $utilisateur['prenom']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="date_inscription">Date d'inscription</label>
                    <input type="date" class="form-control" name="date_inscription" value="<?php echo $utilisateur['date_inscription']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select class="form-control" name="role" required>
                        <option value="utilisateur" <?php echo ($utilisateur['role'] == 'utilisateur') ? 'selected' : ''; ?>>Utilisateur</option>
                        <option value="admin" <?php echo ($utilisateur['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">Enregistrer les Modifications</button>
                    <a href="backoffice.php" class="btn btn-secondary">Retour</a>
                </div>
            </form>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
<?php
require_once('../template/footer.php');