<?php
require_once  __DIR__ . DIRECTORY_SEPARATOR . ".." .  DIRECTORY_SEPARATOR .   "config.php";
require_once $chemin_sous_function . "gestionnaire_authentification.php";
require_once $chemin_sous_dossier . "nav.php";

$pageTitre = "Contact";
$metaDescription = "Page contact du site";
require_once $chemin_sous_dossier . "header.php";
require_once $chemin_sous_function . "functions_formulaire.php";

?>
<main class="mise_en_page">
    <h1>Contact</h1>

    <p class="presentation">Un souci et vous souhaitez nous contacter ?<br> Remplissez le formulaire et nous vous répondrons au plus vite.</p>

    <p class="confirmations_envoie"><?=
                                    ($message["mail"]) ?? "";
                                    ?></p>

    <form id="formulaire" method="POST" action="">

        <label for="nom">Votre Nom :</label>
        <input type="text" name="Nom" id="nom" placeholder="Votre Nom" value="<?php echo $args["valeurNetoyee"]["Nom"] ?? '' ?>">
        <p class="erreur"> <?php echo $args["erreurs"]["Nom"] ?? '' ?></p>

        <label for="prenom"> Votre Prénom :</label>
        <input type="text" name="Prénom" id="prenom" placeholder="Votre Prénom" value="<?php echo $args["valeurNetoyee"]["Prénom"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["Prénom"] ?? '' ?></p>

        <label for="emaille">Adresse émail :</label>
        <input type="email" name="Email" id="emaille" placeholder="Adresse @ " value="<?php echo $args["valeurNetoyee"]["Email"] ?? '' ?>">
        <p class="erreur"><?php echo $args["erreurs"]["Email"] ?? '' ?></p>

        <label for="message">Votre message:</label><br><textarea name="Message" id="message" cols="30" rows="10"><?php echo $args["valeurNetoyee"]["Message"] ?? '' ?></textarea>
        <p class="erreur"><?php echo $args["erreurs"]["Message"] ?? '' ?></p>

        <input type="submit" value="Envoier">


</main>
<?php require_once $chemin_sous_dossier . "footer.php"   ?>