<?php
require_once '../includes/inc-db-connect.php';

function createNewDashboard($dbh, $title, $description, $userID)
{
    try {
        // Step 1: Insert the new board
        $query = "INSERT INTO boards (title, description, creationDate, userID) VALUES (?, ?, NOW(), ?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$title, $description, $userID]);
        $newBoardID = $dbh->lastInsertId();

        // Step 2: Add the creator as a member of the board
        $memberQuery = "INSERT INTO boardmember (userID, boardID) VALUES (?, ?)";
        $memberStmt = $dbh->prepare($memberQuery);
        $memberStmt->execute([$userID, $newBoardID]);

        return $newBoardID;
    } catch (PDOException $e) {
        error_log('Error in createNewDashboard: ' . $e->getMessage());
        return false;
    }
}

function getCurrentBoardID() {
    if (isset($_GET['boardID']) && !empty($_GET['boardID'])) {
        $boardID = intval($_GET['boardID']); // Utilisez intval() pour s'assurer que l'ID est un entier
        return $boardID;
    } else {
        return false; // Aucun ID de tableau n'est fourni
    }
}


function getBoardDetails($dbh, $boardID) {
    // Préparez la requête SQL pour récupérer les détails du tableau
    $query = "SELECT * FROM boards WHERE boardID = :boardID";
    
    // Préparez et exécutez la requête
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':boardID', $boardID, PDO::PARAM_INT);
    $stmt->execute();
    
    // Récupérez le résultat
    $boardDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifiez si des détails ont été trouvés
    if ($boardDetails) {
        return $boardDetails;
    } else {
        return false;
    }
}


function createNewList($dbh, $title, $boardID) {
    try {
        // Trouver la position la plus élevée actuelle pour le boardID donné
        $positionQuery = "SELECT MAX(position) as maxPosition FROM `list` WHERE boardID = ?";
        $positionStmt = $dbh->prepare($positionQuery);
        $positionStmt->execute([$boardID]);
        $maxPosition = $positionStmt->fetch(PDO::FETCH_ASSOC)['maxPosition'];

        // La nouvelle position est maxPosition + 1
        $newPosition = $maxPosition + 1;

        // Préparez la requête SQL pour insérer la nouvelle liste avec la nouvelle position
        $query = "INSERT INTO `list` (title, position, boardID) VALUES (?, ?, ?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$title, $newPosition, $boardID]);
        return true;
    } catch (PDOException $e) {
        error_log('Erreur lors de la création de la liste : ' . $e->getMessage());
        return false;
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

function createNewTask($dbh, $title, $listID) {
    try {
        $query = "INSERT INTO task (title, listID) VALUES (?, ?)";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$title, $listID]);
        return true;
    } catch (PDOException $e) {
        error_log('Erreur lors de la création de la tâche : ' . $e->getMessage());
        return false;
    }
}

function getTasksByListID($dbh, $listID) {
    try {
        $query = "SELECT * FROM `task` WHERE listID = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$listID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erreur lors de la récupération des tâches : ' . $e->getMessage());
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

function getListDetails($dbh, $listID) {
    $query = "SELECT * FROM list WHERE listID = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$listID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteListAndTasks($dbh, $listID) {
    try {
        // Commencer la transaction
        $dbh->beginTransaction();

        // Supprimer toutes les tâches associées à la liste
        $query = "DELETE FROM task WHERE listID = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$listID]);

        // Supprimer la liste elle-même
        $query = "DELETE FROM list WHERE listID = ?";
        $stmt = $dbh->prepare($query);
        $stmt->execute([$listID]);

        // Valider la transaction
        $dbh->commit();
        return true;
    } catch (Exception $e) {
        // Une erreur est survenue, annuler la transaction
        $dbh->rollBack();
        error_log("Erreur lors de la suppression de la liste et de ses tâches : " . $e->getMessage());
        return false;
    }
}
