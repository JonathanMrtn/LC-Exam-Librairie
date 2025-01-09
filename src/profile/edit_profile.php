
<?php
require('../../config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

include('../template/header.php');
?>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Profil de l'utilisateur</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="update_profile.php">
                        <div class="form-group">
                            <label for="new_name">Nouveau Nom :</label>
                            <input type="text" class="form-control" name="new_name" required>
                        </div>
                        <div class="form-group">
                            <label for="new_email">Nouvel Email :</label>
                            <input type="email" class="form-control" name="new_email" required>
                        </div>
                        <!-- Ajoutez d'autres champs à mettre à jour ici -->
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer les Modifications</button>
                    </form>
                    <button onclick="window.location.href ='profile.php'">Retour au Profil</a>
                    <button onclick="window.location.href ='../../index.php'">Retour à l'accueil</a>
                </div>
            </div>
        </div>
<?php
require_once('../template/footer.php');
