<?php
define("DS", DIRECTORY_SEPARATOR);
$chemin_sous_dossier =  __DIR__ . DIRECTORY_SEPARATOR . "sous_dossier" . DS;
$chemin_sous_function =  __DIR__ . DIRECTORY_SEPARATOR . "function" . DS;
define("BASE_URL", "");
define("DEV_MODE", false); // permet d'envoiée les erreur quand c'est true, attention a mettre a false quand le site est ONLINE
// if (defined('DEV_MODE') && DEV_MODE === true)
// {
//     echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
// }
// dirname(__DIR__,1).DIRECTORY_SEPARATOR. "config.php"; permet de remonter d'un sous dossier
