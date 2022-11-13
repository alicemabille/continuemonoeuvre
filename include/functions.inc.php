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
function active_page(string $page) : string{
    $uri = $_SERVER['REQUEST_URI'];
    $uri_arr = explode("?",$uri);
    $current_page = $uri_arr[0];
    if($current_page=="/".$page){
        return "active";
    } else {
        return "";
    }
}


function send_signup_mail(string $user_email, string $username) : string {
    require_once "config-mail.inc.php";
    try {
        // SMTP configuration
        $mailer = new PHPMailer(true); // true enables Exception
        //$mailer->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
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
        // $mailer->addBCC($mail_username, 'webmaster');
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
        // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        // $mail->addAttachment('path/to/file.pdf', 'file.pdf');
        $mailer->send();
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: '. $mailer->ErrorInfo;
    }
}

/**
 * 
 */
function check_signup() : string {
    if(isset($_POST["username"])&&(!empty($_POST["username"]))
    &&isset($_POST["email"])&&(!empty($_POST["email"]))
    &&isset($_POST["tel"])&&(!empty($_POST["tel"]))
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
        $user_tel = $_POST["tel"];
        $birthdate = $_POST["birthdate"];
        $password = $_POST["password"];

        spl_autoload_register(function ($classe) {
            include('classes/'. $classe .'.class.php');
        });

        $user = new Utilisateur($username, $user_email, $user_tel, $birthdate, $password);
        
        if ($user->check_username() && $user->check_mail() && $user->check_num() && $user->check_naissance() && $user->check_mdp()) {
            $user->addToDatabase();
            $verifKey = $user->__getCleVerification();
            send_signup_mail($user_email, $username);
            return "Votre compte a bien été créé. Un mail de confirmation vous a été envoyé.";
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
            if ($session->is_active_account()) {
                // Le compte a été validé par mail
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['session'] = true;

                //Redirection
                $isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
	            $isset_txt_category = isset($_GET["txt_category"])&&!empty($_GET["txt_category"]);
                if($isset_txt&&$isset_txt_category){
                    //Redirection vers un texte
                    header('Location: lecture.php?txt_id='.$_GET["txt_id"].'&txt_category='.$_GET["txt_category"]);
                }
                else {
                    // Redirection vers la page d'accueil
                    header("Location: index.php");
                }
            } else {
                $err = "Veuillez confirmer votre inscription dans le mail que nous vous avons envoyé avant de vous connecter";
            }
        } else {
            $err = "Nom d'utilisateur ou mot de passe incorrect";
        }
    }
    return $err;
}



const MAX_TXT_PREVIEW_LENGTH = 1000;
const MAX_POEM_LENGTH = 20;

/**
 * Preview of the given file on the homepage when no user is connected.
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 * @return string html containing cropped story
 */
function txt_preview(string $filename, ?string $category="novel") : string {
    $filepath = "text-examples/".$filename.".txt";
    if(file_exists($filepath)){
        $txt = file_get_contents($filepath);
        if(strlen($txt) >  MAX_TXT_PREVIEW_LENGTH) {
            $txt = substr($txt, 0, MAX_TXT_PREVIEW_LENGTH);
            $alignment = "left";
        }
        $txt = "<p>".$txt;
        if($category=="novel"){
            $txt = str_replace("\n\n","</p><p>",$txt);
        }
        else if($category=="poem"){
            $nbbr = substr_count($txt,"\n");
            if($nbbr >  MAX_POEM_LENGTH) {
                $txt = substr($txt, 0, MAX_POEM_LENGTH*11);
            }
            $txt = str_replace("\n\n","</p><p>",$txt);
            $txt = str_replace("\n","</br>",$txt);
            $alignment = "center";
        }
        else{
            return "Catégorie de texte inconnue.";
        }
        if($_SESSION['session']==true){
            $txt = "<article class=\" text-".$alignment." text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded shadow\"> \n\t\t\t\t<h3>".
            ucfirst($filename)."</h3>\n\t\t\t\t"
            .$txt."...</p><a href=\"lecture.php?txt_id=".$filename."&txt_category=".$category."\" class=\"btn btn-info\" role=\"button\">Lire la suite</a> \n\t\t\t </article> \n";
            return $txt;
        }
        $txt = "<article class=\" text-".$alignment." text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded shadow\"> \n\t\t\t\t<h3>".
        ucfirst($filename)."</h3>\n\t\t\t\t"
        .$txt."...</p><a href=\"connexion.php?txt_id=".$filename."&txt_category=".$category."\" class=\"btn btn-info\" role=\"button\">Lire la suite</a> \n\t\t\t </article> \n";
        return $txt;
        
    }
    return "Ce texte n'existe pas.";
}

/**
 * Transforms full given .txt file into a pretty html piece of code. To be used when user is connected.
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 * @return string html containing full story
 */
function txt_full(string $filename, ?string $category="novel") : string {
    $filepath = "text-examples/".$filename.".txt";
    if(file_exists($filepath)){
        $txt = file_get_contents($filepath);
        $txt = "<p>".$txt;
        if($category=="novel"){
            $txt = str_replace("\n\n","</p><p>",$txt);
            $alignment = "left";
        }
        else if($category=="poem"){
            $txt = str_replace("\n\n","</p><p>",$txt);
            $txt = str_replace("\n","</br>",$txt);
            $alignment = "center";
        }
        else{
            return "Unknown text category.";
        }
        $txt = "<article class=\" text-".$alignment." bg-secondary text-white p-4 m-1 rounded shadow\"> \n\t\t\t\t<h3>".
            ucfirst($filename)."</h3>\n\t\t\t\t"
            .$txt."...</p> \n\t\t\t </article> \n";
        return $txt;
    }
    return "Ce texte n'existe pas.";
}

function modif_db_ddl(string $requests) {
    require('../conf/connexionbd.conf.php');

    $mysqli = new mysqli($host, $username, $password, $database, $port);
    $result = $mysqli->multi_query($requests);
    if (!$result) {
        echo $mysqli->error;
    }
    $mysqli->close();
}

?>