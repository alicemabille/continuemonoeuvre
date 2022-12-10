<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

/**
 * Echos "active" if the page passed as a parameter is the current page. Useful with bootstrap for a cool nav menu.
 * @param page : the page you want to know is active or not.
 */
function active_page(string $page) : string {
    $uri = $_SERVER['REQUEST_URI'];
    $uri_arr = explode("?",$uri);
    $current_page = $uri_arr[0];
    if($current_page=="/".$page){
        return "active";
    } else {
        return "";
    }
}

/**
 * Sends an email to inform the user a continuemonoeuvre account was created using their email adress
 * @param string the user's email adress
 * @param string username
 * @param string a unique account-checking key that the user will have to enter to verify their account
 * @return string a success or fail message
 */
function send_signup_mail(string $user_email, string $username, string $verifKey) : string {
    require_once "config-mail.inc.php";
    try {
        // SMTP configuration
        $mailer = new PHPMailer(true); // true enables Exception
        $mailer->isSMTP();
        $mailer->CharSet = "utf-8";
        $mailer->Host = $mail_host;
        $mailer->Port = $mail_port;
        $mailer->SMTPAuth = true; // just try false to see Exception
        $mailer->Username = $mail_username;
        $mailer->Password = $mail_password;
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        // the mail
        $mailer->setFrom($mail_username, 'Continue mon œuvre');
        $mailer->addReplyTo($mail_username, 'Continue mon œuvre');
        $mailer->addAddress($user_email, $username); // le destinataire
        $mailer->addCC($mail_username, 'webmaster');
        $mailer->Subject = 'Bienvenue sur Continue Mon Œuvre';
        $mailer->isHTML(true);
        $mailContent =
        "
        <!DOCTYPE HTML>
        <html>
            <head>
                <title>Bienvenue sur Continue mon œuvre</title>
            </head>
            <body style='font-family: Arial;margin:0; text-align:center; background:#f7f8f9; height:100%;'>
                <h1>Bonjour ".$username." !</h1>
                <p>Vous venez de créer un compte sur <a href='https://continuemonoeuvre.alwaysdata.net/'>Continue Mon Œuvre</a>.</p> 
                <p>Vous pouvez dès maintenant lire les ouvrages créés par la communauté et écrire à votre tour.</p>
                <p>Pour confirmer votre inscription, c'est ici : </p>
                <a href='continuemonoeuvre.alwaysdata.net/verification.php?user=". urlencode($username) ."&key=". urlencode($verifKey) ."'>lien</a>
                <p>À bientôt !</p>
            </body>
        </html>";
        $mailer->Body = $mailContent;
        $mailer->send();
        return "";
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: '. $mailer->ErrorInfo;
    }
}

/**
 * Signs up an user (inserts them in the database) if correct signup information was entered.
 * @return string a success or fail message
 */
function check_signup() : string {
    if(isset($_POST["username"])&&(!empty($_POST["username"]))
    &&isset($_POST["email"])&&(!empty($_POST["email"]))
    &&isset($_POST["birthdate"])&&(!empty($_POST["birthdate"]))
    &&isset($_POST["password"])&&(!empty($_POST["password"]))
    &&isset($_POST["password-confirm"])&&(!empty($_POST["password-confirm"])))
    {
        if(!$_POST["password"] == $_POST["password-confirm"]){
            return "<p id='password-warning' class='alert alert-warning mt-3'>Les deux mots de passe entrés ne sont pas identiques.</p>";
        }
        $user_email = $_POST["email"];
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            return "<p id='password-warning' class='alert alert-warning mt-3'>$user_email n'est pas un adresse mail valide.</p>";
        }
        
        $username = $_POST["username"];
        $birthdate = $_POST["birthdate"];
        $password = $_POST["password"];

        //phone number is optional
        $user_tel = "";
        if(isset($_POST["tel"])&&(!empty($_POST["tel"]))){
            $user_tel = $_POST["tel"];
        }

        include('classes/Utilisateur.class.php');
        $verifKey = Utilisateur::inscription($username, $user_email, $birthdate, $password, $user_tel);
        if (strlen($verifKey) > 0) {
            send_signup_mail($user_email, $username, $verifKey);
            return "<p class='alert alert-primary mt-2'>Votre compte a bien été créé. Un mail de confirmation vous a été envoyé.</p>";
        }
    }
    return "";
}


/**
 * Starts a user session.
 * @return string An error message, empty if everything is right
 */
function check_signin() : string {
    $err = "";
    if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])) {
        spl_autoload_register(function ($classe) {
            include('classes/'. $classe .'.class.php');
        });
        $session = new Session($_POST['username'], $_POST['password']);

        if ($session->connection()) {
            // Nom utilisateur + mdp corrects
            if ($session->isActiveAccount()) {
                // Le compte a été validé par mail
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['session'] = true;

                //Redirection
                $isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
	            $isset_txt_category = isset($_GET["txt_category"])&&!empty($_GET["txt_category"]);
                if($isset_txt&&$isset_txt_category){
                    //Redirection vers un texte
                    header('Location: lecture.php?txt_id='.$_GET["txt_id"]);
                }
                else {
                    // Redirection vers la page d'accueil
                    header("Location: index.php");
                }
            } else {
                $err = "<p class='alert alert-danger mt-2'>Veuillez confirmer votre inscription dans le mail que nous vous avons envoyé avant de vous connecter</p>";
            }
        } else {
            $err = "<p class='alert alert-danger mt-2'>Nom d'utilisateur ou mot de passe incorrect</p>";
        }
    }
    return $err;
}

/**
 * Get ids from last modified texts
 * @return array ids
 */
function last_modified_txts_ids() : array {
    require('conf/connexionbd.conf.php');
    $mysqli = new mysqli($host, $username, $password, $database, $port);
    $query = "
        SELECT DISTINCT date_ecrit, id_texte FROM ecrire
        JOIN texte ON ecrire.id_ecrit = texte.id_texte
        GROUP BY id_texte
        ORDER BY date_ecrit DESC;
    ";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $array = array();
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $array[$i] = ($row["id_texte"]);
            $i++;
        }
        $stmt->close();
    }
    $mysqli->close();
    
    return $array;
}

include "api-keys.inc.php";
define("RESULTS_MAX",20);

/**
 * Gets json data from Pixabay where the videos match the keywords
 * @param string keywords
 * @return object json data containing videos
 */
function decode_json_videos(string $d) : object{
    $url='https://pixabay.com/api/videos/?key='.PIXABAY_API_KEY.'&q='.$d;
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
}

/**
 * Gets json data from Pixabay where the images match the keywords
 * @param string keywords
 * @return object json data containing images
 */
function decode_json_photos(string $d) : object {
    $url='https://pixabay.com/api/?key='.PIXABAY_API_KEY.'&q='.$d.'&image_type=photo';
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
}

/**
 * Replaces spaces with '+' (for pixabay search purposes)
 * @param string keywords separated by spaces or '+'
 * @return string keywords separated by '+'
 */
function explorer(string $requete) : string {
    $exp = explode(" ", $requete);
    $def_req  = "";
    for ($n=0;$n<sizeof($exp);$n++){
        if ($n == count($exp)-1){
        $def_req .= $exp[$n];
        } else {
        $def_req .= $exp[$n]."+"; 
        }
    }
    return $def_req;
}

/**
 * Generates html that displays videos from a selection by keyword
 * @param string keyword
 * @return string html code with videos
 */
function get_videos(string $q) : string {
    $str="";
    $def_q = explorer($q);
    $datas = decode_json_videos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        $str="<p class='alert alert-info'>Aucune vidéo ne correspond à votre recherche.</p>";
        return $str;
    } else {
        $videos = $datas->hits;
        for ($i=0;$i<RESULTS_MAX;$i++){
        $video_page = $videos[$i]->pageURL;
        $video_small = $videos[$i]->videos->small->url;
        $str .='<li>
                    <figure>
                    <video width="220" height="140" controls>
                        <source src='.$video_small.' type=video/mp4>
                    </video>
                    <figcaption><a href='.$video_page.' target="_blank">Vidéo '.$i.'</a></figcaption>
                    </figure>
                </li>';
        }
    }
    return $str;
}

/**
 * Generates html that allows the user to choose an image from a selection by keyword
 * @param string keyword
 * @return string html code with image buttons
 */
function get_images(string $q) : string {
    if(isset($_GET["txt_id"])&&!empty($_GET["txt_id"])){
        $form = 'method="post" action=""';
    }
    $str='<div id="pixabay_img_list" class="row">';
    $def_q = explorer($q);
    $datas = decode_json_photos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        $str="<p class='alert alert-info'>Aucun résultat pour ".$q.".</p>";
        return $str;
    } else {
        $photos = $datas->hits;
        for ($i=0;$i<RESULTS_MAX;$i+=2){
        $str .='<div class="preview_gif col-5 m-1">
                    <form '.$form.'>
                        <input type="hidden" name="txt_image" value="'.$photos[$i]->webformatURL.'">
                        <input type="image" src="'.$photos[$i]->webformatURL.'" alt="'.$photos[$i]->pageURL.'" class="rounded img-fluid">
                    </form><form '.$form.'>
                        <input type="hidden" name="txt_image" value="'.$photos[$i+1]->webformatURL.'" >
                        <input type="image" class="rounded img-fluid" src="'.$photos[$i+1]->webformatURL.'" alt="'.$photos[$i+1]->pageURL.'">
                    </form>
                </div>';
        }
    }
    return $str.'</div>';
}

/**
 * Function to see the profile of someone else 
 * @param user : name of the user
 * @return String : list of informations or nothing, if the user doesn't exist
*/
function get_user_infos_else(string $user) : string{
    include 'conf/connexionbd.conf.php';
    spl_autoload_register(function ($classe) {
        include('classes/'. $classe .'.class.php');
    });
    $mysqli = new mysqli($host, $username, $password, $database, $port);

    if ($mysqli == NULL) {
        return "<p class='alert alert-warning'>Cette page est indisponible pour le moment.</p><p><a href='https://continuemonoeuvre.alwaysdata.net' />Retour à la page d'accueil</a></p>";
    } 
    
    //Connexion OK 
    $query2 = "
        SELECT * FROM utilisateur WHERE nom_utilisateur='". $user ."';
    ";//Try to take informations
    $r = $mysqli->query($query2);

    if ($r == NULL) {
        return "<p class='alert alert-warning'>Le profil de ".$user." n'existe pas.</p><p><a href='https://continuemonoeuvre.alwaysdata.net' >Retour à la page d'accueil</a></p>"; 
    }
    
    //We have informations
    $query4 = "
        SELECT ecrire.id_ecrit, texte.titre_texte FROM ecrire
            JOIN texte ON texte.id_texte = ecrire.id_ecrit
            WHERE nom_auteur='".$user."';";
    $textes = $mysqli->query($query4);
    if ($textes == NULL) {
        return "<p class='alert alert-info'>Cet utilisateur n'a encore rien publié.</p>";
    } 
    //We have informations
    $i = 0;
    while ($row2 = $textes->fetch_assoc()) {
        $rows[$i] = $row2;
    }

    for ($i = 0;$i<sizeof($rows);$i++) {
        $texte = new Texte($rows[$i]["id_ecrit"]);
        $list .= $texte->txtPreviewCard();
    }

    return "<section><h2>Œuvres de ".$user."</h2>\n<div class='row'>".$list."</div></section>"; //Edit the answer  
}
?>