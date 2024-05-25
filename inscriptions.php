<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "nav.php";
$pageTitre = "Inscriptions";
$metaDescription = " ....";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
echo
"<h1>Données du serveur</h1>";
require_once $chemin_sous_function . "DB_connexion.php";
echo donnée_du_serveur();
echo "<h1>Function Inscriptions</h1>";
require_once $chemin_sous_function . "functionsInscriptions.php";
if (isset($_SESSION['donnee'])) {
    header("Location: Profil.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <H1>inscriptions</H1>

    <form action="" method="POST">
        <label for="Pseudo"> Votre Pseudo :</label>
        <input type="text" name="Pseudo" id="pseudo" placeholder="Votre Pseudo" minlength="2" maxlength="255" value="Duduch">
        <p class="erreur"><?php echo $args["erreurs"]["Pseudo"] ?? '' ?></p>


        <label for="temail">Adresse émail :</label>
        <input type="email" name="Email" id="temail" placeholder="Adresse @ " value="dingomax3@hotmail.com">

        <p class="erreur"><?php echo $args["erreurs"]["Email"] ?? '' ?></p>


        <label for="mdp">Votre mdp :</label>
        <input type="password" name="Code" id="mdp" minlength="2" maxlength="72" placeholder="Votre mot de passe ">
        <p class="erreur"><?php echo $args["erreurs"]["Code"] ?? '' ?></p>



        <label for="mdp_confirm">Votre mdp Confirm :</label>
        <input type="password" name="Confirmations" id="mdp_confirm" minlength="2" maxlength="72" placeholder="Confirmation mot de passe ">
        <p class="erreur"><?php echo $args["erreurs"]["Confirmations"] ?? '' ?></p>


        <input type="submit" value="Envoier">

    </form>

</body>

</html>
value="<?php echo $args["valeurNetoyee"]["Pseudo"] ?? '' ?>"
value="<?php echo $args["valeurNetoyee"]["Email"] ?? '' ?>"