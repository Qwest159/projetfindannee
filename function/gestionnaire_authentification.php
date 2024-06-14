<?php

// Les configuration suivantes visent à limiter les risques de vol de session par fixation.

// Cette options passée à 1 permet au serveur de rejeter un identifiant de session qui n'aurait pas été initialisé par celui-ci.
ini_set('session.use_strict_mode', 1);

// Empêcher la récupération du cookie de session via l'URL (1 est sa valeur par défaut, mais on est jamais trop prudent).
ini_set('session.use_only_cookies', 1);



//ATTENTION secure dois mettre en false si je travaille en local
$dureDeVie = 7 * 24 * 60 * 60;
session_set_cookie_params([
    'lifetime' => $dureDeVie,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'lax'
]);
session_start();

//Création de variables de session
function connecter_uti($nom, $donnee)
{
    $_SESSION[$nom] = $donnee;
}



//Lecture de variables de session
function uti_enligne($nom)
{
    return  $_SESSION[$nom];
}

//retire la session actuelle
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["deconnection"])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
