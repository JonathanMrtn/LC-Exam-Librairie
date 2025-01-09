<?php 

require('../../config.php');

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

$query = "SELECT * FROM utilisateurs";
$stmt = $pdo->query($query);
$titre = "[BACKOFFICE]";
require_once('../template/header.php');
?>
        <div class="container">
            <?php
            
            if ($stmt) {
                echo "<table>";
                echo "<tr><th>Nom</th><th>Prénom</th><th>Date d'inscription</th><th>Rôle</th><th>Actions</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['nom']}</td>";
                    echo "<td>{$row['prenom']}</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['date_inscription'])) . "</td>";
                    if ($row['role'] === 'admin') {
                        echo "<td><strong style='color: red;'>{$row['role']}</strong></td>";
                    } else {
                        echo "<td>{$row['role']}</td>";
                    }
                    echo "<td><button class='btn btn-success' onclick=\"window.location.href = 'edit_user.php?user_id={$row['id']}'\">Modifier</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Erreur lors de la récupération des emprunts.";
            }
            ?>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-secondary" style="width: auto; display: inline-block;" onclick="window.location.href = '../../index.php'">Retour à l'accueil</button>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
<?php
require_once('../template/footer.php');
?>

