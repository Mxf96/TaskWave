<?php
require '../includes/inc-top-form.php';
require_once '../includes/inc-db-connect.php';
require '../managers/sanitize_input-manager.php';

$email = $_SESSION['data']['email'] ?? '';
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? ''; // Ajout pour gérer le message de succès après l'inscription

// Suppression des messages d'erreur et de succès après leur affichage
unset($_SESSION['error'], $_SESSION['success'], $_SESSION['data']);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="mt-4 text-center">
            <a href="/index.php">Retour</a>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center">Connexion</h2>
                    <?php if ($error) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success) : ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <form action="login-POST.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo sanitize_input($email); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        <div class="mt-4 text-center">
                            Pas encore inscrit ? <a href="/logs/register.php">Créez un compte</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>