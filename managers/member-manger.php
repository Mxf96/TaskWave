<?php
require_once '../includes/inc-db-connect.php';

// Fonction pour ajouter un membre à un board
function sendInvitationToBoard($dbh, $email, $boardID, $invitingUserID) {
    // Trouvez l'userID de l'email invité
    $stmt = $dbh->prepare("SELECT userID FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $invitedUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invitedUser) {
        return "Aucun utilisateur trouvé avec cet email.";
    }

    $invitedUserID = $invitedUser['userID'];

    // Insérez l'invitation dans la table `board_invitations`
    $stmt = $dbh->prepare("INSERT INTO board_invitations (boardID, invitedUserID, invitingUserID, status) VALUES (:boardID, :invitedUserID, :invitingUserID, 'pending')");
    $stmt->execute([':boardID' => $boardID, ':invitedUserID' => $invitedUserID, ':invitingUserID' => $invitingUserID]);

    return "Invitation envoyée avec succès.";
}

// Fonction pour récupérer les demandes d'ajout au projet
function getBoardJoinRequests($dbh, $userID) {
    $query = "SELECT bi.*, b.title AS boardTitle
              FROM board_invitations bi
              JOIN boards b ON bi.boardID = b.boardID
              WHERE bi.invitedUserID = ? AND bi.status = 'pending'";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour accepter une demande d'ajout au projet
function acceptBoardJoinRequest($dbh, $requestID) {
    try {
        // Débuter une transaction
        $dbh->beginTransaction();

        // Marquer l'invitation comme acceptée
        $queryUpdateInvitation = "UPDATE board_invitations SET status = 'accepted' WHERE invitationID = ?";
        $stmtUpdateInvitation = $dbh->prepare($queryUpdateInvitation);
        $stmtUpdateInvitation->execute([$requestID]);

        // Récupérer les détails de l'invitation pour obtenir boardID et invitedUserID
        $querySelectInvitation = "SELECT boardID, invitedUserID FROM board_invitations WHERE invitationID = ?";
        $stmtSelectInvitation = $dbh->prepare($querySelectInvitation);
        $stmtSelectInvitation->execute([$requestID]);
        $invitationDetails = $stmtSelectInvitation->fetch(PDO::FETCH_ASSOC);

        if ($invitationDetails) {
            // Insérer l'utilisateur comme membre du tableau
            $queryInsertMember = "INSERT INTO boardmember (userID, boardID) VALUES (?, ?)";
            $stmtInsertMember = $dbh->prepare($queryInsertMember);
            $stmtInsertMember->execute([$invitationDetails['invitedUserID'], $invitationDetails['boardID']]);
        }

        // Valider la transaction
        $dbh->commit();
        return true;
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $dbh->rollBack();
        return false;
    }
}

// Fonction pour refuser une demande d'ajout au projet
function rejectBoardJoinRequest($dbh, $requestID) {
    // Marquer l'invitation comme refusée
    $query = "UPDATE board_invitations SET status = 'rejected' WHERE invitationID = ?";
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

function getUserMemberBoards($dbh, $userID) {
    $query = "SELECT b.boardID, b.title
              FROM boards b
              JOIN boardmember bm ON b.boardID = bm.boardID
              WHERE bm.userID = ? AND b.userID != ?
              ORDER BY b.creationDate DESC";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$userID, $userID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

