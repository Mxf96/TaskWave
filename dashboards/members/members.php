<?php
require '../../includes/inc-top-dashboard.php';
require_once '../../includes/inc-db-connect.php';
require 'member-manger.php';
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

// Handle Add Member Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addMemberEmail'], $_POST['boardID'])) {
    $addMemberEmail = $_POST['addMemberEmail'];
    $boardID = $_POST['boardID'];
    addMemberToBoard($dbh, $addMemberEmail, $boardID, $userID);
}

// Fetch board invitations for the user
$invitations = getBoardJoinRequests($dbh, $userID);

// Fetch boards and their members
$boardsMembers = getBoardsAndMembers($dbh, $userID);
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

        <div class="col-md-9">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/dashboards/dashboard.php">Membres</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item me-2">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#notificationModal">Notifications</a>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">Ajouter un membre</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Modal for Notifications -->
            <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notificationModalLabel">Board Invitations</h5>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($invitations)) : ?>
                                <ul class="list-group">
                                    <?php foreach ($invitations as $invitation) : ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Invitation to join: <strong><?php echo htmlspecialchars($invitation['boardTitle']); ?></strong>
                                            <div>
                                                <a href="accept_invitation.php?invitationID=<?php echo $invitation['invitationID']; ?>" class="btn btn-success btn-sm">Accept</a>
                                                <a href="reject_invitation.php?invitationID=<?php echo $invitation['invitationID']; ?>" class="btn btn-danger btn-sm">Reject</a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>No pending invitations.</p>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal pour ajouter un membre -->
            <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Contenu du modal pour ajouter un membre -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../../includes/inc-bottom-dashboard.php'; ?>