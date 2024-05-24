<?php
require_once  __DIR__ .  DIRECTORY_SEPARATOR . "config.php";
require_once  $chemin_sous_dossier . "nav.php";
$pageTitre = "Connexion base de données";
$metaDescription = "...";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_function . "DB_connexion.php";

echo donnée_du_serveur();
echo "<h1>Function connexion</h1>";
require_once $chemin_sous_function . "functions_connexion.php";

if (isset(($_SESSION['donnee']))) {
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
    <main id="formconnexion"></main>
    <form action="" method="POST">
        <H1>Connexion</H1>

        <label for="identifiant"> Votre Pseudo ou email:</label>
        <input type="text" name="identifiant" id="identifiant" placeholder="Votre Prénom" value="Duduch">
        <p class="erreur"><?php echo $args["erreurs"]["identifiant"] ?? '' ?></p>

        <label for="mdp">Votre mdp :</label>
        <input type="password" name="code" id="code" placeholder="Votre mot de passe">
        <p class="erreur"><?php echo $args["erreurs"]["code"] ?? '' ?></p>

        <input type="submit" value="Connexion">

        <p class="erreur"><?php $args["valeurNetoyee"] ?? "Vous n'avez pas l'air d'etre inscrit" ?></p>
    </form>
    <p>Pas encore <a href="/inscriptions.php">inscrit?</a></p>
</body>
<!-- < POINTINTERROGATION php echo $args["valeurNetoyee"]["pseudo"] ?? "" ?> -->

</html>
<!-- minlength="2" maxlength="72" -->
<!-- minlength="2" maxlength="255" -->