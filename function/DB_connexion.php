<?php

// la base de donne enregistre avec majuscule et minuscule?
if (DEV_MODE === true) {
    $envPath = dirname(__DIR__, 1) . "/.env.local";
} else {
    $envPath = dirname(__DIR__, 1) . "/.env";
}
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);
    $lines = explode("\n", $env);
    foreach ($lines as $line) {
        preg_match("/([^#]+)\=(.*)/", $line, $matches);
        if (isset($matches[2])) {
            putenv(trim($line));
        }
    }
}
// $env = file_get_contents($envPath);


function verifdecrypt($donneeclient, $donneehash)
{
    return password_verify($donneeclient, $donneehash);
}

// Tenter d'établir une connexion à la base de données :
function gerer_exceptions(PDOException $e): void
{
    // Limiter l'affichage des erreurs au mode "développement" pour éviter le risque de communiquer des données sensibles
    // lorsqu'une erreur se produit en mode "production" (lorsque le site est en ligne) :
    if (defined('DEV_MODE') && DEV_MODE === true) {
        echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
    }
}
function connexion()
{

    // $nomDuServeur = "localhost";
    // $nomUtilisateur = "root";
    // $motDePasse = "";
    // $nomBDD = "projet_fin_php";

    try {
        // Instancier une nouvelle connexion.

        $pdo = new PDO("mysql:host=" . getenv("DBHOST") . ";dbname=" . getenv("DBNAME") . ";charset=utf8", getenv("DBUSER"), getenv("DBPASSWORD"));

        // Définir le mode d'erreur sur "exception".
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    // Capturer les exceptions en cas d'erreur de connexion :
    catch (\PDOException $e) {

        echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
    }
};

//afficher les données pour test
function donnée_du_serveur()
{
    try {
        // Instancier la connexion à la base de données.
        $pdo = connexion();


        $table = "chris_php_projet";
        $requete = "SELECT * FROM $table";

        $stmt = $pdo->query($requete);

        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        gerer_exceptions($e);
    }
    if (isset($utilisateurs) && !empty($utilisateurs)) {
        ob_start();
?>
        <ul>
            <?php
            foreach ($utilisateurs as $utilisateur) {
            ?>
                <li>Pseudo : <?= $utilisateur['uti_pseudo'] ?>, E-mail : <?= $utilisateur['uti_email'] ?>, Mot de passe : <?= $utilisateur['uti_motdepasse'] ?>, activation : <?= $utilisateur['uti_code_activation'] ?> </li>

            <?php
            }
            ?>
        </ul>
<?php
        echo ob_get_clean();
    }
}


function inscriptions($args)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            // Instancier la connexion à la base de données.
            $pdo = connexion();
            $table = "chris_php_projet";
            $requete = "INSERT INTO $table (uti_pseudo, uti_email,uti_motdepasse,uti_code_activation) VALUES (:pseudo, :email,:mdp,:codeactivation)";

            // Préparer la requête SQL.
            $stmt = $pdo->prepare($requete);

            // Lier les variables aux marqueurs :

            $stmt->bindValue(':pseudo', $args["valeurNetoyee"]["Pseudo"], PDO::PARAM_STR);
            $stmt->bindValue(':email', $args["valeurNetoyee"]["Email"], PDO::PARAM_STR);
            $stmt->bindValue(':mdp', $args["valeurNetoyee"]["Confirmations"], PDO::PARAM_STR);
            $stmt->bindValue(':codeactivation', $args["valeurNetoyee"]["activation"], PDO::PARAM_STR);

            // Exécuter la requête.
            $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
    }
}

function mise_a_jour($id, $champs, $donnee)
{
    try {
        $pdo = connexion();
        $table = "chris_php_projet";
        $requete = "UPDATE $table SET $champs = :nouveaucompteactive WHERE uti_id = :idUtilisateur";
        $stmt = $pdo->prepare($requete);
        $stmt->bindValue(':nouveaucompteactive', $donnee, PDO::PARAM_STR);
        $stmt->bindValue(':idUtilisateur', $id, PDO::PARAM_INT);

        $stmt->execute();
    } catch (\PDOException $e) {
        gerer_exceptions($e);
    }
}

//permet de verifier si une donnée existe déjà dans la base de données
function donnee_identique($donnee, $champs)
{
    try {
        // Instancier la connexion à la base de données.
        $pdo = connexion();
        $table = "chris_php_projet";
        $requete = "SELECT * FROM $table WHERE $champs = :donnee";
        // Préparer la requête SQL.
        $stmt = $pdo->prepare($requete);

        // Lier les variables aux marqueurs :

        $stmt->bindValue(':donnee', $donnee, PDO::PARAM_STR);
        // Exécuter la requête.
        $estValide = $stmt->execute();
    } catch (PDOException $e) {
        gerer_exceptions($e);
    }
    if (isset($estValide) && $estValide !== false) {
        // $estvalide sera toujour true sauf s'il y une erreur dans le requete sql( exemple un nom de table mal ecrit)

        // Récupérer l'utilisateur issu de la requête DE LA DB.
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($utilisateur) && !empty($utilisateur)) {
            // retourne true s'il y a des données
            return true;
        } else {
            // retourne false s'il n'y a pas de données
            return false;
        }
    }
}

// function pour la connexion de l'utilisateur
function connexionDB($args)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            // Instancier la connexion à la base de données.
            $pdo = connexion();
            $table = "chris_php_projet";
            $requete = "SELECT * FROM $table WHERE (uti_pseudo = :pseudo OR 
            uti_email = :pseudo)";

            // Préparer la requête SQL.
            $stmt = $pdo->prepare($requete);

            // Lier les variables aux marqueurs :

            $stmt->bindValue(':pseudo', $args["identifiant"], PDO::PARAM_STR);

            // Exécuter la requête.
            $estValide = $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
        if (isset($estValide) && $estValide !== false) {
            // $estvalide sera toujour true sauf s' il y une erreur dans le requete sql( exemple un nom de table mal ecrit)

            // Récupérer l'utilisateur issu de la requête DE LA DB.
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            //Permet de verifier si: utilisateur à une donnée et qu'elle correspond au mdp haché fourni pour se connecter
            if (isset($utilisateur) && !empty($utilisateur) && password_verify($args["code"], $utilisateur['uti_motdepasse'])) {
                //retourne les données de l'utilisateur que j'ai besoin 
                return $utilisateur;
            } else {
                false;
            }
        }
    }
}

//function recuperer les données de l'utilisateur grace à l'id de l'utilisateur
function recuputilisateurviaID($id)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            // Instancier la connexion à la base de données.
            $pdo = connexion();
            $table = "chris_php_projet";
            $requete = "SELECT * FROM $table WHERE uti_id = :iduser ";
            // Préparer la requête SQL.
            $stmt = $pdo->prepare($requete);

            // Lier les variables aux marqueurs :

            $stmt->bindValue(':iduser', $id, PDO::PARAM_STR);

            // Exécuter la requête.
            $estValide = $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
        if (isset($estValide) && $estValide !== false) {
            // $estvalide sera toujour true sauf s' il y une erreur dans le requete sql( exemple un nom de table mal ecrit)

            // Récupérer l'utilisateur issu de la requête DE LA DB.
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($utilisateur) && !empty($utilisateur)) {
                return $utilisateur;
            } else {
                false;
            }
        }
    }
}

// ----------------EMAIL-----------------

function emailcode($args)
{
    $destinataire = " <" . $args["uti_email"] . ">";;
    // Destinataire de l'email.
    $expediteur = "Site Php";
    $expediteur .= "<Duduch@hotmail.com>";
    // Sujet de l'email.
    $sujet = "Envoie du code de confirmation";
    $message_client = "Bonjour, voici votre code de confirmation :" . $args["uti_code_activation"];



    $entete = [
        "From" => $expediteur,
        "MIME-Version" => "1.0",
        "Content-Type" => "text/html; charset=\"UTF-8\"",
        "Content-Transfer-Encoding" => "quoted-printable"
    ];

    if (mail($destinataire, $sujet, $message_client, $entete)) {
        "";
    } else {
        return "L'envoi du courriel a échoué.";
    }
}
?>