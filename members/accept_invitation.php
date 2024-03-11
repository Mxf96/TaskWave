<?php 
require_once '../includes/inc-db-connect.php';
require '../managers/member-manger.php';

// Vérifiez si invitationID est présent dans la requête
if (isset($_GET['invitationID'])) {
    $invitationID = $_GET['invitationID'];
    $boardID = acceptBoardJoinRequest($dbh, $invitationID);

    // Appelez la fonction pour accepter l'invitation
    if (acceptBoardJoinRequest($dbh, $invitationID)) {
        echo "<script>alert('Invitation accepted successfully.');</script>";
        // Redirigez vers une page appropriée après l'acceptation
        header("Location: http://taskwave.local/dashboards/dashboard.php?boardID=" . $boardID);
    } else {
        echo "<script>alert('Failed to accept invitation.');</script>";
        // Redirigez vers une page appropriée en cas d'échec
        header('Location: /members/members.php'); 
    }
} else {
    // Redirigez vers une page d'erreur ou d'accueil si l'ID de l'invitation n'est pas spécifié
    header('Location: /dashboards/dashboard.php'); 
}

exit;