<?php 

/**
 * Echos "active" if the page passed as a parameter is the current page. Useful with bootstrap for a cool nav menu.
 * @param page : the page you want to know is active or not.
 */
function active_page(string $page) : void{
    $uri = $_SERVER['REQUEST_URI'];
    $uri_arr = explode("?",$uri);
    $current_page = $uri_arr[0];
    if($current_page=="/".$page){
        echo "active";
    }
}


function check_signup() : void{
    if(isset($_POST["username"])&&(!empty($_POST["username"]))
    &&isset($_POST["email"])&&(!empty($_POST["email"]))
    &&isset($_POST["tel"])&&(!empty($_POST["tel"]))
    &&isset($_POST["birthdate"])&&(!empty($_POST["birthdate"]))
    &&isset($_POST["password"])&&(!empty($_POST["password"]))
    &&isset($_POST["password-confirm"])&&(!empty($_POST["password-confirm"])))
    {
        if($_POST["password"] == $_POST["password-confirm"]){
            $username = $_POST["username"];
<<<<<<< Updated upstream
            $email = $_POST["email"];
            $birthdate = $_POST["birthdate"];
            $password = $_POST["password"];

=======
            $user_email = $_POST["email"];
            $user_tel = $_POST["tel"];
            $birthdate = $_POST["birthdate"];
            $password = $_POST["password"];

            // Vérification des champs ici

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            spl_autoload_register(function ($classe) {
                include('../classes'. $classe .'.class.php');
            });

            $user = new Utilisateur($username, $user_email, $user_tel, $birthdate, $hashedPassword);
            $verifKey = $user->__getCleVerification();

            // intégrer lien dans l'email avec username et la clé de vérification
            // ce lien envoie sur une page qui active le compte dans la bd avec l'attribut compteActifUtilisateur

            /*
>>>>>>> Stashed changes
            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=utf-8';

            // Additional headers
            $headers[] = 'To:<'.$email.'>';
            $headers[] = 'From: Continue Mon Œuvre <no-reply@continuemonoeuvre.alwaysdata.net>';
            
            $message = "
            <html>
                <head>
                    <title>Bienvenue sur Continue Mon Œuvre</title>
                </head>
                <body>
                    <h1>Bonjour ".$username." !</h1>
                    <p>Vous venez de créer un compte sur <a href='https://continuemonoeuvre.alwaysdata.net/'>Continue Mon Œuvre</a>. Votre mot de passe est : ".$password.".</p> 
                    <p>Vous pouvez dès maintenant lire les ouvrages créés par la communauté et écrire à votre tour.</p>
                    <p>À bientôt !</p>
                </body>
            </html>";

            mail($email, "Bienvenue sur Continue Mon Œuvre", $message, implode("\r\n", $headers));
        }
        else{
            echo "<p id='password-warning' class='alert alert-warning mt-3'>Les deux mots de passe entrés ne sont pas identiques.</p>";
        }
    }
}

const MAX_TXT_PREVIEW_LENGTH = 1000;
const MAX_POEM_LENGTH = 20;

/**
 * Echos php code for the preview of the given file on the homepage when no user is connected.
 * @param filename : name of the file containing the text to display, without ".html".
 * @param category : novel or poem. Will change the way paragraphs are defined.
 */
function txt_preview(string $filename, ?string $category="novel") : string {
    $txt = file_get_contents("text-examples/".$filename.".txt");
    if(strlen($txt) >  MAX_TXT_PREVIEW_LENGTH) {
        $txt = substr($txt, 0, MAX_TXT_PREVIEW_LENGTH);
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
    }
    else{
        return "Unknown text category.";
    }
    $txt = "<article class=\"text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded shadow\"> \n\t\t\t\t<h3>".
        ucfirst($filename)."</h3>\n\t\t\t\t"
        .$txt."...</p><a href=\"text-view.php\" class=\"btn btn-info\" role=\"button\">Lire la suite</a> \n\t\t\t </article> \n";
    return $txt;
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