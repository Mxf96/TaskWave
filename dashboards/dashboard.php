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

if (isset($_GET['boardID']) && !empty($_GET['boardID'])) {
    $boardID = $_GET['boardID'];
    $query = "SELECT * FROM boards WHERE boardID = ? AND userID = ?";
    $stmt = $dbh->prepare($query);
    $stmt->execute([$boardID, $userID]);
    $boardDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    $lists = getListsByBoardID($dbh, $boardID);
}

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
            <!-- Main Content -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <?php if (!isset($_GET['boardID'])) : ?>
                        <a class="navbar-brand" href="#">Bienvenue sur votre tableau de bord TaskWave</a>
                    <?php endif; ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <?php if (isset($boardDetails)) : ?>
                                <a class="nav-link active" aria-current="page" href="#">
                                    <span style="font-weight: bold;">Tableau :</span>
                                    <span style="font-style: italic;"><?php echo sanitize_input($boardDetails['title']); ?></span>
                                </a>
                                <a class="nav-link active" aria-current="page" href="#">
                                    <span style="font-weight: bold;">Description :</span>
                                    <span style="font-style: italic;"><?php echo sanitize_input($boardDetails['description']); ?></span>
                                </a>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                    Ajouter un membre
                                </button>
                            <?php endif; ?>
                            <!-- Vous pouvez ajouter d'autres liens ou boutons ici selon vos besoins -->
                        </div>
                    </div>
                    <?php if (isset($_GET['boardID']) && !empty($_GET['boardID'])) : ?>
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
                    <?php endif; ?>
                </div>
            </nav>

            <div class="row">
                <?php if (isset($_GET['boardID']) && !empty($_GET['boardID'])) : ?>
                    <?php if (!empty($lists)) : ?>
                        <?php foreach ($lists as $list) : ?>
                            <?php $tasks = getTasksByListID($dbh, $list['listID']); // Récupère les tâches pour cette liste 
                            ?>
                            <div class="col-md-4 p-4"> <!-- Ajustez la classe col-md-4 selon la largeur désirée -->
                                <div class="card list-card" style="width: 100%;"> <!-- Ajustez ou supprimez style="width: 18rem;" selon le besoin -->
                                    <div class="card-body card-content">
                                        <span class="badge bg-danger"><?php echo htmlspecialchars($list['position']); ?></span>
                                        <h6 class="card-title"><?php echo htmlspecialchars($list['title']); ?></h6>
                                    </div>
                                    <div>
                                        <div class="p-2">
                                            <?php if (!empty($tasks)) : ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($tasks as $task) : ?>
                                                        <li class="list-group-item"><?php echo htmlspecialchars($task['title']); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else : ?>
                                                <p>Aucune tâche pour cette liste.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="p-2 taskForm">
                                            <form method="POST" action="dashboard.php?boardID=<?php echo htmlspecialchars($_GET['boardID']); ?>&listID=<?php echo htmlspecialchars($list['listID']); ?>">
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
    <!-- Container for the form -->
    <div class="p-4" id="formContainer">
        <form id="createDashboardForm" method="POST">
            <div class="mb-3">
                <label for="dashboardTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="dashboardTitle" name="dashboardTitle" required>
            </div>
            <div class="mb-3">
                <label for="dashboardDescription" class="form-label">Description</label>
                <textarea class="form-control" id="dashboardDescription" name="dashboardDescription" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
<?php require '../includes/inc-bottom-dashboard.php'; ?>