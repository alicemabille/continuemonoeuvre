<?php   
    /**
     * Function to check if the id exists
     * @param int id : id of the story
     * @return bool: true equals id exists and false equals id doesn't exist
     */
    function genere_ok(int $id) : bool{
        include 'conf/connexionbd.conf.php';
        $mysqli = new mysqli($host, $username, $password, $database, $port);
        $result="";
        if ($mysqli != NULL){//Connection OK 
            $query1 = "
                SELECT COUNT(*) FROM texte WHERE id_texte=?;";
                $stmt = $mysqli->prepare($query1);
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fetchCount = $result->fetch_assoc();
                    $occurence = $fetchCount['COUNT(*)'];
                    $stmt->close();
                } else {
                    return false;
                }
            if ($result != NULL && $occurence == 1){ //id exists
                return true;
            } else {//id doesn't exist
                return false;
            } 
        $mysqli->close();
        } else {//No connection
            return false;
        }
    }

    /**
     * Function to generate a PDF
     * @param int id : id of the story
     * @return : nothing
     */
    function genere_pdf(int $id){
        include 'conf/connexionbd.conf.php';
        $mysqli = new mysqli($host, $username, $password, $database, $port);
        $response = "";
        $result= "";
        if ($mysqli != NULL){//Connexion OK 
            $query1 = "
                SELECT COUNT(*) FROM texte WHERE id_texte=?;"; 
            $stmt = $mysqli->prepare($query1);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $fetchCount = $result->fetch_assoc();
                $occurence = $fetchCount['COUNT(*)'];
                $stmt->close();
            }
            if ($result != NULL && $occurence == 1){
                $result2="";
                $query2 = "
                    SELECT * FROM texte WHERE id_texte=?;";
                $stmt2 = $mysqli->prepare($query2);
                if ($stmt2) {
                    $stmt2->bind_param("i", $id);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    $fetch2 = $result2->fetch_assoc();
                    $texte = $fetch2['contenu_texte'];
                    $title = $fetch2['titre_texte'];
                }
                //Separate string through capital letters
                $texte2 = utf8_decode($texte);
                $parties = explode("/[A-Z]/", $texte2);
                ob_start();
                require("classes/Pdf.class.php");
                $pdf = new PDF();
                $pdf->AliasNbPages();
                $pdf->SetTitle($title, false);
                for ($i = 0; $i < count($parties); $i++){
                    $n = $i +1;
                    $num = strval($n);
                    $p = "Partie ".$num;
                    $pdf->AjouterChapitre($n, $p, $parties[$i]); 
                }
                $pdf->Output('D', $title.'.pdf', false);
                ob_end_flush(); 
            } else {
            } 
        } else {
        }
    }
?>