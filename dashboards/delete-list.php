<?php 
require_once '../includes/inc-db-connect.php';
require '../managers/dashboard-manager.php';

if (isset($_GET['listID'])) {
    $listID = $_GET['listID'];
    
    // Vous devez obtenir le boardID avant de supprimer la liste pour pouvoir rediriger correctement
    $listDetails = getListDetails($dbh, $listID); // Vous aurez besoin de créer cette fonction pour obtenir les détails de la liste, y compris le boardID
    $boardID = $listDetails['boardID'];

    if (deleteListAndTasks($dbh, $listID)) {
        // Rediriger vers la page du tableau avec un message de succès
        header("Location: http://taskwave.local/dashboards/dashboard.php?boardID=" . $boardID . "&message=listDeleted");
    } else {
        // Rediriger vers la page du tableau avec un message d'erreur
        header("Location: http://taskwave.local/dashboards/dashboard.php?boardID=" . $boardID . "&error=deleteFailed");
    }
} else {
    // Rediriger vers une page d'erreur si aucun ID de liste n'est spécifié
    header('Location: /dashboards/dashboard.php?error=noListID');
}
exit;
