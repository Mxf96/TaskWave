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
