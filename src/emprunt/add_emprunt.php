<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../../config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collectez les données du formulaire
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_livre = $_POST['id_livre'];
    if (empty($_POST['description'])) {
        $description = null;
    } else {
        $description = $_POST['description'];
    }

    // Effectuez des validations (assurez-vous que les données sont correctes)
    if (empty($id_utilisateur)) {
        $errors[] = "L'utilisateur de l'emprunt est requis.";
    }
    if (empty($id_livre)) {
        $errors[] = "Le livre de l'emprunt est requis.";
    }
    // Ajoutez d'autres validations ici...

    // Si aucune erreur de validation n'est présente
    if (empty($errors)) {

        // on vérifie que l'utilisateur n'est pas déjà emprunté ce livre
        $query = "SELECT COUNT(*) FROM emprunts WHERE id_utilisateur = :id_utilisateur AND id_livre = :id_livre";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(':id_utilisateur' => $id_utilisateur, ':id_livre' => $id_livre));
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $errors[] = "L'utilisateur a déjà emprunté ce livre.";
        }
        else
        {
            var_dump($description);exit;
            $query = "INSERT INTO emprunts (date_emprunt, id_utilisateur, id_livre, signalement, description) VALUES (NOW(), :id_utilisateur, :id_livre, 0, :description)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(
                ':id_utilisateur' => $id_utilisateur,
                ':id_livre' => $id_livre,
                ':description' => $description
            ));
    
            $query = "UPDATE livres SET statut = 'emprunté' WHERE id = :id_livre";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':id_livre' => $id_livre));
            // Indiquez que l'ajout du livre a réussi
            $success = true;
        }


    }
}

$titre = "Ajouter un Emprunt";
require_once ('../template/header.php');
?>
        <?php if ($success) : ?>
            <div class="alert alert-success text-center" role="alert" style="margin-top: 20px;">
                L'emprunt a été ajouté avec succès.
            </div>
            <div class="text-center" style="margin-top: 20px;">
                <button class="btn btn-primary btn-sm" style="width: auto;" onclick="window.location.href = 'emprunts.php'">Retour à la gestion des emprunts</button>
            </div>
        <?php else : ?>
            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="id_utilisateur" class="col-sm-2 control-label">Utilisateur</label>
                    <div class="col-sm-10">
                        <select name="id_utilisateur" id="id_utilisateur" class="form-control">
                            <?php
                            if ($_SESSION['role'] === 'admin') {
                                $query = "SELECT id, nom FROM utilisateurs";
                            } else {
                                $query = "SELECT id, nom FROM utilisateurs WHERE id = " . $_SESSION['user_id'];
                            }
                            $stmt = $pdo->query($query);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nom']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="id_livre" class="col-sm-2 control-label">Livre</label>
                    <div class="col-sm-10">
                        <select name="id_livre" id="id_livre" class="form-control">
                            <?php
                            $query = "SELECT id, titre FROM livres WHERE statut = 'disponible'";
                            if (isset($_GET['id_livre'])) {
                                $id_livre_url = $_GET['id_livre'];
                                $query .= " AND id = :id_livre_url";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute(array(':id_livre_url' => $id_livre_url));
                            } else {
                                $stmt = $pdo->query($query);
                            }
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['titre']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <textarea name="description" id="description" class="form-control" maxlength="255"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Faire un emprunt</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
<?php
require_once('../template/footer.php');
