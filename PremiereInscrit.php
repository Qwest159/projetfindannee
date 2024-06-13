<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
if (isset($_SESSION['donnee'])) {
    require_once $chemin_sous_dossier . "navProfil.php";
} else {
    require_once $chemin_sous_dossier . "nav.php";
}
require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Activation";
$metaDescription = "page premiere inscription";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "functionsPremiereinscrit.php";


if (!isset($_SESSION['verif_connexion'])) {
    header("Location: /");
    exit();
}
?>
<main>
    <h2>Bonjour <?= uti_enligne("verif_connexion")['uti_pseudo'] ?></h2>

    <h1>Premiere inscriptions ? </h1>

    <p class="confirmations_envoie"><?=
                                    ($message["codeactive"]) ?? "";
                                    ?></p>

    <form id="formulaire" action="" method="post">
        <p>Veuillez confirmer le code ci dessous recu par email</p>
        <input type="hidden" name="codebase" value="<?= uti_enligne("verif_connexion")['uti_code_activation'] ?> ">

        <label for="validation">Entrez votre code</label>
        <input type="number" name="validation" id="">

        <p class="erreur">
            <?=
            ($args["erreurs"]["validation"]) ?? "";

            ?>
        </p>
        <input type="submit" name="verificationCode" value="Validation">
    </form>

    <form id="formulairerenvoie" method="post" action="">

        <h3>Vous n'avez pas recu de courrier?
        </h3>

        <input type="hidden" name="renvoihidden" value="toto">
        <input type="submit" name="codeperdu" value="Cliquez-ici">
    </form>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>