<?php
// echo '<pre>' . print_r($args, true) . '</pre>';
$TableauxRegles = [
    "Pseudo" => [
        "min" => 2,
        "max" => 255,
        "requis" => "",
    ],
    "Email" => [
        "min" => 2,
        "max" => 255,
        "requis" => "",
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
        "type" => "mdpconfirmations"
    ],
];


// CONDITIONS finale pour envoier l'email ou la requete
if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    if (!isset($args["erreurs"])) { // si tableau erreur ne contient rien, alors envoie email
        inscriptions($args);
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
            if ($regle == "type" && $valeur == "mdpconfirmations") {
                if (ConfirmationMdp($args["valeurNetoyee"]["Code"] ?? '', $champNettoyer)) {
                    return "Votre code de $key ne correspond pas";
                }
            }
        }
    }
}

//------------------EMAIL-------------
function email($args)
{
    $destinataire = "Claudy Focan <claudy.focan@dikkenek.be>";
    // Destinataire de l'email.
    $expediteur = $args["Nom"];
    $expediteur .= " " . $args["Prénom"];
    $expediteur .= " <" . $args["Email"] . ">";
    // Sujet de l'email.
    $sujet = "Le formulaire";
    $message_client = $args["Message"];

    $entete = [
        "From" => $expediteur,
        "MIME-Version" => "1.0",
        "Content-Type" => "text/html; charset=\"UTF-8\"",
        "Content-Transfer-Encoding" => "quoted-printable"
    ];

    try {
        mail($destinataire, $sujet, $message_client, $entete);
        echo "Le courriel a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "L'envoi du courriel a échoué.";
    };
}
