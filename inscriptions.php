<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_dossier . "nav.php";
$pageTitre = "Inscriptions";
$metaDescription = " ....";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "DB_connexion.php";
// echo inscriptions("pseudo","Email","mdp");
echo donnée_du_serveur();
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
        <label for="pseudo"> Votre Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Votre Pseudo" minlength="2" maxlength="255" value="<?php echo $args["valeurNetoyee"]["pseudo"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["pseudo"] ?? '' ?></p>


        <label for="temail">Adresse émail :</label>
        <input type="email" name="Email" id="temail" placeholder="Adresse @ " value="<?php echo $args["valeurNetoyee"]["Email"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["Email"] ?? '' ?></p>


        <label for="mdp">Votre mdp :</label>
        <input type="password" name="mot de passe" id="mdp" minlength="2" maxlength="72" placeholder="Votre mot de passe " value="<?php echo $args["valeurNetoyee"]["mot de passe"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["mot de passe"] ?? '' ?></p>



        <label for="mdp_confirm">Votre mdp Confirm :</label>
        <input type="password" name="Confirmation" id="mdp_confirm" minlength="2" maxlength="72" placeholder="Confirmation mot de passe " value="<?php echo $args["valeurNetoyee"]["Confirmation"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["Confirmation"] ?? '' ?></p>


        <input type="submit" value="Envoier">

    </form>

</body>

</html>