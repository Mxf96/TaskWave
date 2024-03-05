<?php 
require_once '../includes/inc-top-form.php'; // Assurez-vous que ce fichier inclut les balises <html>, <head> et ouvre la balise <body>

// Initialisation des variables avec des chaînes vides
$firstName = $lastName = $email = "";
if(isset($_SESSION['error'])){
    $error = $_SESSION['error'];
    // Restauration des données précédemment saisies par l'utilisateur
    $firstName = $_SESSION['data']['firstName'];
    $lastName = $_SESSION['data']['lastName'];
    $email = $_SESSION['data']['email'];
} else {
    $error = "";
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Inscription</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form action="register-POST.php" method="post">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmez le mot de passe</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                        </div>
                        <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/inc-bottom-form.php'; 
