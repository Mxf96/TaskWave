<?php 
require '../includes/inc-top-dashboard.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Navbar verticale -->
        <div class="col-md-3" style="background-color: #f8f9fa;"> <!-- Utilisez une classe de couleur de fond ou un style inline -->
            <div class="d-flex flex-column flex-shrink-0 p-3">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <span class="fs-4">Espace de travail TaskWave</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="/path/to/tableaux" class="nav-link active" aria-current="page">
                            Tableaux
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/path/to/membres" class="nav-link">
                            Membres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/path/to/parametres" class="nav-link">
                            Paramètres
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-9">
            <div class="p-4">
                <h2>Bienvenue sur votre tableau de bord TaskWave</h2>
                <!-- Le contenu de votre tableau de bord va ici -->
            </div>
        </div>
    </div>
</div>