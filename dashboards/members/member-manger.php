<?php
require_once '../../includes/inc-db-connect.php';

// Fonction pour ajouter un membre à un board
function addMemberToBoard($dbh, $userID, $boardID) {
    $query = "INSERT INTO boardmember (userID, boardID) VALUES (?, ?)";
    $stmt = $dbh->prepare($query);
    return $stmt->execute([$userID, $boardID]);
}

// Fonction pour récupérer les demandes d'ajout au projet
function getBoardJoinRequests($dbh, $boardID) {
    $query = "SELECT * FROM board_invitations WHERE boardID = ? AND status = 'pending'";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$boardID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour accepter une demande d'ajout au projet
function acceptBoardJoinRequest($dbh, $requestID) {
    // Marquer l'invitation comme acceptée
    $query = "UPDATE invitations SET status = 'accepted' WHERE invitationID = ?";
    $stmt = $dbh->prepare($query);
    return $stmt->execute([$requestID]);
}

// Fonction pour refuser une demande d'ajout au projet
function rejectBoardJoinRequest($dbh, $requestID) {
    // Marquer l'invitation comme refusée
    $query = "UPDATE invitations SET status = 'rejected' WHERE invitationID = ?";
    $stmt = $dbh->prepare($query);
    return $stmt->execute([$requestID]);
}

// Fonction pour récupérer les membres d'un board
function getBoardMembers($dbh, $boardID) {
    $query = "SELECT users.userID, users.firstName, users.lastName FROM users JOIN boardmember ON users.userID = boardmember.userID WHERE boardmember.boardID = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$boardID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBoardsAndMembers($dbh) {
    try {
        // Prepare SQL query to select boards and their members
        $sql = "SELECT b.boardID, b.title AS boardTitle, u.userID, u.firstName, u.lastName
                FROM boards b
                JOIN boardmember bm ON b.boardID = bm.boardID
                JOIN users u ON bm.userID = u.userID
                ORDER BY b.boardID, u.userID";

        // Prepare and execute the query
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        error_log('Query failed: ' . $e->getMessage());
        return [];
    }
}

function getListsByBoardID($dbh, $boardID) {
    try {
        $query = "SELECT * FROM `list` WHERE boardID = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$boardID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erreur lors de la récupération des listes : ' . $e->getMessage());
        return [];
    }
}
?>
