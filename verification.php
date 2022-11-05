<?php
    $titre = 'Vérification de compte';
    include 'include/header.inc.php';

    // Page sur laquelle l'utilisateur arrive après avoir cliqué dans le lien du mail
?>

    <h1>Vérification de votre compte</h1>

<?php
    if (isset($_GET['user']) && !empty($_GET['user']) && isset($_GET['key']) && !empty($_GET['key'])) {
        $user = $_GET['user'];
        $key = $_GET['key'];

        include 'conf/connexionbd.conf.php';
        $mysqli = new mysqli($host, $username, $password, $database, $port);
        $response = "";

        $query1 = "
            SELECT COUNT(*) FROM utilisateur WHERE nom_utilisateur='". $user ."';
        ";
        $result1 = $mysqli->query($query1);
        $occurence = $result1->nom_rows;
        if ($occurence == 1) {
            $query2 = "
                SELECT * FROM utilisateur WHERE nom_utilisateur='". $user ."';
            ";
            $result2 = $mysqli->query($query2);
            $row = $result2->fetch_assoc();
            if (!$row['compte_actif_utilisateur']) {
                if ($row['cle_verification_utilisateur'] == $key) {
                    $response = "Votre compte a été activé avec succès !";
                    $query3 = "
                        UPDATE utilisateur SET compte_actif_utilisateur=true WHERE nom_utilisateur='". $user ."';
                    ";
                } else {
                    $response = "Erreur : La clé d'activation est invalide !";
                }
            } else {
                $response = "Erreur : Votre compte a déjà été vérifié !";
            }
        } else {
            $response = "Erreur : Aucun compte n'est enregistré avec cet identifiant !";
        }
        $mysqli->close();
        echo "<p>". $response ."</p>";
    }
?>


<?php
    include "include/footer.inc.php";
?>
