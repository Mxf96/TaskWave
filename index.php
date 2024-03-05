<?php require 'includes/inc-top.php'; ?>

<!-- Section d'Introduction -->
<section class="py-5 text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Bienvenue sur TaskWave</h1>
            <p class="lead text-muted">Gérez vos tâches et projets en toute simplicité avec TaskWave, votre outil de planification tout-en-un.</p>
            <p>
            <a href="#" onclick="handleStartNowClick()" class="btn btn-primary my-2">Commencer maintenant</a>
                <a href="#" class="btn btn-secondary my-2">En savoir plus</a>
            </p>
        </div>
    </div>
</section>

<!-- Section des Fonctionnalités -->
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Planification</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Planification</text>
                    </svg>
                    <div class="card-body">
                        <p class="card-text">Organisez vos projets et tâches avec notre outil de planification flexible.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Suivi des Tâches</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Suivi des Tâches</text>
                    </svg>
                    <div class="card-body">
                        <p class="card-text">Suivez l'avancement de vos tâches et projets en temps réel.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Collaboration</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Collaboration</text>
                    </svg>
                    <div class="card-body">
                        <p class="card-text">Collaborez facilement avec votre équipe, où que vous soyez.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function handleStartNowClick() {
    <?php if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']): ?>
        // L'utilisateur est connecté, redirigez vers la page de création de projet
        window.location.href = '/dashboards/dashboard.php'; // Remplacez par le chemin réel
    <?php else: ?>
        // L'utilisateur n'est pas connecté, redirigez vers la page de connexion
        window.location.href = '/logs/login.php';
    <?php endif; ?>
}
</script>
<?php require 'includes/inc-bottom.php'; ?>