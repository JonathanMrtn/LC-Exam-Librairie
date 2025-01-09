<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title><?php (empty($titre) ? "Librairie XYZ" : $titre) ?></title>
    <style>
        <?php (empty($style) ? null : $style) ?>
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/LC/Examen/evaluation-librairie/src/home/home.php">Librairie XYZ</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/LC/Examen/evaluation-librairie/src/book/books.php">Livres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/LC/Examen/evaluation-librairie/src/profile/profile.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/LC/Examen/evaluation-librairie/src/auth/logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>