<?php 
$titre = "Liste des Emprunts - Librairie XYZ";
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
            <!-- Affichage des emprunts depuis la base de données -->
            <?php
            require('../../config.php');

            if ($_SESSION['role'] === 'admin') {
                echo "<p>Ce qui figure ici correspond à la liste de tous les emprunts.</p>";

                $query = "SELECT * FROM emprunts";
                $stmt = $pdo->query($query);
            }
            else
            {
                echo "<p>Ce qui figure ici correspond à la liste de vos emprunts en cours.</p>";
                $query = "SELECT * FROM emprunts WHERE id_utilisateur = :id";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(':id' => $_SESSION['user_id']));
            }

            if ($stmt) {
                echo "<table>";
                echo "<tr><th>Utilisateur</th><th>Livre</th><th>Date d'emprunt</th><th>Description</th><th>Rendu livre</th></tr>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    $query = "SELECT * FROM utilisateurs WHERE id = :id";
                    $stmt2 = $pdo->prepare($query);
                    $stmt2->execute(array(':id' => $row['id_utilisateur']));                    
                    $utilisateur = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if ($utilisateur) {
                        echo "<td>{$utilisateur['nom']}</td>";
                    } else {
                        echo "<td>Utilisateur inconnu</td>";
                    }

                    $query = "SELECT * FROM livres WHERE id = :id";
                    $stmt3 = $pdo->prepare($query);
                    $stmt3->execute(array(':id' => $row['id_livre']));
                    $livre = $stmt3->fetch(PDO::FETCH_ASSOC);
                    if ($livre) {
                        echo "<td>{$livre['titre']}, {$livre['auteur']}, {$livre['date_publication']}</td>";
                    } else {
                        echo "<td>Livre inconnu, >Auteur inconnu, Date de publication inconnue</td>";
                    }
                    $date_emprunt = new DateTime($row['date_emprunt']);
                    $date_actuelle = new DateTime();
                    $interval = $date_emprunt->diff($date_actuelle);
                    if ($interval->days > 30) {
                        $style = 'color: red;';
                    } elseif ($interval->days > 15) {
                        $style = 'color: orange;';
                    } else {
                        $style = 'color: green;';
                    }
                    echo "<td style='{$style}'>{$row['date_emprunt']}</td>";
                    echo "<td>{$row['description']}</td>";
                    if ($interval->days > 30) {
                        echo "<td><button class='btn btn-danger' title='Vous êtes en retard sur votre rendu !' onclick=\"window.location.href = 'return_emprunt.php?emprunt_id={$row['id']}'\">Rendre le livre</button></td>";
                    } elseif ($interval->days < 2) {
                        echo "<td><button class='btn btn-secondary' title='Vous ne pouvez pas rendre un livre avant 2 jours' disabled>Rendre le livre</button></td>";
                    } else {
                        echo "<td><button class='btn btn-primary' title='Vous pouvez rendre le livre' onclick=\"window.location.href = 'return_emprunt.php?emprunt_id={$row['id']}'\">Rendre le livre</button></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Erreur lors de la récupération des emprunts.";
            }
            ?>
    <!-- Bouton "Ajouter un emprunt" visible uniquement pour les admins -->
            <button onclick="window.location.href = 'add_emprunt.php'">Ajouter un emprunt</button>
            <button onclick="window.location.href = '../../index.php'">Retour à l'accueil</button>
            
        </div>
<?php
require_once('../template/footer.php');
?>

