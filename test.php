<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "nav.php";
$pageTitre = "Inscriptions";
$metaDescription = " ....";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "DB_connexion.php";

echo donnée_du_serveur();
echo "<h1>Function connexion</h1>";
require_once $chemin_sous_function . "functions_connexion.php";



function generercode()
{
    $nombres = rand(0, 9);
    for ($i = 1; $i <= 4; $i++) {
        $nombres .= rand(0, 9);
    }
    return $nombres;
}
$codeactivation2 = generercode();
while (donnee_identique($codeactivation2, "uti_code_activation")) {
    generercode();
}


echo $codeactivation2;
