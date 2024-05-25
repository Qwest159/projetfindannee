<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "nav.php";
require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Connexion base de données";
$metaDescription = "...";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "functionsPremiereinscrit.php";

echo donnée_du_serveur();
echo "<h1>Function Premiere inscriptions</h1>";
echo '<pre>' . print_r(uti_enligne("verif_connexion"), true) . '</pre>';

?>

<h2>bonjour <?= uti_enligne("verif_connexion")['uti_pseudo'] ?> , <?= uti_enligne("verif_connexion")["uti_email"] ?></h2>

<h1>Premiere inscriptions ? </h1>

<form action="" method="post">

    <input type="hidden" name="codebase" value="<?= uti_enligne("verif_connexion")['uti_code_activation'] ?> ">

    <input type="number" name="validation" id="">

    <p class="erreur"><?php echo $args["erreurs"]["validation"] ?? '' ?></p>

    <input type="submit" name="verificationCode" value="Envoier le code de verification">
</form>


<!-- <form action="" method="post">

    <input type="hidden" name="" value="formVerificationIdentitie">

    <input type="submit" value="formEnvoyerCode">
</form> -->