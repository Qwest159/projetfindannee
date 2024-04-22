<?php


$reglesForm = [
    // ATTENTION la key $reglesForm dois correspondre a l'input name
    "Nom" => [
        "min" => 2,
        "max" => 255,
        "requis" => true,
    ],
    "Prénom" => [
        "min" => 2,
        "max" => 255,
        "requis" => true,
    ],
    "Email" => [
        "min" => 6,
        "max" => 320,
        "requis" => true,
        "type" => "email"
    ],
    "Message" => [
        "min" => 10,
        "max" => 3000,
        "requis" => true,
    ],
];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $args = [];
    foreach ($reglesForm as $nomChamp => $champ) {
        //$nomChamp=
        if (isset($_POST[$nomChamp])) // recupere la valeur de l'input qui fait reference au Nom de chaque parametre tableau
        {
            $postChamp = $_POST[$nomChamp];
            $champNettoyer = netoyageCharactere($postChamp);
            $erreur = envoie_erreur($champNettoyer, $nomChamp, $reglesForm);
            // $erreur = envoie_erreur($champNettoyer, $reglesForm[$key]["min"], $reglesForm[$key]["max"], $key, $reglesForm[$key]["requis"]);
            // echo $erreur;
            if (isset($erreur)) {
                $args["erreurs"][$nomChamp] = $erreur;
            }
            $args["valeurNetoyee"][$nomChamp] = $champNettoyer;
        } else {
            $args["erreurs"]["autre"] = "champs inconnu";
        }
    }
    // $nom = =netoyageCharactere($_POST["nom"]);
    // $recupname=message_erreur($nom,2,255);

    // $recupprenom=message_erreur("Prénom",2,255);
    // $recupemail=message_erreur("Email",1,255);
    // $recupmessage=message_erreur("Message",10,3000);

    if (!isset($args["erreurs"])) {
        email($args["valeurNetoyee"]);
        $args = [];
    }
}

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

function netoyageCharactere($donnee)
{
    $donnee = trim($donnee);
    $donnee =  htmlspecialchars($donnee);
    return $donnee;
}

function envoie_erreur($champNettoyer, $key, $reglesForm)
{
    foreach ($reglesForm[$key] as $regle => $valeur) {
        // verifie si la variable contien qqch
        if (empty($champNettoyer) || !isset($champNettoyer)) {
            if ($regle == "requis") {
                return "Votre $key est manquant";
            }
        } else {
            // les regles du form
            if ($regle == "min" && minimum($champNettoyer, $valeur)) {
                return "Votre $key doit etre de minimum $valeur caractere";
            }
            if ($regle == "max" && maximum($champNettoyer, $valeur)) {
                return "Votre $key doit etre de maximum $valeur caractere";
            }
            if ($regle == "type" && $valeur == "email") {
                if (!(filter_var($champNettoyer, FILTER_VALIDATE_EMAIL))) {
                    return "votre $key n'est pas valide";
                }
            }
        }
    }
}

// echo '<pre>' . print_r($keys, true) . '</pre>';




// function envoie_formulaire($args)
// {
//     if ($_SERVER["REQUEST_METHOD"] === "POST") {
//         // si toutes mes données retourne true ( et donc ma fonction verif retourne true => pas d'erreur)
//         $erreursString = "";
//         foreach ($_POST as $key => $champs) {
//             $erreurs[] = $args["erreurs"][$key];
//             $erreursString = implode("", $erreurs);
//         }
//         // echo '<pre>' . print_r(($args["valeurNetoyee"]["Nom"]), true) . '</pre>';
//         // echo $erreursString;

//         if (empty($erreursString)) {
//             echo 'Message envoié';
//             // email($args["valeurNetoyee"]["Nom"], $args["valeurNetoyee"]["Prénom"], $args["valeurNetoyee"]["Email"], $args["valeurNetoyee"]["Message"]);
//             $args = [];
//         } else {
//             // si il y a des erreurs:
//             echo "Veuillez remplir tous les champs manquants ou erronées";
//         }
//     }
// }

// function erreur($donnée, $minimum, $maximum)
// {
//     if ($_SERVER["REQUEST_METHOD"] === "POST") {
//         if (empty(trim($donnée))) {
//             return true;
//         }
//         if (maximum($donnée, $maximum)) {
//             return true;
//         }
//         if (minimum($donnée, $minimum)) {
//             return true;
//         }
//     }
// }

// $affiche =  "votre $name est :" . $_POST[$name] . "\n";
// $afffiche_balise = nl2br($affiche); // permet d'obliger les utilisations balise html(<br>,\n,....) ecrit en php
// echo $afffiche_balise;


// function verif($name,$minimum,$maximum)
// {
//     if ($_SERVER["REQUEST_METHOD"] === "POST")
//     {
//         $donnée_safe=netoyageCharactere($_POST[$name]);
//         $erreur = erreur($donnée_safe,$minimum,$maximum); 
//                 if ($erreur)
//                 {
//                     // si erreur, retourne false
//                     return false;
//                 }
//                 else
//                 // S'IL N'A PLUS D'ERREUR ALORS C'EST BON
//                 {
//                     return true;
//                 }
//     }   
// }

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

    // if (mail($destinataire, $sujet, $message_client, $entete)) {
    //     echo "Le courriel a été envoyé avec succès.";
    // } else {
    //     echo "L'envoi du courriel a échoué.";
    // }
}






// -------FUNCTION EMAIL---------








// echo "Bonjour $nom <br> 
// on peut etre meilleur ami <span>$prenom</span> <br> 
//  Voici ton adress : $email <br>
//  Le commentaire est : $message " ; 


// 
// <!DOCTYPE html>
// <html lang="fr">
// <head>
//     <link rel="stylesheet" href="style.css">
// </head>
// <body>
// <p>Bonjour <span>Un peu de texte</span></p> <br> 
// on peut etre meilleur ami <span> <?= $prenom.... 
//  Voici ton adress : <?= $email.... <br>
//  Le commentaire est : <?= $message....


// </body>
// </html>
