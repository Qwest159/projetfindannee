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

unset($_SESSION["verif_connexion"]);
?>
<main class="mise_en_page">
    <h1>Bienvenue sur votre page profil</h1>

    <h2>bonjour <?= uti_enligne("donnee")['uti_pseudo'] ?> , <?= uti_enligne("donnee")["uti_email"] ?></h2>
    <h3 class="lieninscrit">Vous voulez modifier vos informations ? C'est par ici => <a href="./changement.php">Modifications de profil</a>
    </h3>
    <p class="presentation">Vous avez réussi à venir jusqu'ici sans encombre.<br>
        Juste un dernier mot pour vous : </p>
    <figure><img src="./sous_dossier/img/congratulations.jpg" alt="image accueil"></figure>




</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>