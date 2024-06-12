<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "navProfil.php";
require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Connexion base de données";
$metaDescription = "...";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";


if (!isset($_SESSION['donnee'])) {
    header("Location: connexion.php");
    exit();
}

// echo donnée_du_serveur();
// echo "<h1>Function authentification</h1>";
// echo '<pre>' . print_r(uti_enligne("donnee"), true) . '</pre>';
// session_start();
unset($_SESSION["verif_connexion"]);
?>
<main>
    <h1>Bienvenue sur votre page profil</h1>

    <h2>bonjour <?= uti_enligne("donnee")['uti_pseudo'] ?> , <?= uti_enligne("donnee")["uti_email"] ?></h2>


    <form id="deconnexion" method="post">

        <input name="deco" type="submit" value="Deconnexion">

    </form>
    <?php
    deconnection("donnee")
    ?>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>