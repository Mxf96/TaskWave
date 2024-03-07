<?php
require_once '../includes/inc-db-connect.php';
require_once '../managers/sanitize_input-manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des champs vides
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        $_SESSION['data'] = ['email' => $email];
        header("Location: login.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Enregistrement des données de l'utilisateur dans la session
        $_SESSION['userID'] = true;
        $_SESSION['userID'] = $user['userID']; // Assurez-vous que votre table users a une colonne 'userID'
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = "Identifiants incorrects.";
        $_SESSION['data'] = ['email' => $email];
        header("Location: login.php");
        exit();
    }
}
?>