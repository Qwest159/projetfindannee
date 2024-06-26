<?php
function nav(string $chemin, string $nom_lien): string
{
    // mettre balise pre pour lire le $_serveur (tres pratique pour recup les données)

    // $_SERVER['REQUEST_URI'] => permet de prendre le chemin sur lequel on est(plus precisement, le chemin apres le domaine dans URL)
    if ($_SERVER['REQUEST_URI'] === $chemin) {
        $CSS = 'active'; // applique la classe active si elle correspond au chemin
    } else {
        $CSS = ''; // au sinon pas de classe
    }

    ob_start(); // mettre en tampon(en suspend) le code html comme un copier coller
?>
    <li><a class="<?php echo $CSS ?>" href="<?php echo $chemin ?>"><?php echo $nom_lien ?></a></li>
<?php
    return ob_get_clean();
}
$nav =  nav("/", "Accueil") .
    nav("/contact.php", "Contact") .
    nav("/inscriptions.php", "Inscriptions") .
    nav("/connexion.php", "Connexion");

$navprofil =  nav("/", "Accueil") .
    nav("/contact.php", "Contact") .
    nav("/Profil.php", "Profil");
ob_start();
?>
<li>
    <form id="deconnexion" method="post" action="">
        <input type="hidden" name="deconnection" value="toto">
        <input name="deco" type="submit" value="Deconnexion">
    </form>
</li>
<?php
$navprofil .= ob_get_clean();
?>