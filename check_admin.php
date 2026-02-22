<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['user']['admin'] != 1) {
    http_response_code(403);
    echo "Accès interdit : vous n'êtes pas administrateur.";
    exit;
}
