<?php
    class Utilisateur {
        private string $nomUtilisateur;
        private string $mailUtilisateur;
        private string $numTelUtilisateur;
        private string $naissanceUtilisateur;
        private string $mdpUtilisateur;
        private string $cleVerificationUtilisateur;
        private bool $compteActifUtilisateur;

        public function __construct(string $nomUtilisateur, string $mailUtilisateur, string $numTelUtilisateur, string $naissanceUtilisateur, string $mdpUtilisateur, bool $compteActifUtilisateur = false) {
            // Vérification au préalable de la validité des informations (nombre de caractères, adresse valide, htmlspecialchars() etc..)
            $this->nomUtilisateur = $nomUtilisateur;
            $this->mailUtilisateur = $mailUtilisateur;
            $this->numTelUtilisateur = $numTelUtilisateur;
            $this->naissanceUtilisateur = $naissanceUtilisateur;
            $this->mdpUtilisateur = $mdpUtilisateur;
            // Génération d'une clé de vérification à la création du compte
            $this->cleVerificationUtilisateur = md5(microtime(TRUE) * 1000);
            $this->compteActifUtilisateur = $compteActifUtilisateur;
        }

        private function connectionToDatabase():object {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            return $mysqli;
        }

        /**
         * Ajoute les attributs de l'instance à la base de données utilisateur
         * @return bool Renvoie vrai si l'utilisateur à été ajouté, faux sinon
         */
        public function addToDatabase():bool {
            $res = false;
            $mdpChiffUtilisateur = password_hash($this->mdpUtilisateur, PASSWORD_DEFAULT);

            $mysqli = $this->connectionToDatabase();
            $query = "
                INSERT INTO utilisateur(nom_utilisateur, mail_utilisateur, num_tel_utilisateur, naissance_utilisateur, mdp_chiff_utilisateur, cle_verification_utilisateur, compte_actif_utilisateur)
                    VALUES ('". $this->nomUtilisateur ."', '". $this->mailUtilisateur ."', '". $this->numTelUtilisateur ."', '". $this->naissanceUtilisateur ."', '". $mdpChiffUtilisateur ."', '". $this->cleVerificationUtilisateur ."', '". $this->compteActifUtilisateur ."');
            ";
            $result = $mysqli->query($query);
            if ($result) {
                $res = true;
            } else {
                // DEBUG
                // print_r($mysqli->error_list); // tableau
                // echo $mysqli->errno ." : ". $mysqli->error;
            }
            $mysqli->close();
            return $res;
        }

        public function check_username():bool {
            $res = false;
            if (strlen($this->nomUtilisateur) >= 5 && strlen($this->nomUtilisateur) <= 20) {
                if (ctype_alnum($this->nomUtilisateur)) {
                    $mysqli = $this->connectionToDatabase();
                    $query = "
                        SELECT COUNT(*) FROM utilisateur WHERE nom_utilisateur='". $this->nomUtilisateur ."';
                    ";
                    $result = $mysqli->query($query);
                    $fetch = $result->fetch_row();
                    $mysqli->close();
                    if ($fetch[0] == 0) {
                        $res = true;
                    }
                }
            }
            return $res;
        }

        public function check_mail():bool {
            $res = false;
            if (filter_var($this->mailUtilisateur, FILTER_VALIDATE_EMAIL)) {
                $res = true;
            }
            return $res;
        }

        public function check_num():bool {
            $res = false;
            if (strlen($this->numTelUtilisateur) == 10) {
                if (ctype_digit($this->numTelUtilisateur)) {
                    if (substr($this->numTelUtilisateur, 0, 2) == "06" || substr($this->numTelUtilisateur, 0, 2) == "07") {
                        $res = true;
                    }
                }
            }
            return $res;
        }

        public function check_naissance():bool {
            $res = false;
            $format = "Y-m-d"; // AAAA-MM-JJ
            $date = date($format);
            $date100 = date($format, strtotime("-100 years"));
            $dt = DateTime::createFromFormat($format, $this->naissanceUtilisateur);

            if ($dt->format($format) == $this->naissanceUtilisateur) { // Date donnée au bon format AAAA-MM-JJ
                if ($this->naissanceUtilisateur < $date) { // naissance < aujourd'hui
                    if ($this->naissanceUtilisateur > $date100) { // naissance > 100 ans avant
                        $res = true;
                    }
                }
            }
            return $res;
        }

        public function check_mdp():bool {
            $res = false;
            if (strlen($this->mdpUtilisateur) >= 8) { // minimum 8 caractères
                if (preg_match('/[a-z]/', $this->mdpUtilisateur)) { // au moins 1 min
                    if (preg_match('/[A-Z]/', $this->mdpUtilisateur)) { // au moins 1 MAJ
                        if (preg_match('/[0-9]/', $this->mdpUtilisateur)) { // au moins 1 chiffre 
                            $res = true;
                        }
                    }
                }
            }
            return $res;
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

        public function __getMdp():string {
            return $this->mdpUtilisateur;
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

        public function __setMdpChiff(string $mdpUtilisateur):void {
            $this->mdpUtilisateur = $mdpUtilisateur;
        }

        public function __setCleVerification(string $cleVerificationUtilisateur):void {
            $this->cleVerificationUtilisateur = $cleVerificationUtilisateur;
        }

        public function __setCompteActif(string $compteActifUtilisateur):void {
            $this->compteActifUtilisateur = $compteActifUtilisateur;
        }
    }
?>
