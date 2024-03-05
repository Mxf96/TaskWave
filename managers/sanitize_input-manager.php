<?php
require_once '../includes/inc-db-connect.php';

// Fonction pour nettoyer les entrées
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}