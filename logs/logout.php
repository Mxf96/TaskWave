<?php
require_once '../includes/inc-db-connect.php';
session_destroy();
header("Location: ../index.php");
exit();
