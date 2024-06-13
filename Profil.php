<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "navProfil.php";
require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Profil";
$metaDescription = "Page profil du site";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";



if (!isset($_SESSION['donnee'])) {
    header("Location: connexion.php");
    exit();
}

// echo donnée_du_serveur();
// echo "<h1>Function authentification</h1>";
// echo '<pre>' . print_r(uti_enligne("donnee"), true) . '</pre>';

unset($_SESSION["verif_connexion"]);
?>
<main>
    <h1>Bienvenue sur votre page profil</h1>

    <h2>bonjour <?= uti_enligne("donnee")['uti_pseudo'] ?> , <?= uti_enligne("donnee")["uti_email"] ?></h2>

    <h2>Vous voulez modifier vos infos?
        c'est par ici => <a href="./changement.php">Modifications de profil</a>
    </h2>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>