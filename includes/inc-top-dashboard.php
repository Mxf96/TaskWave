<?php require 'inc-db-connect.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Logo et nom de l'application -->
            <a class="navbar-brand" href="/dashboards/dashboard.php">
                <img src="/assets/pictures/logo_TaskWave-removebg-preview.png" alt="Logo TaskWave" class="img-fluid" style="max-height: 40px;">
                TaskWave
            </a>
            <!-- Bouton pour les écrans réduits -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Contenus de la navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Liens sur la gauche -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a href="#" class="nav-link">Espace de travail</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Favoris</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Modèles</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownCreer" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Créer
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownCreer">
                            <li class="nav-item">
                                <a href="#" id="creerTableau" class="nav-link">Créer un tableau</a>
                            </li>
                            <!-- <li><a class="dropdown-item" href="/path/to/utiliser-modele">Utiliser un modèle</a></li> -->
                        </ul>
                    </li>
                </ul>
                <!-- Liens sur la droite -->
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['userID']) && $_SESSION['userID']) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="confirmLogout()">Déconnexion</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/logs/login.php">Se Connecter</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>