<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";

require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Connexion base de données";
$metaDescription = "...";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "functionsPremiereinscrit.php";


if (!isset($_SESSION['verif_connexion'])) {
    header("Location: /");
    exit();
}
?>
<main>
    <h2>Bonjour <?= uti_enligne("verif_connexion")['uti_pseudo'] ?> , <?= uti_enligne("verif_connexion")["uti_email"] ?></h2>

    <h1>Premiere inscriptions ? </h1>

    <?php  ?>

    <form id="formulaire" action="" method="post">
        <p>Veuillez confirmer le code ci dessous recu par email</p>
        <input type="hidden" name="codebase" value="<?= uti_enligne("verif_connexion")['uti_code_activation'] ?> ">

        <label for="validation">Entrez votre code</label>
        <input type="number" name="validation" id="">

        <p class="erreur"><?=
                            (isset($_POST["verificationCode"])) ?  $args["erreurs"]["validation"] :  "";
                            ?>

        </p>

        <input type="submit" name="verificationCode" value="Envoiez le code">
        <h3>Vous n'avez pas recu de courrier?
            Appuyez sur le bouton ci-dessous.
        </h3>

        <input type="submit" name="codeperdu" value="formEnvoyerCode">

    </form>

</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>