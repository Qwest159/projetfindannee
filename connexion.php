<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once  $chemin_sous_dossier . "nav.php";
$pageTitre = "Connexion base de données";
$metaDescription = "...";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "DB_connexion.php";
// echo inscritpions();
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

    <H1>Connexion</H1>

    <label for="pseudo"> Votre Pseudo :</label>
    <input type="text" name="pseudo" id="pseudo" placeholder="Votre Prénom" minlength="2" maxlength="255"> <br>

    <label for="mdp">Votre mdp :</label>
    <input type="password" name="mdp" id="mdp" minlength="2" maxlength="72">
    <br>

    <input type="submit" value="Envoier">
    <br>
    </form>

</body>

</html>