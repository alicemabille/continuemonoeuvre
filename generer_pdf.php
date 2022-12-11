<?php
    session_start();
    if (isset($_SESSION['session']) && !empty($_SESSION['session']) && $_SESSION['session']==true){//user connceted ?
        if (isset($_POST["txt_id_pdf"]) && !empty($_POST["txt_id_pdf"])){
            require("include/pdf.inc.php");
            if (genere_ok($_POST["txt_id_pdf"])){//txt_id_pdf exists ?
                genere_pdf($_POST["txt_id_pdf"]);//yes we can generate the pdf doc
            }else {//Error message the id doesn't exist
                $type = "article:author";
                $titre = "Générer un pdf";
                include "include/header.inc.php";
                echo "<main style='background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;'>
                <div class='container mt-1'><aticle><section><h2>ERREUR</h2><p class='alert alert-warning'>Cette id n'existe pas. <a href='https://continuemonoeuvre.alwaysdata.net/'>Retour vers la page d'accueil</a></p></section></article></div></main> <?php include './include/footer.inc.php'; ?></body></html>";
            }
        } else {//error message txt_id_pdf is empty 
            $type = "article:author";
            $titre = "Générer un pdf";
            include "include/header.inc.php";
            echo "<main style='background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;'>
            <div class='container mt-1'><aticle><section><h2>ERREUR</h2><p class='alert alert-warning'>Cette page n'existe pas. <a href='https://continuemonoeuvre.alwaysdata.net/'>Retour vers la page d'accueil</a></p></section></article></div></main><?php include './include/footer.inc.php'; ?></body></html>";
        }
    }else{//user isn't connected
        $type = "article:author";
        $titre = "Générer un pdf";
        include "include/header.inc.php";
        echo "<main style='background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;'>
        <div class='container mt-1'><aticle><section><h2>ERREUR</h2><p class='alert alert-warning'>Veuillez vous connecter pour accéder à cette page. <a href='https://continuemonoeuvre.alwaysdata.net/connexion.php'>Me connecter</a></p></section></article></div></main><?php include './include/footer.inc.php'; ?></body></html>"; 
    }
?>