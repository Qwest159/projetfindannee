<?php

$TableauxRegles_profil = [
    "pseudo" => [
        "min" => 2,
        "max" => 255,
        "requis" => "",
        "nomDB" => "uti_pseudo",
    ]
];

$TableauxRegles_email = [
    "email" => [
        "min" => 2,
        "max" => 250,
        "requis" => "",
        "nomDB" => "uti_email",
        "type" => "email",
    ]
];
$TableauxRegles_mdp = [
    "Code" => [
        "min" => 8,
        "max" => 72,
        "requis" => "",
    ],
    "Confirmations" => [
        "min" => 8,
        "max" => 72,
        "requis" => "",
        "type" => "mdpconfirmations",
    ],
];



function coderepeat()
{
    $codeactivation = generercode();
    while (donnee_identique($codeactivation, "uti_code_activation")) {
        $codeactivation =  generercode();
    }
    return $codeactivation;
};


// CONDITIONS finale pour envoier l'email ou la requete

// --------FONCTION POUR PROFIL------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["profil"])) {
    $args = [];
    foreach ($TableauxRegles_profil as $nomChamp => $champ) {

        if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;

            $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles_profil, $args);

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
        // si tableau erreur ne contient rien
        $message["profil"] = "Félicitation, votre profil vient de changer";
        //mettre les données de la varibale dans $donnee
        $donnee = uti_enligne("donnee");
        mise_a_jour($donnee["uti_id"], "uti_pseudo", $args["valeurNetoyee"]["pseudo"]);
        $args = [];
        // recup id pour réatribuer la vrai variable avec les bonnes données
        $donnees = recuputilisateurviaID($donnee["uti_id"]);
        connecter_uti("donnee", $donnees);
    }
}

// -----FORM POUR EMAIL-----
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["emails"])) {

    $args = [];
    foreach ($TableauxRegles_email as $nomChamp => $champ) {

        if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;


            $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles_email, $args);
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
        $message["email"] = "Félicitation, votre email vient de changer";
        $id = uti_enligne("donnee")['uti_id'];
        mise_a_jour($id, "uti_email", $args["valeurNetoyee"]["email"]);
        $donnees = recuputilisateurviaID($id);
        connecter_uti("donnee", $donnees);
        $args = [];
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["mdp"])) {
    $args = [];
    foreach ($TableauxRegles_mdp as $nomChamp => $champ) {

        if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;

            $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles_mdp, $args);

            if ($nomChamp === "Confirmations") {
                $args["valeurNetoyee"][$nomChamp] = mdphackage($champNettoyer);
            };

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
        $message["mdp"] = "Félicitation, votre mot de passe vient de changer";
        // si tableau erreur ne contient rien 
        $donnee = uti_enligne("donnee");
        mise_a_jour($donnee["uti_id"], "uti_motdepasse", $args["valeurNetoyee"]["Confirmations"]);
        $args = [];
        $donnees = recuputilisateurviaID($donnee["uti_id"]);
        connecter_uti("donnee", $donnees);
    }
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
            //SI il y a une regle et que le paramettre en second est true 
            if ($regle == "min" && minimum($champNettoyer, $valeur)) {
                return "Votre $key doit être de maximum $valeur caractère";
            }
            if ($regle == "max" && maximum($champNettoyer, $valeur)) {
                return "Votre $key doit être de maximum $valeur caractère";
            }
            if ($regle == "type" && $valeur == "email") {
                if (!(filter_var($champNettoyer, FILTER_VALIDATE_EMAIL))) {
                    return "Votre $key n'est pas valide";
                }
            }
            if ($regle == "nomDB") {
                if (donnee_identique($champNettoyer, $valeur)) {
                    return "votre $key existe déja";
                }
            }
            if ($regle == "type" && $valeur == "mdpconfirmations") {
                if (ConfirmationMdp($args["valeurNetoyee"]["Code"] ?? '', $champNettoyer)) {
                    return "Votre code de $key ne correspond pas";
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
