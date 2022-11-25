<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

/**
 * Finds nth occurrence of a string in a string.
 * @param string $haystack the string to searh in
 * @param string $needle the string to search for
 * @param int $n
 * @return int the position of the nth occurence of $needle in $haystack
 */
function strnpos(string $haystack, string $needle, int $n) : int {
    return strpos($haystack, $needle, ($n > 1) ? strnpos($haystack, $needle, $n - 1) + strlen($needle) : 0);
}

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


function send_signup_mail(string $user_email, string $username, string $verifKey) : string {
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
        return "";
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

        spl_autoload_register(function ($classe) {
            include('classes/'. $classe .'.class.php');
        });

        $user = new Utilisateur($username, $user_email, $birthdate, $password, $user_tel);
        
        if ($user->checkUsername() && $user->checkMail() && $user->checkNum() && $user->checkNaissance() && $user->checkMdp()) {
            $user->addToDatabase();
            $verifKey = $user->__getCleVerification();
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
                    header('Location: lecture.php?txt_id='.$_GET["txt_id"].'&txt_category='.$_GET["txt_category"]);
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
 * Renvoie les id des derniers textes modifiés
 * @return array(string) les id des textes
 */
function last_modified_txts_ids() : array {
    require('conf/connexionbd.conf.php');
    $mysqli = new mysqli($host, $username, $password, $database, $port);
    $query = "
        SELECT date_ecrit, id_texte FROM ecrire
        JOIN texte ON ecrire.id_ecrit = texte.id_texte
        ORDER BY date_ecrit DESC;
    ";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        //$stmt->bind_param("i", $this->idTexte);
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

const MAX_TXT_PREVIEW_LENGTH = 1000;
const MAX_POEM_LENGTH = 20;

/**
 * Preview of the given file on the homepage when no user is connected.
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 * @return string html containing cropped story
 */
function txt_preview(string $filename, ?string $title="", ?string $category="novel") : string {
    if(empty($title)){
        $title = ucfirst($filename);
    }
    $filepath = "text-examples/".$filename.".txt";
    if(file_exists($filepath)){
        $txt = file_get_contents($filepath);
        if($category=="poem"){
            $txt = "<pre class='p-4 text-left'>".$txt;
            /*$nbbr = substr_count($txt,"\n");
            if($nbbr >  MAX_POEM_LENGTH) {
                $txt = substr($txt, 0, MAX_POEM_LENGTH*11);
            }*/
            $txt = substr($txt, 0, strnpos($txt, "\n\n", 2));
            $txt .= strnpos($txt, "\n\n", 2);
            $txt .= "</pre>";
        }
        else if($category=="haiku"){
            $txt = "<pre class='p-4 text-center'>".$txt;
            $txt = substr($txt, 0, strnpos($txt, "\n\n", 4));
            $txt .= "</pre>";
        }
        else if($category=="novel"){
            $txt = substr($txt, 0, strpos($txt, "\n\n"));
            $txt = "<p class='p-4 text-left'>".str_replace("\n\n","</p><p>",$txt)."</p>";
        }
        else{
            return "Catégorie de texte inconnue.";
        }
        if($_SESSION['session']==true){
            $href = "lecture.php";
        }
        $txt = "<article class=\"text-preview col bg-secondary text-white px-0 m-3 rounded shadow\"> \n\t\t\t\t".
                "<h3 class='p-2'>".$title."</h3>\n\t\t\t\t"
                .$txt."<div class='preview-text-image container-fluid p-5 mb-0 bg-image' style=\"background-image: url(".first_pixabay($title).");\">
                <a href=\"".$href."?txt_id=".$filename."&txt_category=".$category."\" class=\"btn btn-info\" role=\"button\">Lire la suite</a></div> \n\t\t\t
                </article> \n";
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
function txt_full(string $filename, ?string $category="novel", ?string $title="") : string {
    if(empty($title)){
        $title = ucfirst($filename);
    }
    $filepath = "text-examples/".$filename.".txt";
    if(file_exists($filepath)){
        $txt = file_get_contents($filepath);
        return txt_display($txt, $title, $category);
    }
    return "Ce texte n'existe pas.";
}

/**
 * Preview of the end given file (used on the edit page).
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 * @return string html containing cropped story
 */
function txt_end(string $filename, ?string $category="novel", ?string $title="") : string {
    if(empty($title)){
        $title = ucfirst($filename);
    }
    $filepath = "text-examples/".$filename.".txt";
    if(file_exists($filepath)){
        $txt = file_get_contents($filepath);

        //crop the text and style accordingly to category
        if(strlen($txt) >  MAX_TXT_PREVIEW_LENGTH) {
            $txt = substr($txt, -MAX_TXT_PREVIEW_LENGTH);
        }
        if($category=="novel"){
            $txt = str_replace("\n\n","</p><p>",$txt);
            $txt = "<p>...".$txt."</p>";
            $alignment = "left";
        }
        else if($category=="haikus"){
            $nbbr = substr_count($txt,"\n");
            if($nbbr >  MAX_POEM_LENGTH) {
                $txt = substr($txt, -MAX_POEM_LENGTH*11);
            }
            $txt = "<pre>...".$txt."</pre>";
            $alignment = "center";
        }
        else if($category=="poem") {
            $nbbr = substr_count($txt,"\n");
            if($nbbr >  MAX_POEM_LENGTH) {
                $txt = substr($txt, -MAX_POEM_LENGTH*11);
            }
            $txt = "<pre>...".$txt."</pre>";
            $alignment = "left";
        }
        else{
            return "Catégorie de texte inconnue.";
        }

        //styling the cropped result
        if($_SESSION['session']==true){
            $txt = "<article class=\" text-".$alignment." bg-secondary text-white p-4 m-1 rounded shadow\"> \n\t\t\t\t<h3>".
            $title."</h3>\n\t\t\t\t"
            .$txt."\n\t\t\t </article> \n";
            return $txt;
        }
        $txt = "<article class=\" text-".$alignment." text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded shadow\"> \n\t\t\t\t<h3>".
        $title."</h3>\n\t\t\t\t"
        .$txt."<a href=\"connexion.php?txt_id=".$filename."&txt_category=".$category."\" class=\"btn btn-info\" role=\"button\">Contribuer à ce texte</a> \n\t\t\t </article> \n";
        return $txt;
        
    }

    //text or category not found
    return "Ce texte n'existe pas.";
}

function txt_display(string $txt, string $title, ?string $category="novel") : string {
    if($category=="novel"){
        $txt = "<p>".$txt;
        $txt = str_replace("\n\n","</p><p>",$txt);
        $txt .= "</p>";
        $alignment = "left";
    }
    else if($category=="haiku"){
        $txt = "<pre>".$txt."</pre>";
        $alignment = "center";
    }
    else if($category=="poem"){
        $txt = "<pre>".$txt."</pre>";
        $alignment = "left";
    }
    else{
        return "Unknown text category.";
    }
    $txt = "<article class=\"text-".$alignment." bg-secondary text-white p-4 m-1 rounded shadow\"> \n\t\t\t\t<h3>".
        ucfirst($title)."</h3>\n\t\t\t\t"
        .$txt."\n\t\t\t </article> \n";
    return $txt;
}

function check_text_edit() : void {
    if(isset($_GET["txt_id"])&&(!empty($_GET["txt_id"])))
    {
        if(isset($_POST["editor-textArea"])&&(!empty($_POST["editor-textArea"]))){
            edit_text($_GET["txt_id"], $_POST["editor-textArea"]);
        }
        else echo "<p class='alert alert-primary mt-2'>Could not get text area content.</p>";
    }
    else echo "<p class='alert alert-primary mt-2'>L'id du texte à modifier n'a pas pu être récupéré.</p>";
}

function edit_text(string $text_id, string $text_to_add) : void {
    $filename = "text-examples/".$text_id.".txt";
    if (is_writable($filename)) {
        // The file pointer is at the bottom of the file hence
        // that's where $somecontent will go when we fwrite() it.
        if (!$fp = fopen($filename, 'a')) {
            echo "<p class='alert alert-primary mt-2'>Cannot open file ($filename)</p>";
            exit;
        }

        // Write $somecontent to our opened file.
        if (fwrite($fp, $text_to_add) === FALSE) {
            echo "<p class='alert alert-primary mt-2'>Cannot write to file ($filename)</p>";
            exit;
        }

        echo "<p class='alert alert-primary mt-2'>Votre contribution a bien été prise en compte !.</p>";

        fclose($fp);

    } else {
        echo "The file $filename is not writable";
    }
    //$file = fopen("text-examples/".$text_id.".txt",'r');
    //$temp_file = fopen("text-examples/".$text_id."_temp.txt",'w');

    //fclose($file);
    //fclose($temp_file);
    //rename("text-examples/".$text_id."_temp.txt", "text-examples/".$text_id.".txt");
}

include "api-keys.inc.php";
define("RESULTS_MAX",20);

/**
 * Décoder JSON vidéos pour API pixabay
 */
function decode_json_videos(string $d) : object{
    $url='https://pixabay.com/api/videos/?key='.PIXABAY_API_KEY.'&q='.$d;
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
}

/**
 * Décoder JSON photos pour API pixabay
 */
function decode_json_photos(string $d) : object{
    $url='https://pixabay.com/api/?key='.PIXABAY_API_KEY.'&q='.$d.'&image_type=photo';
    $json=file_get_contents($url);
    $data = json_decode($json);
    return $data;
}

/**
 * Préparer le string pour l'url
 */
function explorer(string $requete) : string{
    $exp = explode(" ", $requete);
    $def_req="";
    for ($n=0;$n<sizeof($exp);$n++){
        if ($n == count($exp)-1){
        $def_req .=$exp[$n];
        } else {
        $def_req .=$exp[$n]."+"; 
        }
    }
    return $def_req;
}

/**
 * Renvoie l'url de la première image résultante de la recherche pour $q
 */
function first_pixabay(string $q) : string {
    $def_q = explorer($q);
    $datas = decode_json_photos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        return "AUCUNE PHOTOS NE CORRESPOND À VOTRE RECHERCHE";
    } else {
        $photos = $datas->hits;
        return $photos[0]->webformatURL;
    }
}

/**
 * Renvoie l'url de la première image en bonne qualité résultante de la recherche pour $q
 */
function first_pixabay_fullhd(string $q) : string {
    $def_q = explorer($q);
    $datas = decode_json_photos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        return "AUCUNE PHOTOS NE CORRESPOND À VOTRE RECHERCHE";
    } else {
        $photos = $datas->hits;
        return $photos[0]->fullHDURL;
    }
}

/**
 * Avoir les vidéos demandées par l'user
 */
function get_videos(string $q) : string{
    $str="";
    $def_q = explorer($q);
    $datas = decode_json_videos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        $str="AUCUNE VIDÉOS NE CORRESPOND À VOTRE RECHERCHE";
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
 * Avoir les photos demandées par l'user
 */
function get_images(string $q) : string{
    if(isset($_GET["txt_id"])&&!empty($_GET["txt_id"])){
        $form = 'method="post" action="lecture.php?txt_id='.$_GET["txt_id"].'"';
    }
    $str='<div id="pixabay_img_list" class="row">';
    $def_q = explorer($q);
    $datas = decode_json_photos($def_q);
    $nbr = $datas->totalHits;
    if ($nbr == 0){
        $str="AUCUNE PHOTOS NE CORRESPOND À VOTRE RECHERCHE";
        return $str;
    } else {
        $photos = $datas->hits;
        for ($i=0;$i<RESULTS_MAX;$i+=2){
        //$photo_page = $photos[$i]->pageURL;
        $str .='<div class="preview_gif col-5 m-1">
                    <form '.$form.'>
                        <input name="txt_image" value="'.$photos[$i]->webformatURL.'" type="image" class="rounded img-fluid" src="'.$photos[$i]->webformatURL.'" alt="'.$photos[$i]->pageURL.'">
                    </form><form>
                        <input name="txt_image" value="'.$photos[$i+1]->webformatURL.'" type="image" class="rounded img-fluid" src="'.$photos[$i+1]->webformatURL.'" alt="'.$photos[$i+1]->pageURL.'">
                    </form>
                </div>';
        }
    }
    return $str.'</div>';
}

?>