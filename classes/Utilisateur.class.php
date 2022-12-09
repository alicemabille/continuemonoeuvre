<?php
    class Utilisateur {
        private string $nomUtilisateur;
        private string $mailUtilisateur;
        private string $numTelUtilisateur;
        private string $naissanceUtilisateur;
        private string $mdpChiffUtilisateur;
        private string $cleVerificationUtilisateur;
        private bool $compteActifUtilisateur;

        public function __construct(string $nomUtilisateur) {
            $this->nomUtilisateur = $nomUtilisateur;
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);

            $query = "SELECT * FROM utilisateur WHERE nom_utilisateur = ?;";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $this->nomUtilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $this->mailUtilisateur = $row['mail_utilisateur'];
                $this->numTelUtilisateur = $row['num_tel_utilisateur'];
                $this->naissanceUtilisateur = $row['naissance_utilisateur'];
                $this->mdpChiffUtilisateur = $row['mdp_chiff_utilisateur'];
                $this->cleVerificationUtilisateur = $row['cle_verification_utilisateur'];
                $this->compteActifUtilisateur = $row['compte_actif_utilisateur'];
                $stmt->close();
            }
            $mysqli->close();
        }

        public static function inscription(string $nomUtilisateur, string $mailUtilisateur, string $naissanceUtilisateur, string $mdpUtilisateur, string $numTelUtilisateur = '', bool $compteActifUtilisateur = false):string {
            $res = "";
            $nomUtilisateur = htmlspecialchars($nomUtilisateur);
            $mailUtilisateur = htmlspecialchars($mailUtilisateur);
            $naissanceUtilisateur = htmlspecialchars($naissanceUtilisateur);
            $mdpUtilisateur = htmlspecialchars($mdpUtilisateur);
            $numTelUtilisateur = htmlspecialchars($numTelUtilisateur);

            // Génération d'une clé de vérification à la création du compte
            $cleVerificationUtilisateur = md5(microtime(TRUE) * 1000);

            // Vérification des champs saisis et message en cas d'erreur
            if (self::checkUsername($nomUtilisateur) && self::checkMail($mailUtilisateur) && self::checkNaissance($naissanceUtilisateur) && self::checkMdp($mdpUtilisateur) && self::checkNum($numTelUtilisateur)) {
                if (self::addToDatabase($nomUtilisateur, $mailUtilisateur, $naissanceUtilisateur, $mdpUtilisateur, $numTelUtilisateur, $cleVerificationUtilisateur)) {
                    $res = $cleVerificationUtilisateur;
                    // if (self::sendSignupMail($mailUtilisateur, $nomUtilisateur, $cleVerificationUtilisateur)) {
                    //     $res = "<p class='alert alert-primary mt-2'>Votre compte a bien été créé. Un mail de confirmation vous a été envoyé.</p>";
                    // }
                }
            }
            return $res;
        }

        private static function addToDatabase(string $nomUtilisateur, string $mailUtilisateur, string $naissanceUtilisateur, string $mdpUtilisateur, string $numTelUtilisateur, string $cleVerificationUtilisateur):bool {
            $res = false;
            $mdpChiffUtilisateur = password_hash($mdpUtilisateur, PASSWORD_DEFAULT);

            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);

            $query = "INSERT INTO utilisateur(nom_utilisateur, mail_utilisateur, num_tel_utilisateur, naissance_utilisateur, mdp_chiff_utilisateur, cle_verification_utilisateur)
                        VALUES (?, ?, ?, ?, ?, ?);";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ssssss", $nomUtilisateur, $mailUtilisateur, $numTelUtilisateur, $naissanceUtilisateur, $mdpChiffUtilisateur, $cleVerificationUtilisateur);
                $stmt->execute();
                $res = true;
                $stmt->close();
            }
            $mysqli->close();
            return $res;
        }

        // private static function sendSignupMail(string $mailUtilisateur, string $nomUtilisateur, string $cleVerificationUtilisateur):bool {
        //     $res = false;
        //     require_once "include/config-mail.inc.php";
        //     try {
        //         // SMTP configuration
        //         $mailer = new PHPMailer(true); // true enables Exception
        //         //$mailer->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        //         $mailer->isSMTP();
        //         $mailer->CharSet = "utf-8";
        //         $mailer->Host = $mail_host;
        //         $mailer->Port = $mail_port;
        //         $mailer->SMTPAuth = true; // just try false to see Exception
        //         $mailer->Username = $mail_username;
        //         $mailer->Password = $mail_password;
        //         $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        
        //         // the mail
        //         $mailer->setFrom($mail_username, 'Continue mon œuvre');
        //         $mailer->addReplyTo($mail_username, 'Continue mon œuvre');
        //         $mailer->addAddress($mailUtilisateur, $nomUtilisateur); // le destinataire
        //         $mailer->addCC($mail_username, 'webmaster');
        //         // $mailer->addBCC($mail_username, 'webmaster');
        //         $mailer->Subject = 'Bienvenue sur Continue Mon Œuvre';
        //         $mailer->isHTML(true);
        //         $mailContent =
        //         "
        //         <!DOCTYPE HTML>
        //         <html>
        //             <head>
        //                 <title>Bienvenue sur Continue mon œuvre</title>
        //             </head>
        //             <body style='font-family: Arial;margin:0; text-align:center; background:#f7f8f9; height:100%;'>
        //                 <h1>Bonjour ".$nomUtilisateur." !</h1>
        //                 <p>Vous venez de créer un compte sur <a href='https://continuemonoeuvre.alwaysdata.net/'>Continue Mon Œuvre</a>.</p> 
        //                 <p>Vous pouvez dès maintenant lire les ouvrages créés par la communauté et écrire à votre tour.</p>
        //                 <p>Pour confirmer votre inscription, c'est ici : </p>
        //                 <a href='continuemonoeuvre.alwaysdata.net/verification.php?user=". urlencode($nomUtilisateur) ."&key=". urlencode($cleVerificationUtilisateur) ."'>lien</a>
        //                 <p>À bientôt !</p>
        //             </body>
        //         </html>";
        //         $mailer->Body = $mailContent;
        //         // $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        //         // $mail->addAttachment('path/to/file.pdf', 'file.pdf');
        //         $mailer->send();
        //         $res = true;
        //     } catch (Exception $e) {
        //         echo 'Message could not be sent. Mailer Error: '. $mailer->ErrorInfo;
        //     }
        //     return $res;
        // }

        private function connectionToDatabase():object {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            return $mysqli;
        }
        
        private static function checkUsername(string $nomUtilisateur):bool {
            $res = false;
            $err = "";
            if (strlen($nomUtilisateur) >= 5 && strlen($nomUtilisateur) <= 20) {
                if (ctype_alnum($nomUtilisateur)) {
                    // $mysqli = $this->connectionToDatabase();
                    require 'conf/connexionbd.conf.php';
                    $mysqli = new mysqli($host, $username, $password, $database, $port);
                    $query = "SELECT COUNT(*) FROM utilisateur WHERE nom_utilisateur = ?;";
                    $stmt = $mysqli->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param("s", $nomUtilisateur);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $fetch = $result->fetch_assoc();
                        $stmt->close();
                        if ($fetch['COUNT(*)'] == 0) {
                            $res = true;
                        } else {
                            $err = "<p class='alert alert-danger mt-2'>Ce nom est déjà pris par un autre utilisateur.</p>";
                        }
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Un problème est survenu lors de la création du compte.</p>";
                    }
                    $mysqli->close();
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre nom d'utilisateur ne peut contenir que des caractères alphanumériques.</p>";
                }
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre nom d'utilisateur doit contenir entre 5 et 20 caractères.</p>";
            }
            echo $err;
            return $res;
        }

        private static function checkMail(string $mailUtilisateur):bool {
            $res = false;
            $err = "";
            if (filter_var($mailUtilisateur, FILTER_VALIDATE_EMAIL)) {
                require 'conf/connexionbd.conf.php';
                $mysqli = new mysqli($host, $username, $password, $database, $port);
                // $mysqli = $this->connectionToDatabase();
                $query = "SELECT COUNT(*) FROM utilisateur WHERE mail_utilisateur = ?;";
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("s", $mailUtilisateur);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fetch = $result->fetch_assoc();
                    $stmt->close();
                    if ($fetch['COUNT(*)'] == 0) {
                        $res = true;
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Cette adresse mail a déjà été utilisé pour un autre compte.</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Un problème est survenu lors de la création du compte.</p>";
                }
                $mysqli->close();
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre adresse mail est invalide.</p>";
            }
            echo $err;
            return $res;
        }

        private static function checkNum(string $numTelUtilisateur):bool {
            $res = false;
            $err = "";
            if (strlen($numTelUtilisateur) == 10) {
                if (ctype_digit($numTelUtilisateur)) {
                    if (substr($numTelUtilisateur, 0, 2) == "06" || substr($numTelUtilisateur, 0, 2) == "07") {
                        $res = true;
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone est invalide</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone ne peut contenir que des chiffres.</p>";
                }
            } else if (strlen($numTelUtilisateur) == 0) {
                $res = true; // numéro de téléphone pas obligatoire
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone doit être composé de 10 chiffres.</p>";
            }
            echo $err;
            return $res;
        }

        private static function checkNaissance(string $naissanceUtilisateur):bool {
            $res = false;
            $err = "";
            $format = "Y-m-d"; // AAAA-MM-JJ
            $date = date($format);
            $date100 = date($format, strtotime("-100 years"));
            $dt = DateTime::createFromFormat($format, $naissanceUtilisateur);

            if ($dt->format($format) == $naissanceUtilisateur) { // Date donnée au bon format AAAA-MM-JJ
                if ($naissanceUtilisateur < $date) { // naissance < aujourd'hui
                    if ($naissanceUtilisateur > $date100) { // naissance > 100 ans avant
                        $res = true;
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Votre date de naissance est invalide.</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre date de naissance est invalide.</p>";
                }
            } else {
                $err = "<p class='alert alert-danger mt-2'>Format de date invalide.</p>";
            }
            echo $err;
            return $res;
        }

        private static function checkMdp($mdpUtilisateur):bool {
            $res = false;
            $err = "";
            if ((strlen($mdpUtilisateur) >= 8) && (strlen($mdpUtilisateur) <= 20)) { // minimum 8 caractères et maximum 20 caractères
                if (preg_match('/[a-z]/', $mdpUtilisateur)) { // au moins 1 min
                    if (preg_match('/[A-Z]/', $mdpUtilisateur)) { // au moins 1 MAJ
                        if (preg_match('/[0-9]/', $mdpUtilisateur)) { // au moins 1 chiffre 
                            $res = true;
                        } else {
                            $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 chiffre.</p>";
                        }
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 majuscule.</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 minuscule.</p>";
                }
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 8 caractères.</p>";
            }
            echo $err;
            return $res;
        }

        // Fonctions :
            // Changement de mot de passe
            // Affichage de la page profil -> functions.inc.php pour profil
            // Array de tous les ids textes écrits par l'utilisateur -> functions.inc.php pour profil
            // Array de tous les ids textes modifiés par l'utilisateur
            // Array de tous les ids de réactions faites par l'utilisateur
            // Nombre de modifications faites sur un texte donné

        
        public function changeMdp(string $nouveauMdp):string {
            if ((strlen($nouveauMdp) >= 8) && (strlen($nouveauMdp) <= 20)) { // minimum 8 caractères et maximum 20 caractères
                if (preg_match('/[a-z]/', $nouveauMdp)) { // au moins 1 min
                    if (preg_match('/[A-Z]/', $nouveauMdp)) { // au moins 1 MAJ
                        if (preg_match('/[0-9]/', $nouveauMdp)) { // au moins 1 chiffre 
                            // $res = true;
                            $this->__setMdpChiff(password_hash($nouveauMdp, PASSWORD_DEFAULT));




                        } else {
                            $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 chiffre.</p>";
                        }
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 majuscule.</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 minuscule.</p>";
                }
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 8 caractères.</p>";
            }
        }
        

        public function __getNom():string {
            return $this->nomUtilisateur;
        }

        public function __getMail():string {
            return $this->mailUtilisateur;
        }

        public function __getNumTel():string {
            return $this->numTelUtilisateur;
        }

        public function __getNaissance():string {
            return $this->naissanceUtilisateur;
        }

        public function __getMdpChiff():string {
            return $this->mdpChiffUtilisateur;
        }

        public function __getCleVerification():string {
            return $this->cleVerificationUtilisateur;
        }

        public function __getCompteActif():bool {
            return $this->compteActifUtilisateur;

        }

        public function __setMail(string $mailUtilisateur):void {
            $this->mailUtilisateur = $mailUtilisateur;
        }

        public function __setNumTel(string $numTelUtilisateur):void {
            $this->numTelUtilisateur = $numTelUtilisateur;
        }

        public function __setNaissance(string $naissanceUtilisateur):void {
            $this->naissanceUtilisateur = $naissanceUtilisateur;
        }

        // MOT DE PASSE OUBLIÉ
        public function __setMdpChiff(string $mdpChiffUtilisateur):void {
            $this->mdpChiffUtilisateur = $mdpChiffUtilisateur;
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "UPDATE utilisateur SET mdp_chiff_utilisateur = ? WHERE nom_utilisateur = ?;";

            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ss", $mdpChiffUtilisateur, $this->nomUtilisateur);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }

        public function __setCleVerification(string $cleVerificationUtilisateur):void {
            $this->cleVerificationUtilisateur = $cleVerificationUtilisateur;
        }

        public function __setCompteActif(string $compteActifUtilisateur):void {
            $this->compteActifUtilisateur = $compteActifUtilisateur;
        }
    }
?>
