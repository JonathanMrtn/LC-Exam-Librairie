<?php
require('../../config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Récupération des informations de l'utilisateur depuis la base de données
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM utilisateurs WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(array(':user_id' => $user_id));
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

include('../template/header.php');

?>
        <div class="container mt-5">
            <div class="card">
                <div class="card-header">
                    <h3>Profil de l'utilisateur</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> <?php echo $userInfo['nom']; ?></p>
                    <p><strong>Email :</strong> <?php echo $userInfo['email']; ?></p>
                    <p><strong>Rôle :</strong> <?php echo $userInfo['role']; ?></p>
                    <p><strong>Date d'inscription :</strong> <?php echo date('d/m/Y', strtotime($userInfo['date_inscription'])); ?></p>
                    <!-- Affichez d'autres informations du profil ici -->
                    <button class="btn btn-primary" onclick="window.location.href ='edit_profile.php'">Modifier le Profil</button>
                    <button class="btn btn-secondary" onclick="window.location.href ='../../index.php'">Retour à l'accueil</button>
                </div>
            </div>
        </div>
<?php
require_once('../template/footer.php');