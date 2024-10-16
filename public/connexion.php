<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . ".." .  DIRECTORY_SEPARATOR .   "config.php";
require_once  $chemin_sous_dossier . "nav.php";
$pageTitre = "Connexion Client";
$metaDescription = "Page connexion du site";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "functions_connexion.php";



if (isset($_SESSION['donnee'])) {
    header("Location: Profil.php");
    exit();
}

?>
<main class="mise_en_page">
    <H1>Connexion</H1>
    <p class="presentation">
        Vous voilà enfin sur la dernière étape
        <br>
        Plus qu'un pas à faire et vous serez connecté !
        <br>
        Identifiez-vous ci-dessous, bien évidemment.
    </p>
    <p class="lieninscrit"><?=
                            ($message["connexion"]) ?? "";
                            ?>
    </p>

    <form id="formulaire" action="" method="POST">

        <label for="identifiant"> Votre Pseudo ou email:</label>
        <input type="text" name="identifiant" id="identifiant" placeholder="Votre identifiant" value="">
        <p class="erreur"><?php echo $args["erreurs"]["identifiant"] ?? '' ?></p>

        <label for="mdp">Votre mdp :</label>
        <input type="password" name="code" id="code" placeholder="Votre mot de passe">
        <p class="erreur"><?php echo $args["erreurs"]["code"] ?? '' ?></p>

        <input type="submit" value="Connexion">


    </form>
    <p class="pasinscrit"><a href="/inscriptions.php"> Pas encore inscrit?</a></p>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>