<?php require 'inc-db-connect.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskWave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <img src="/assets/pictures/logo_TaskWave-removebg-preview.png" alt="Logo TaskWave" class="img-fluid" style="max-height: 40px;">
            <a class="navbar-brand" href="#">TaskWave</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Prix</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) : ?>
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

<script src="../scripts/logout.js"></script>