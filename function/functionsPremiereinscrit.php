<?php
// echo '<pre>' . print_r($args, true) . '</pre>';

// LANCER UNE ALERTE POUR VALIDER L'INSCRIPTION


// CE QUI RESTE A FAIRE

//uti_compte_active: Valeur booléenne par defaut à 1 (pour le moment on active le compte dès sa création).

// uti_code_activation: Une valeur fixe de 5 caractères facultative.

// modifier le mdp si l'utilisateur souhaite changer son mdp

$TableauxRegles = [
    "validation" => [
        "min" => 5,
        "max" => 6,
        "requis" => "",
        "nomDB" => "uti_code_activation",
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
    if (isset($_POST["verificationCode"])) {
        $args = [];
        foreach ($TableauxRegles as $nomChamp => $champ) {

            if (isset($_POST[$nomChamp])) // Si il y a une donnée qui correspond a la valeur du tableau(Nom,Prenom,Email....) alors rentre ici
            {
                $postChamp = $_POST[$nomChamp];
                $champNettoyer = netoyageCharactere($postChamp);
                $args["valeurNetoyee"][$nomChamp] = $champNettoyer;

                // echo '<pre>' . print_r($args["valeurNetoyee"], true) . '</pre>';

                $erreur = envoie_erreur($champNettoyer, $nomChamp, $TableauxRegles, $args);

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
        if (!isset($args["erreurs"])) { // si tableau erreur ne contient rien, 

            echo "REUSSI";
            $donnee = uti_enligne("verif_connexion");
            unset($_SESSION["verif_connexion"]);

            // MISE A JOUR DB POUR METTRE COMPTE ACTIVE A 1 
            // LUI ADRESSER LA BONNE SESSION DONNEE

            $resultat['uti_compte_active'] === 0;
            connecter_uti("donnee", $donnee);
            header("Location: Profil.php");
            // $args["valeurNetoyee"]["activation"] = coderepeat();
            // echo '<pre>' . print_r($args, true) . '</pre>';
            $args = [];
        }
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
                return "Votre $key doit etre de minimum $valeur caractere";
            }
            if ($regle == "max" && maximum($champNettoyer, $valeur)) {
                return "Votre $key doit etre de maximum $valeur caractere";
            }
            // if (!is_numeric($champNettoyer)) {
            //     return "Votre $key doit etre composé que de chiffre";
            // }
            if (changement_en_chiffre($champNettoyer) !== changement_en_chiffre($_POST["codebase"])) {
                return "Votre $key n'est pas bon";
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
