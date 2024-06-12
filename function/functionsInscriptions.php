<?php
// echo '<pre>' . print_r($args, true) . '</pre>';

// LANCER UNE ALERTE POUR VALIDER L'INSCRIPTION


// CE QUI RESTE A FAIRE

//uti_compte_active: Valeur booléenne par defaut à 1 (pour le moment on active le compte dès sa création).

// uti_code_activation: Une valeur fixe de 5 caractères facultative.

// modifier le mdp si l'utilisateur souhaite changer son mdp

$TableauxRegles = [
    "Pseudo" => [
        "min" => 2,
        "max" => 255,
        "requis" => "",
        "nomDB" => "uti_pseudo",
    ],
    "Email" => [
        "min" => 2,
        "max" => 255,
        "requis" => "",
        "nomDB" => "uti_email",
    ],
    "Code" => [
        "min" => 8,
        "max" => 72,
        "requis" => "",
    ],
    "Confirmations" => [
        "min" => 8,
        "max" => 72,
        "requis" => "",
        "confirmations" => "mdpconfirmations"
    ],
];

function generercode()
{
    $nombres = rand(0, 9);
    for ($i = 1; $i <= 4; $i++) {
        $nombres .= rand(0, 9);
    }
    return $nombres;
}


// while (donnee_identiquecode(55450, "uti_code_activation")) {
// }
// echo generercode();

function coderepeat()
{
    $codeactivation = generercode();
    while (donnee_identique($codeactivation, "uti_code_activation")) {
        $codeactivation =  generercode();
    }
    return $codeactivation;
};










// CONDITIONS finale pour envoier l'email ou la requete
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $args = [];
    foreach ($TableauxRegles as $nomChamp => $champ) {

        if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;

            // echo '<pre>' . print_r($args["valeurNetoyee"], true) . '</pre>';

            $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles, $args);

            if ($nomChamp === "Confirmations") {
                $args["valeurNetoyee"][$nomChamp] = mdphackage($champNettoyer);
            };

            // echo '<pre>' . print_r($args, true) . '</pre>';

            // si la function renvoie quelque chose, alors mets le dans le tableau qui correspond au nom du champs
            if (isset($erreur)) {
                $args["erreurs"][$nomChamp] = $erreur;
            }
        } else {
            //SI le nom de l'input n'est pas le meme  => erreur autre contient"champs inconnu"
            $args["erreurs"]["autre"] = "champs inconnu";
        }
    }
    if (!isset($args["erreurs"])) { // si tableau erreur ne contient rien, alors envoie email
        echo "Félicitation, vous êtes inscrit";
        $args["valeurNetoyee"]["activation"] = coderepeat();
        inscriptions($args);
        // echo '<pre>' . print_r($args, true) . '</pre>';
        $args = [];
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
                return "Votre $key doit etre de minimum $valeur caractere";
            }
            if ($regle == "max" && maximum($champNettoyer, $valeur)) {
                return "Votre $key doit etre de maximum $valeur caractere";
            }
            if ($regle == "type" && $valeur == "email") {
                if (!(filter_var($champNettoyer, FILTER_VALIDATE_EMAIL))) {
                    return "Votre $key n'est pas valide";
                }
            }
            if ($regle == "confirmations" && $valeur == "mdpconfirmations") {
                if (ConfirmationMdp($args["valeurNetoyee"]["Code"] ?? '', $champNettoyer)) {
                    return "Votre code de $key ne correspond pas";
                }
            }
            if ($regle == "nomDB" && ($valeur == "uti_pseudo" or $valeur == "uti_email")) {
                if (donnee_identique($champNettoyer, $valeur)) {
                    return "Votre $key existe déja";
                }
            }
        }
    }
}


//------------------EMAIL-------------
// function email($args)
// {
//     $destinataire = "Claudy Focan <claudy.focan@dikkenek.be>";
//     // Destinataire de l'email.
//     $expediteur = $args["Nom"];
//     $expediteur .= " " . $args["Prénom"];
//     $expediteur .= " <" . $args["Email"] . ">";
//     // Sujet de l'email.
//     $sujet = "Le formulaire";
//     $message_client = $args["Message"];

//     $entete = [
//         "From" => $expediteur,
//         "MIME-Version" => "1.0",
//         "Content-Type" => "text/html; charset=\"UTF-8\"",
//         "Content-Transfer-Encoding" => "quoted-printable"
//     ];

//     try {
//         mail($destinataire, $sujet, $message_client, $entete);
//         echo "Le courriel a été envoyé avec succès.";
//     } catch (Exception $e) {
//         echo "L'envoi du courriel a échoué.";
//     };
// }
