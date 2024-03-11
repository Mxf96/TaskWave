<?php 
require_once '../includes/inc-db-connect.php';
require '../managers/member-manager.php'; // Assurez-vous que le nom est correct

if (isset($_GET['invitationID'])) {
    $invitationID = $_GET['invitationID'];

    if (acceptBoardJoinRequest($dbh, $invitationID)) {
        echo "<script>alert('Invitation accepted successfully.');</script>";
        header('Location: /dashboards/dashoard.php'); 
    } else {
        echo "<script>alert('Failed to accept invitation.');</script>";
        header('Location: /members/members.php'); 
    }
} else {
    header('Location: /dashboards//dashboard.php'); 
}

exit;