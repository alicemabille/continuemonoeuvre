<?php
    include 'conf/connexionbd.conf.php';
    $mysqli = new mysqli($host, $username, $password, $database, $port);

    spl_autoload_register(function ($classe) {
        include('classes/'. $classe .'.class.php');
    });

    $u = new Utilisateur('test1633', 'florent95.courtin@gmail.com', '0612345678', '2022-05-05', 'hfulbzeihfbvzeafhze', false);
    $nomUtilisateur = $u->__getNom();
    $mailUtilisateur = $u->__getMail();
    $numTelUtilisateur = $u->__getNumTel();
    $naissanceUtilisateur = $u->__getNaissance();
    $mdpChiffUtilisateur = $u->__getMdpChiff();
    $cleVerificationUtilisateur = $u->__getCleVerification();
    $compteActifUtilisateur = $u->__getCompteActif();

    $query = "
                INSERT INTO utilisateur(nom_utilisateur, mail_utilisateur, num_tel_utilisateur, naissance_utilisateur, mdp_chiff_utilisateur, cle_verification_utilisateur, compte_actif_utilisateur)
                    VALUES ('". $nomUtilisateur ."', '". $mailUtilisateur ."', '". $numTelUtilisateur ."', '". $naissanceUtilisateur ."', '".$mdpChiffUtilisateur ."', '". $cleVerificationUtilisateur ."', '". $compteActifUtilisateur ."');
            ";

    $result = $mysqli->query($query);
    if ($result) {
        $res = true;
    } else {
        // DEBUG
        print_r($mysqli->error_list); // tableau
        echo $mysqli->errno ." : ". $mysqli->error;
    }
    $mysqli->close();

?>