<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . "config.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
if (isset($_SESSION['donnee']) || isset($_SESSION['verif_connexion'])) {
    require_once $chemin_sous_dossier . "navProfil.php";
} else {
    require_once $chemin_sous_dossier . "nav.php";
}

$pageTitre = "Page d'accueil";
$metaDescription = "Page d'accueil du projet d'un élève Ifosup contenant un formulaire ....";
require_once $chemin_sous_dossier . "header.php";

?>

<main>
    <h1>ACCUEIL de la page</h1>


    <p>Voici ma 1ere page d'accueil, elle est trés blanche pour le moment, malgré tous elle sera rempli de mois en mois, je l'espere :p </p>
</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>