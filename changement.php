<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";

require_once $chemin_sous_dossier . "navProfil.php";

require_once $chemin_sous_dossier . "header.php";
$pageTitre = "Changement";
$metaDescription = "Page changement";
require_once $chemin_sous_function . "DB_connexion.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "functions_miseajour.php";


if (!isset($_SESSION['donnee'])) {
    header("Location: /");
    exit();
}
?>
<main>
    <h2>Bonjour <?= uti_enligne("donnee")['uti_pseudo'] ?>,<?= uti_enligne("donnee")['uti_email'] ?></h2>

    <h1>Changement des donnees de profil</h1>

    <p class="confirmations_envoie"><?=
                                    ($message["profil"]) ?? "";
                                    ?></p>

    <form id="formulaire" action="" method="post">
        <p>Veuillez confirmer le code ci dessous recu par email</p>
        <input type="hidden" name="profil" value="profile">

        <label for="validation">Entrez votre code</label>
        <input type="texte" name="pseudo" id="">

        <p class="erreur">
            <?=
            ($args["erreurs"]["pseudo"]) ?? "";

            ?>
        </p>
        <input type="submit" name="verificationCode" value="Validation">
    </form>

    <form id="formulairerenvoie" method="post" action="">

        <h3>Vous n'avez pas recu de courrier?
        </h3>
        <p> Remarquez votre émail ci-dessous</p>
        <input type="hidden" name="emails" value="tutu">
        <input type="texte" name="email" id="">
        <p class="erreur">
            <?=
            ($args["erreurs"]["email"]) ?? "";
            ?>
        </p>
        <input type="submit" name="codeperdu" value="Envoyer">
    </form>


    <form id="formulairerenvoie" method="post" action="">

        <input type="hidden" name="mdp" value="toto">

        <label for="mdp">Votre mdp :</label>
        <input type="password" name="Code" id="mdp" minlength="2" maxlength="72" placeholder="Votre mot de passe ">
        <p class="erreur"><?php echo $args["erreurs"]["Code"] ?? '' ?></p>



        <label for="mdp_confirm">Votre mdp Confirm :</label>
        <input type="password" name="Confirmations" id="mdp_confirm" minlength="2" maxlength="72" placeholder="Confirmation mot de passe ">
        <p class="erreur"><?php echo $args["erreurs"]["Confirmations"] ?? '' ?></p>

        <input type="submit" name="mdpchanger" value="Envoyer">


    </form>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>