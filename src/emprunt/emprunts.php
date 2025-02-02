<?php 
require('../../config.php');


// Vérifiez si l'utilisateur est authentifié et a le rôle approprié (par exemple, "admin" ou "gestionnaire") pour accéder à cette fonctionnalité.
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

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

            if ($_SESSION['role'] === 'admin') {
                echo "<p>Ce qui figure ici correspond à la liste de tous les emprunts de tous les utilisateur car vous êtes admin.</p>";

                $query = "SELECT * FROM emprunts";
                $stmt = $pdo->query($query);
            }
            else
            {
                echo "<p>Ce qui figure ici correspond à la liste de vos emprunts en cours en tant qu'utilisateur authentifié.</p>";
                $query = "SELECT * FROM emprunts WHERE id_utilisateur = :id";
                $stmt = $pdo->prepare($query);
                $stmt->execute(array(':id' => $_SESSION['user_id']));
            }

            if ($stmt) {
                echo "<table>";
                echo "<tr><th>Utilisateur</th><th>Livre</th><th>Date d'emprunt</th><th>Date rendu prévue</th><th>Description</th><th>Actions</th></tr>";
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
                    $date_rendu = new DateTime($row['date_emprunt']);
                    $date_rendu = $date_rendu->add(new DateInterval('P30D'));
                    $date_actuelle = new DateTime();
                    $intervalDateEmprunt = $date_emprunt->diff($date_actuelle);
                    if ($intervalDateEmprunt->days > 30) {
                        $styleDateEmprunt = 'color: red;';
                    } elseif ($intervalDateEmprunt->days > 15) {
                        $styleDateEmprunt = 'color: orange;';
                    } else {
                        $styleDateEmprunt = 'color: green;';
                    }

                    $intervalDateRendu = $date_rendu->diff($date_actuelle);
                    
                    if ($intervalDateRendu->days > 30) {
                        $styleDateRendu = 'color: red;';
                    } elseif ($intervalDateRendu->days > 15) {
                        $styleDateRendu = 'color: orange;';
                    } else {
                        $styleDateRendu = 'color: green;';
                    }

                    echo "<td style='{$styleDateEmprunt}'>" . $date_emprunt->format('d/m/Y H:i:s') . "</td>";
                    echo "<td style='{$styleDateRendu}'>" . $date_rendu->format('d/m/Y') . "</td>";
                    echo "<td>{$row['description']}</td>";
                    if ($intervalDateEmprunt->days > 30) {
                        echo "<td><button class='btn btn-danger' title='Vous êtes en retard sur votre rendu !' onclick=\"window.location.href = 'return_emprunt.php?emprunt_id={$row['id']}'\">Rendre le livre</button></td>";
                    } elseif ($intervalDateEmprunt->days < 2) {
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
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-success" style="width: auto; display: inline-block;" onclick="window.location.href = 'add_emprunt.php'">Faire un emprunt</button>
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

