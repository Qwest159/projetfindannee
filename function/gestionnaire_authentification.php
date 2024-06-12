<?php

// Les configuration suivantes visent à limiter les risques de vol de session par fixation.
// Un vol de session par fixation consiste à créer un cookie de session avec un identifiant bidon sur le navigateur d'une victime.
// Lorsque la victime se connecte à son compte sur le site ciblé, le serveur, ne reconnaissant pas cet identifiant de session, le crée et l'initialise.
// Le hacker visiste la page du site ciblé avec le même cookie de session lui permettant ainsi de profiter des privilèges de la victime :

// Cette options passée à 1 permet au serveur de rejeter un identifiant de session qui n'aurait pas été initialisé par celui-ci.
ini_set('session.use_strict_mode', 1);

// Empêcher la récupération du cookie de session via l'URL (1 est sa valeur par défaut, mais on est jamais trop prudent).
ini_set('session.use_only_cookies', 1);

$dureDeVie = 7 * 24 * 60 * 60;
session_set_cookie_params([
    'lifetime' => $dureDeVie,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'lax'
]);
session_start();

function connecter_uti($nom, $donnee)
{
    //Création de variables de session
    $_SESSION[$nom] = $donnee;
}




function uti_enligne($nom)
{
    //Lecture de variables de session
    return  $_SESSION[$nom];
}

function deconnection()
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["deco"])) {
            session_destroy();
            header("Location: index.php");
            exit();
        }
    }
}




// function direction()
// {
//     $direction = [
//         "/connexion.php" => "Profil.php",
//         "/inscriptions.php" => "Profil.php",
//         "/Profil.php" => "connexion.php"

//     ];
//     $cheminactuelle = $_SERVER['REQUEST_URI'];

//     foreach ($direction as $chemin => $value) {
//         if (isset($_SESSION['donnee']) && $cheminactuelle === $chemin) {
//             header("Location: $value");
//             exit();
//         }
//     }
// }
