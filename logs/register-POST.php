<?php
require '../includes/inc-db-connect.php';
require_once '../managers/sanitize_input-manager.php';

// Récupération et nettoyage des données du formulaire
$firstName = sanitize_input($_POST['firstName']);
$lastName = sanitize_input($_POST['lastName']);
$email = sanitize_input($_POST['email']);
$password = sanitize_input($_POST['password']);
$confirmPassword = sanitize_input($_POST['confirmPassword']);

// Vérification des champs vides et de la correspondance des mots de passe
if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || $password !== $confirmPassword) {
    $_SESSION['error'] = "Tous les champs doivent être remplis et les mots de passe doivent correspondre.";
    header('Location: register.php');
    exit();
}

try {
    // Vérifier si l'utilisateur existe déjà
    $stmt = $dbh->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Un compte avec cet email existe déjà.";
        header('Location: register.php');
        exit();
    }

    // Hashage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Préparation et exécution de la requête d'insertion
    $sql = "INSERT INTO users (firstName, lastName, email, password, signUpDate) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);

    // Si tout est bon, enregistrer un message de succès dans la session et rediriger vers login
    $_SESSION['success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
    header('Location: ../logs/login.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de l'inscription: " . $e->getMessage();
    header('Location: register.php');
    exit();
}
?>