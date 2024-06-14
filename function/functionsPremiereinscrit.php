<?php
$TableauxRegles = [
    "validation" => [
        "min" => 5,
        "max" => 5,
        "requis" => "",
        "nomDB" => "uti_code_activation",
    ]
];

$TableauxRegles2 = [
    "email" => [
        "min" => 2,
        "max" => 250,
        "requis" => "",
        "nomDB" => "uti_email",
        "type" => "email",
    ]
];

function generercode()
{
    $nombres = rand(0, 9);
    for ($i = 1; $i <= 4; $i++) {
        $nombres .= rand(0, 9);
    }
    return $nombres;
}


function coderepeat()
{
    $codeactivation = generercode();
    while (donnee_identique($codeactivation, "uti_code_activation")) {
        $codeactivation =  generercode();
    }
    return $codeactivation;
};

// CONDITIONS finale pour envoier l'email ou la requete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["codebase"])) {
    $args = [];
    foreach ($TableauxRegles as $nomChamp => $champ) {

        if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;

            $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles, $args);
            // si la function renvoie quelque chose, alors mets le dans le tableau qui correspond au nom du champs
            if (isset($erreur)) {
                $args["erreurs"][$nomChamp] = $erreur;
            }
        } else {
            //SI le nom de l'input n'est pas le meme  => erreur autre contient"champs inconnu"
            $args["erreurs"]["autre"] = "champs inconnu";
        }
    }
    if (!isset($args["erreurs"])) {
        // si tableau erreur ne contient rien, 
        $donnee = uti_enligne("verif_connexion");
        mise_a_jour($donnee["uti_id"], "uti_compte_active", 1);
        $args = [];
        connecter_uti("donnee", $donnee);
        header("Location: Profil.php");
    }
}



// email pour réenvoier l'email
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["renvoihidden"])) {
    emailcode(uti_enligne("verif_connexion"));
    $message["codeactive"] = "Le courriel a été envoyé avec succès sur votre adresse. ";
}

// -------------LES REGLES POUR LES ERREURS----------

function maximum($donnée, $maximum)
{
    $longueur_mot_max = mb_strlen($donnée, 'UTF-8');
    return ($longueur_mot_max > $maximum) ? true : false;
}

// fonction qui recoit une $donnée et verifier si sa taille est de $minimum
function minimum($donnée, $minimum)
{
    $longueur_mot_min = mb_strlen($donnée, 'UTF-8');
    return ($longueur_mot_min < $minimum) ? true : false;
}

function ConfirmationMdp($mdp, $mdpconfirmation)
{
    return ($mdp === $mdpconfirmation) ? false : true;
}
function changement_en_chiffre($string)
{
    return  (int)$string;
}

//-------------REGLE POUR NETTOYAGE-----------

function mdphackage($champNettoyer)
{
    return password_hash($champNettoyer, PASSWORD_DEFAULT);
}

function netoyageCharactere($donnee)
{
    $donnee = trim($donnee);
    $donnee =  htmlspecialchars($donnee);
    return $donnee;
}

//------------REGLES POUR VERIFIER S'IL Y A DES ERREURS------------
function envoie_erreur($champNettoyer, $key, $TableauxRegles, $args)
{
    foreach ($TableauxRegles[$key] as $regle => $valeur) {
        // verifie si la variable contien quelque chose et qu'il existe une regle avec requis
        if (empty($champNettoyer) || !isset($champNettoyer)) {
            if ($regle == "requis") {
                return "Votre $key est manquant";
            }
        } else {
            if ($regle == "min" && minimum($champNettoyer, $valeur)) {
                return "Votre $key doit être de maximum $valeur caractère";
            }
            if ($regle == "max" && maximum($champNettoyer, $valeur)) {
                return "Votre $key doit être de maximum $valeur caractère";
            }
            if ($regle == "nomDB" && ($valeur == "uti_email")) {
                if (!donnee_identique($champNettoyer, $valeur)) {
                    return "Votre $key n'est pas correct";
                }
            }
            if ($regle == "type" && $valeur == "email") {
                if (!(filter_var($champNettoyer, FILTER_VALIDATE_EMAIL))) {
                    return "Votre $key n'est pas valide";
                }
            }
        }
    }
}
function mise_a_jourDB($args)
{
    try {
        $pdo = connexion();

        $requete = "UPDATE t_utilisateur_uti SET (uti_pseudo = :nouveauPseudo OR uti_email = :nouveauPseudo ) WHERE uti_id = :idUtilisateur";
        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(':nouveauPseudo', $_POST['utilisateur_nouveau_pseudo'], PDO::PARAM_STR);
        $stmt->bindValue(':idUtilisateur', $_POST['utilisateur_id'], PDO::PARAM_INT);

        $stmt->bindValue(':nouveauPseudo', $args["valeurNetoyee"]["Pseudo"], PDO::PARAM_STR);
        $stmt->bindValue(':idUtilisateur', $_POST['utilisateur_id'], PDO::PARAM_INT);


        $stmt->execute();
    } catch (\PDOException $e) {
        gerer_exceptions($e);
    }
}
