<?php

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
                <li>Pseudo : <?= $utilisateur['uti_pseudo'] ?>, E-mail : <?= $utilisateur['uti_email'] ?>, Mot de passe : <?= $utilisateur['uti_motdepasse'] ?> </li>
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
            $requete = "INSERT INTO utilisateur(uti_pseudo, uti_email,uti_motdepasse) VALUES (:pseudo, :email,:mdp)";

            // Préparer la requête SQL.
            $stmt = $pdo->prepare($requete);

            // Lier les variables aux marqueurs :

            $stmt->bindValue(':pseudo', $args["valeurNetoyee"]["Pseudo"], PDO::PARAM_STR);
            $stmt->bindValue(':email', $args["valeurNetoyee"]["Email"], PDO::PARAM_STR);
            $stmt->bindValue(':mdp', $args["valeurNetoyee"]["Code"], PDO::PARAM_STR);

            // Exécuter la requête.
            $stmt->execute();
        } catch (PDOException $e) {
            gerer_exceptions($e);
        }
    }
}
?>