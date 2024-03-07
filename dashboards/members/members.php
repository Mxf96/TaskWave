<?php
require '../../includes/inc-top-dashboard.php';
require_once '../../includes/inc-db-connect.php';
require 'sanitize_input-manager.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $query = "SELECT boardID, title FROM boards WHERE userID = ? ORDER BY boardID ASC";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$userID]);
    $boards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $boards = [];
}


if (isset($_GET['boardID']) && !empty($_GET['boardID'])) {
    $boardID = $_GET['boardID'];
    $query = "SELECT * FROM boards WHERE boardID = ? AND userID = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$boardID, $userID]);
    $boardDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    $lists = getListsByBoardID($dbh, $boardID);
}

?>
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3" style="background-color: #f8f9fa;">
            <div class="d-flex flex-column flex-shrink-0 p-3">
                <a href="/dashboards/dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <h6 class="fs-4">Espace de travail TaskWave</h6>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="/dashboards/boards/boards.php" class="nav-link active" aria-current="page">
                            Tableaux
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/dashboards/members/members.php" class="nav-link">
                            Membres
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/dashboards/settings/settings.php" class="nav-link">
                            Paramètres
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-column flex-shrink-0 p-3">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <h4 class="h4">Vos tableaux :</h4>
                    </li>
                    <?php foreach ($boards as $board) : ?>
                        <li class="nav-item">
                            <a href="/dashboards/dashboard.php?boardID=<?php echo $board['boardID']; ?>" class="a">
                                <?php echo sanitize_input($board['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- ici on affiche les tableaux que le user possède -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <h4 href="" class="h4">Autres projets :</h4>
                        <!-- ici on affiche les tableaux que le user fait partie -->
                    </li>
                </ul>
            </div>
        </div>