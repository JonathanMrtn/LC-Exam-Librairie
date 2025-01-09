<?php 


require('../../config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$titre = "Liste des Livres - Librairie XYZ";
$style = "
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
       
            color: #fff;
            text-align: center;
            padding: 1em 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
    
            color: #fff;
        }

        .book-image {
            max-width: 100px; /* Ajustez la taille maximale de l'image selon vos besoins */
            height: auto;
        }

        button {
     
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            .book-image {
                max-width: 50px;
            }
        }";
require_once('../template/header.php');
?>
        <div class="container">
            <!-- Affichage des livres depuis la base de données -->
            <?php
            require('../../config.php');

            $query = "SELECT * FROM livres";
            $stmt = $pdo->query($query);

            if ($stmt) {
                echo "<table>";
                echo "<tr><th>Image</th><th>Titre</th><th>Auteur</th><th>Date de publication</th><th>Statut</th><th>Emprunt</th><th>Détails</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo '<td><img class="book-image" src="' . $row['photo_url'] . '" alt="' . $row['titre'] . '"></td>';
                    echo "<td>{$row['titre']}</td>";
                    echo "<td>{$row['auteur']}</td>";
                    echo "<td>{$row['date_publication']}</td>";
                    if ($row['statut'] === 'disponible') {
                        echo '<td style="color: green;">Disponible</td>';
                    } else {
                        echo '<td style="color: orange;">Emprunté</td>';
                    }
                    // echo '<td><a href="../emprunt/add_emprunt.php?id_livre=' . $row['id'] . '">Faire un emprunt</a></td>';
                    if ($row['statut'] === 'disponible') {
                        echo '<td><a class="btn btn-primary" href="../emprunt/add_emprunt.php?id_livre=' . $row['id'] . '">Faire un emprunt</a></td>';
                    } else {
                        echo '<td><button class="btn btn-secondary" title="Vous ne pouvez pas emprunter un livre déjà emprunté" disabled>Faire un emprunt</button></td>';
                    }
                    echo '<td><a href="book_details.php?id=' . $row['id'] . '">Voir les détails</a></td>';
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Erreur lors de la récupération des livres.";
            }
            ?>
            <div style="text-align: center; margin-top: 20px;">
                <!-- Bouton "Ajouter un livre" visible uniquement pour les admins -->
                <?php if ($_SESSION['role'] === 'admin') : ?>
                    <button class="btn btn-success" style="width: auto; display: inline-block;" onclick="window.location.href = 'add_book.php'">Ajouter un livre</button>
                <?php endif; ?>
                <button class="btn btn-secondary" style="width: auto; display: inline-block;" onclick="window.location.href = '../../index.php'">Retour à l'accueil</button>
            </div>
            
        </div>
<?php
require_once('../template/footer.php');
?>

