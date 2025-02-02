<?php
require('../../config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de l'existence de l'email
    $query = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':email' => $email));

    if ($stmt->rowCount() > 0) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Vérification de la complexité du mot de passe
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
            $error = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
        } else {
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insertion de l'utilisateur dans la base de données avec le mot de passe hashé
            $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:name, :prenom, :email, :password)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(':name' => $name, ':prenom' => $prenom, ':email' => $email, ':password' => $hashedPassword));


            if ($stmt) {
                // Redirection vers la page de connexion
                header('Location: login.php');
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
$titre = "Inscription";
include('../template/header.php');
?>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prenom" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" minlength="12" required>
            <button type="submit">S'inscrire</button>
        </form>

        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
<?php   
include('../template/footer.php');