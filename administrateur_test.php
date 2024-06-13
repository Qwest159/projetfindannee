<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "nav.php";
$pageTitre = "Administrateur";
$metaDescription = "Page administrateur";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";

require_once $chemin_sous_function . "DB_connexion.php";

echo donnée_du_serveur();
echo "<h1>Function connexion</h1>";
require_once $chemin_sous_function . "functions_connexion.php";


if (($_SESSION['donnee']["uti_role"]) !== "Administrateur") {
    header("Location: Profil.php");
    exit();
}


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

?>
<main>
    <?= $codeactivation2; ?>
</main>