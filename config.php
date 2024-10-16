<?php
define("DS", DIRECTORY_SEPARATOR);

$chemin_sous_dossier =  __DIR__ . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "sous_dossier" . DS;
$chemin_sous_function =  __DIR__ . DIRECTORY_SEPARATOR . "function" . DS;
define("BASE_URL", "");
define("DEV_MODE", false); // permet d'envoiée les erreur quand c'est true, attention a mettre a false quand le site est ONLINE
