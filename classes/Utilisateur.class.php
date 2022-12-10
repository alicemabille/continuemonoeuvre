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
        
        /**
         * Ajoute l'utilisateur à la base de données et crée une clé de vérification
         *
         * @param  mixed $nomUtilisateur
         * @param  mixed $mailUtilisateur
         * @param  mixed $naissanceUtilisateur
         * @param  mixed $mdpUtilisateur
         * @param  mixed $numTelUtilisateur
         * @param  mixed $compteActifUtilisateur
         * @return string la clé de vérification
         */
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
                }
            }
            return $res;
        }
        
        /**
         * addToDatabase
         *
         * @param  mixed $nomUtilisateur
         * @param  mixed $mailUtilisateur
         * @param  mixed $naissanceUtilisateur
         * @param  mixed $mdpUtilisateur
         * @param  mixed $numTelUtilisateur
         * @param  mixed $cleVerificationUtilisateur
         * @return bool
         */
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

        private function connectionToDatabase():object {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            return $mysqli;
        }
                
        /**
         * checkUsername
         *
         * @param  mixed $nomUtilisateur
         * @return bool
         */
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
        
        /**
         * checkMail
         *
         * @param  mixed $mailUtilisateur
         * @return bool
         */
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
        
        /**
         * checkNum
         *
         * @param  mixed $numTelUtilisateur
         * @return bool
         */
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
        
        /**
         * checkNaissance
         *
         * @param  mixed $naissanceUtilisateur
         * @return bool
         */
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
        
        /**
         * checkMdp
         *
         * @param  mixed $mdpUtilisateur
         * @return bool
         */
        private static function checkMdp($mdpUtilisateur):bool {
            $res = false;
            $err = "";
             // minimum 8 caractères et maximum 20 caractères, au moins 1 min, au moins 1 MAJ, au moins 1 chiffre 
            if ((strlen($mdpUtilisateur) >= 8) && (strlen($mdpUtilisateur) <= 20) && (preg_match('/[a-z]/', $mdpUtilisateur)) && (preg_match('/[A-Z]/', $mdpUtilisateur)) && (preg_match('/[0-9]/', $mdpUtilisateur))) {
                return true;
            }
            echo "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et 8 caractères.</p>";
            return false;
        }
        
        /**
         * getTextsIds
         *
         * @return array
         */
        public function getTextsIds():array {
            $tab = array();
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "SELECT * FROM ecrire WHERE nom_auteur = ?";

            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $this->nomUtilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    array_push($tab, $row['id_ecrit']);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $tab;
        }
        
        /**
         * getReactionsIds
         *
         * @return array
         */
        public function getReactionsIds():array {
            $tab = array();
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "SELECT * FROM reagir WHERE nom_auteur_reaction = ?";

            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $this->nomUtilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    array_push($tab, $row['id_texte_reaction']);
                }
                $stmt->close();
            }
            $mysqli->close();
            return $tab;
        }

        // Fonctions :
            // Changement de mot de passe
            // Affichage de la page profil -> functions.inc.php pour profil OK
            // Array de tous les ids textes écrits par l'utilisateur -> modif bd nécessaire
            // Array de tous les ids textes modifiés par l'utilisateur OK
            // Array de tous les ids de réactions faites par l'utilisateur
            // Nombre de modifications faites sur un texte donné -> modif bd nécessaire
        

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
