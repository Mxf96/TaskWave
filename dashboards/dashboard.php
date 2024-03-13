<?php
require '../includes/inc-top-dashboard.php';
require_once '../managers/dashboard-manager.php';
require_once '../includes/inc-db-connect.php';
require '../managers/sanitize_input-manager.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $query = "SELECT boardID, title FROM boards WHERE userID = ? ORDER BY boardID ASC";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$userID]);
    $boards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $boards = [];
}

$lists = [];

// Ajout du traitement du formulaire de création de tableau
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['boardTitle'])) {
    $boardTitle = $_POST['boardTitle'];
    $boardDescription = $_POST['boardDescription'] ?? ''; // Utilisez l'opérateur null coalescent si le champ peut être vide

    // Appel à la fonction pour créer un nouveau tableau et récupération de l'ID du tableau créé
    $newBoardID = createNewDashboard($dbh, $boardTitle, $boardDescription, $userID);

    if ($newBoardID) {
        // Redirection vers la page du tableau nouvellement créé
        header("Location: http://taskwave.local/dashboards/dashboard.php?boardID=$newBoardID");
        exit; // Assurez-vous d'appeler exit après une redirection pour arrêter l'exécution du script
    } else {
        echo "<p>Erreur lors de la création du tableau.</p>";
    }
}

if (isset($_GET['boardID']) && !empty($_GET['boardID'])) {
    $boardID = $_GET['boardID'];
    $query = "SELECT * FROM boards WHERE boardID = ? AND userID = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$boardID, $userID]);
    $boardDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    $lists = getListsByBoardID($dbh, $boardID);
}

$boardID = getCurrentBoardID();

$boardDetails = getBoardDetails($dbh, $boardID);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['listTitle']) && isset($_GET['boardID']) && !empty($_GET['boardID'])) {
    $listTitle = $_POST['listTitle'];
    $success = createNewList($dbh, $listTitle, $boardID);

    if ($success) {
        $lists = getListsByBoardID($dbh, $boardID);
    } else {
        echo "<p>Erreur lors de la création de la liste.</p>";
    }
}

// Ajout du traitement du formulaire de création de tâche
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['taskTitle']) && isset($_POST['listID'])) {
    $taskTitle = $_POST['taskTitle'];
    $listID = $_POST['listID']; // Vous devez ajouter un champ caché pour listID dans votre formulaire
    $success = createNewTask($dbh, $taskTitle, $listID);

    if ($success) {
        // Optionnel : message de succès ou redirection
    } else {
        echo "<p>Erreur lors de la création de la tâche.</p>";
    }
}

// Après avoir récupéré les tableaux possédés par l'utilisateur
$memberBoards = getUserMemberBoards($dbh, $userID);

?>
<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3 bg-light">
            <div class="d-flex flex-column flex-shrink-0 p-3">
                <a href="/dashboards/dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <h6 class="fs-4">Espace de travail TaskWave</h6>
                </a>
                <hr>
                <div class="d-flex justify-content-around">
                    <a href="../boards/boards.php" class="btn btn-primary">
                        Tableaux
                    </a>
                    <a href="../members/members.php" class="btn btn-primary">
                        Membres
                    </a>
                    <a href="../settings/settings.php" class="btn btn-primary">
                        Paramètres
                    </a>
                </div>
            </div>
            <div class="d-flex flex-column flex-shrink-0 p-1">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <h4 class="h4">Vos tableaux :</h4>
                    </li>
                    <?php foreach ($boards as $board) : ?>
                        <li class="nav-item">
                            <a href="/dashboards/dashboard.php?boardID=<?php echo $board['boardID']; ?>" class="a border-bottom pb-1">
                                <?php echo sanitize_input($board['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <h4 class="h4">Autres projets :</h4>
                    </li>
                    <?php foreach ($memberBoards as $board) : ?>
                        <li class="nav-item">
                            <a href="/dashboards/dashboard.php?boardID=<?php echo sanitize_input($board['boardID']); ?>" class="a border-bottom pb-1">
                                <?php echo sanitize_input($board['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Main Content -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <?php if (!isset($_GET['boardID']) || $_GET['boardID'] === '') : ?>
                        <a class="navbar-brand" href="#">Bienvenue sur votre tableau de bord TaskWave</a>
                    <?php else : ?>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <?php if (isset($boardDetails) && $boardDetails !== false) : ?>
                                    <a class="nav-link active" aria-current="page" href="#">
                                        <span style="font-weight: bold; font-size: large;">Tableau :</span>
                                        <span style="font-style: italic; font-size: small;"><?php echo sanitize_input($boardDetails['title']); ?></span>
                                    </a>
                                    <a class="nav-link active" aria-current="page" href="#">
                                        <span style="font-weight: bold; font-size: large;">Description :</span>
                                        <span style="font-style: italic; font-size: small;">
                                            <?php
                                            $description = sanitize_input($boardDetails['description']);
                                            echo (mb_strlen($description) > 20) ? mb_substr($description, 0, 20) . "..." : $description;
                                            ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary buttonMember" data-bs-toggle="modal" data-bs-target="#addMemberModal"><a href="../members/members.php" class="text-white" style="text-decoration: none;">Ajouter un membre</a></button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-4">
                        <div class="p-4 listForm">
                            <form method="POST" action="dashboard.php?boardID=<?php echo htmlspecialchars($_GET['boardID']); ?>">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Créer une nouvelle liste" id="listTitle" name="listTitle" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary mb-3">Créer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="row">
                <?php if (isset($_GET['boardID']) && !empty($_GET['boardID'])) : ?>
                    <?php if (!empty($lists)) : ?>
                        <?php foreach ($lists as $list) : ?>
                            <?php $tasks = getTasksByListID($dbh, $list['listID']);
                            ?>
                            <div class="col-md-4 p-4">
                                <div class="card list-card" style="width: 100%;">
                                    <div class="card-body card-content d-flex justify-content-between">
                                        <span>
                                            <span class="badge bg-success"><?php echo sanitize_input($list['position']); ?></span>
                                            <h6 class="card-title d-inline"><?php echo sanitize_input($list['title']); ?></h6>
                                        </span>
                                        <div class="dropdown">
                                            <a class="btn bg-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <li><a class="dropdown-item" href="#">Modifier</a></li>
                                                <li><a class="dropdown-item" href="delete-list.php?listID=<?php echo htmlspecialchars($list['listID']); ?>">Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="p-2">
                                            <?php if (!empty($tasks)) : ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($tasks as $task) : ?>
                                                        <li class="list-group-item border-bottom pb-2"><?php echo sanitize_input($task['title']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else : ?>
                                                <p>Aucune tâche pour cette liste.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="p-2 taskForm">
                                            <form method="POST" action="dashboard.php?boardID=<?php echo sanitize_input($_GET['boardID']); ?>&listID=<?php echo sanitize_input($list['listID']); ?>">
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="text" class="form-control" placeholder="Nouvelle tâche" id="taskTitle" name="taskTitle" required>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="submit" class="btn btn-primary mb-3">Créer</button>
                                                    </div>
                                                    <input type="hidden" name="listID" value="<?php echo $list['listID']; ?>">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="col-12">Aucune liste disponible pour le moment.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Create Board Modal -->
    <div class="modal fade" id="createBoardModal" tabindex="-1" aria-labelledby="createBoardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBoardModalLabel">Créer un nouveau tableau</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="boardTitle" class="form-label">Titre du tableau</label>
                            <input type="text" class="form-control" id="boardTitle" name="boardTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="boardDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="boardDescription" name="boardDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../includes/inc-bottom-dashboard.php'; ?>