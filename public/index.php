<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . ".." .  DIRECTORY_SEPARATOR .   "config.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_dossier . "nav.php";

$pageTitre = "Page d'accueil";
$metaDescription = "Page d'accueil du projet d'un élève Ifosup contenant un formulaire ....";
require_once $chemin_sous_dossier . "header.php";

?>

<main class="mise_en_page">
    <h1>Bienvenue chez moi</h1>
    <p class="presentation">Je suis ravi de vous accueillir sur ma plateforme dédiée à tous les passionnés et étudiants en informatique. Que vous soyez débutant ou expert, ce site est conçu pour vous montrer mes talents (ou pas) ainsi que vous fournir des outils essentiels pour réussir dans ce domaine dynamique. </p>
    <figure><img src="./sous_dossier/img/ordi.jpg" alt="image accueil"></figure>

</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>