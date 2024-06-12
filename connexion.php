<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once  $chemin_sous_dossier . "nav.php";
$pageTitre = "Connexion Client";
$metaDescription = "...";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "DB_connexion.php";

// echo donnée_du_serveur();
// echo "<h1>Function connexion</h1>";


if (isset($_SESSION['donnee'])) {
    header("Location: Profil.php");
    exit();
}

?>
<main>
    <H1>Connexion</H1>
    <p class="confirmations_envoie"><?php require_once $chemin_sous_function . "functions_connexion.php"; ?></p>
    <form id="formulaire" action="" method="POST">


        <label for="identifiant"> Votre Pseudo ou email:</label>
        <input type="text" name="identifiant" id="identifiant" placeholder="Votre Prénom" value="">
        <p class="erreur"><?php echo $args["erreurs"]["identifiant"] ?? '' ?></p>

        <label for="mdp">Votre mdp :</label>
        <input type="password" name="code" id="code" placeholder="Votre mot de passe">
        <p class="erreur"><?php echo $args["erreurs"]["code"] ?? '' ?></p>

        <input type="submit" value="Connexion">


    </form>
    <p id="nouvinscrit"> <a href="/inscriptions.php"> Pas encore inscrit?</a></p>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>

<!-- < POINTINTERROGATION php echo $args["valeurNetoyee"]["pseudo"] ?? "" ?> -->


<!-- minlength="2" maxlength="72" -->
<!-- minlength="2" maxlength="255" -->