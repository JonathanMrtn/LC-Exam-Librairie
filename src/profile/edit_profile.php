
<?php
include('../template/header.php');
?>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Profil de l'utilisateur</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="update_profile.php">
                        <label for="new_name">Nouveau Nom :</label>
                        <input type="text" name="new_name" required>
                        <br>
                        <label for="new_email">Nouvel Email :</label>
                        <input type="email" name="new_email" required>
                        <br>
                        <!-- Ajoutez d'autres champs à mettre à jour ici -->
                        <button type="submit">Enregistrer les Modifications</button>
                    </form>
                    <button onclick="window.location.href ='profile.php'">Retour au Profil</a>
                    <button onclick="window.location.href ='../../index.php'">Retour à l'accueil</a>
                </div>
            </div>
        </div>
<?php
require_once('../template/footer.php');
