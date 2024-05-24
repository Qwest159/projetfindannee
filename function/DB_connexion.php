<?php

// la base de donne enregistre avec majuscule et minuscule?



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

    $nomDuServeur = "localhost";
    $nomUtilisateur = "root";
    $motDePasse = "";
    $nomBDD = "projet_fin_php";

    try {
        // Instancier une nouvelle connexion.
        $pdo = new PDO("mysql:host=$nomDuServeur;dbname=$nomBDD;charset=utf8", $nomUtilisateur, $motDePasse);

        // Définir le mode d'erreur sur "exception".
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    // Capturer les exceptions en cas d'erreur de connexion :
    catch (\PDOException $e) {
        // Afficher les potentielles erreurs rencontrées lors de la tentative de connexion à la base de données.
        // Attention, les informations affichées ici pouvant être sensibles, cet affichage est uniquement destiné à la phase de développement.
        echo "Erreur d'exécution de requête : " . $e->getMessage() . PHP_EOL;
    }
};
function donnée_du_serveur()
{
    try {
        // Instancier la connexion à la base de données.
        $pdo = connexion();

        // Cette requête interroge la table "t_utilisateur_uti" afin de retourner tous les utilisateurs.
        $requete = "SELECT * FROM utilisateur";

        // La méthode "query()" est utilisée pour exécuter une requête SQL qui retourne un jeu de résultats, ce qui est le cas des requêtes "SELECT".
        // La méthode retourne "false" si la requête n'a rien trouvé.
        $stmt = $pdo->query($requete);

        // La méthode "fetchAll()" permet de récupérer tous les éléments issues de la requête sous forme de tableau.
        // Le paramètre "PDO::FETCH_ASSOC" permet de préciser que l'on désire obtenir des tableaux associatifs (nomColonne => valeur) plutôt que des objets.
        // La méthode retourne un tableau vide si la requête n'a rien trouvé.
        // Dans ce contexte, la requête retournera un tableau dans lequel chaque élément correspond à un utilisateur
        // et où chaque utilisateur sera représenté par un tableau associatif comprenant toutes les informations de l'utilisateur.
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
            $requete = "INSERT INTO utilisateur(uti_pseudo, uti_email,uti_motdepasse,uti_code_activation) VALUES (:pseudo, :email,:mdp,:codeactivation)";

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

function donnee_identique($donnee, $table)
{
    try {
        // Instancier la connexion à la base de données.
        $pdo = connexion();
        $requete = "SELECT * FROM utilisateur WHERE $table = :donnee";
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
        // $estvalide sera toujour true sauf s' il y une erreur dans le requete sql( exemple un nom de table mal ecrit)

        // Récupérer l'utilisateur issu de la requête DE LA DB.
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($utilisateur) && !empty($utilisateur)) {
            // retourne true si il y a des données
            return true;
        } else {
            // retourne false si il n'y a pas de données
            return false;
        }
    }
}





function connexionDB($args)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            // Instancier la connexion à la base de données.
            $pdo = connexion();
            $requete = "SELECT * FROM utilisateur WHERE (uti_pseudo = :pseudo OR 
            uti_email = :pseudo)";

            // Préparer la requête SQL.
            $stmt = $pdo->prepare($requete);

            // Lier les variables aux marqueurs :

            $stmt->bindValue(':pseudo', $args["identifiant"], PDO::PARAM_STR);


            // $stmt->bindValue(':mdp', $args["code"], PDO::PARAM_STR);
            // Exécuter la requête.
            $estValide = $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
        if (isset($estValide) && $estValide !== false) {
            // $estvalide sera toujour true sauf s' il y une erreur dans le requete sql( exemple un nom de table mal ecrit)

            // Récupérer l'utilisateur issu de la requête DE LA DB.
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);


            //Permet de verifier si: utilisateur a une donnée et qu'elle correspond au mdp haché fourni
            if (isset($utilisateur) && !empty($utilisateur) && password_verify($args["code"], $utilisateur['uti_motdepasse'])) {
                return $utilisateur;
            } else {
                false;
            }
        }
    }
}


?>