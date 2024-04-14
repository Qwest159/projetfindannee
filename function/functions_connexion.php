<?php
// VERIF permet de verifier si les données sont correct ( longueur correct,minusculet....)

// creer la fonction qui permet de recup les bonne données de verif
// => SI elle sont bonne garde les
// => SI TOUTE LES DONNées requise sont bonnes, alors a ce moment la tu confirme l'envoie
// => SI c'est faut, faire un message d'erreur et demander de rectifier

// htmlentities a regarder ( safe les données ()IMPORTANT)

$reglesFormDB = [
    "pseudo" => [
        "min" => 2,
        "max" => 255,
    ],
    "Email" => [
        "min" => 2,
        "max" => 255,
    ],
    "mot de passe" => [
        "min" => 8,
        "max" => 72,
    ],
    "Confirmation" => [
        "min" => 8,
        "max" => 72,
    ],
];



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $args = [];
    foreach ($_POST as $key => $champ) {
        $champNettoyer = netoyageCharactere($_POST[$key]);
        $erreur = envoie_erreur($champNettoyer, $reglesForm[$key]["min"], $reglesFormDB[$key]["max"], $key);
        $args["erreurs"][$key] = $erreur;
        $args["valeurNetoyee"][$key] = $champNettoyer;
    }
    // $nom = =netoyageCharactere($_POST["nom"]);
    // $recupname=message_erreur($nom,2,255);

    // $recupprenom=message_erreur("Prénom",2,255);
    // $recupemail=message_erreur("Email",1,255);
    // $recupmessage=message_erreur("Message",10,3000);

    echo '<pre>' . print_r($args, true) . '</pre>';
    envoie_formulaire($args);
}

function maximum($donnée, $maximum)
{
    $longueur_mot_max = mb_strlen($donnée, 'UTF-8');
    return ($longueur_mot_max > $maximum) ? true : false;
}

function minimum($donnée, $minimum)
{
    $longueur_mot_min = mb_strlen($donnée, 'UTF-8');
    return ($longueur_mot_min < $minimum) ? true : false;
}
function netoyageCharactere($donnee)
{
    return htmlspecialchars($donnee);
}

function envoie_erreur($donnée, $minimum, $maximum, $key)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // $donnée_safe=securite($_POST[$name]);
        // $donnée_safe=netoyageCharactere($_POST[$donnée]);
        if (empty(trim($donnée))) {
            return "Votre $key est manquant";
        }
        if (maximum($donnée, $maximum)) {
            return "Votre $key doit etre de maximum $maximum caractere";
        }
        if (minimum($donnée, $minimum)) {
            return "Votre $key doit etre de minimum $minimum caractere";
        }
        if ($donnée === "age") {
            if ($donnée < 18) {
                return "Votre étes mineur, vous ne pouvez pas répondre à ce questionnaire";
            }
        }
    }
}

function envoie_formulaire($args)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // si toutes mes données retourne true ( et donc ma fonction verif retourne true => pas d'erreur)
        $erreursString = "";
        foreach ($_POST as $key => $champs) {
            $erreurs[] = $args["erreurs"][$key];
            $erreursString = implode("", $erreurs);
        }
        echo '<pre>' . print_r(($args["valeurNetoyee"]["Nom"]), true) . '</pre>';
        echo $erreursString;

        if (empty($erreursString)) {
            echo 'Message envoié';
            // email($args["valeurNetoyee"]["Nom"], $args["valeurNetoyee"]["Prénom"], $args["valeurNetoyee"]["Email"], $args["valeurNetoyee"]["Message"]);
            $args = [];
        } else {
            // si il y a des erreurs:
            echo "Veuillez remplir tous les champs manquants ou erronées";
        }
    }
}
