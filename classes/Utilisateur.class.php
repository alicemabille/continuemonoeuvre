<?php
    class Utilisateur {
        private string $nomUtilisateur;
        private string $mailUtilisateur;
        private string $numTelUtilisateur;
        private string $naissanceUtilisateur;
        private string $mdpChiffUtilisateur;
        private string $cleVerificationUtilisateur;
        private bool $compteActifUtilisateur;

        public function __construct(string $nomUtilisateur, string $mailUtilisateur, string $numTelUtilisateur, string $naissanceUtilisateur, string $mdpChiffUtilisateur, bool $compteActifUtilisateur = false) {
            // Vérification au préalable de la validité des informations (nombre de caractères, adresse valide, htmlspecialchars() etc..)
            $this->nomUtilisateur = $nomUtilisateur;
            $this->mailUtilisateur = $mailUtilisateur;
            $this->numTelUtilisateur = $numTelUtilisateur;
            $this->naissanceUtilisateur = $naissanceUtilisateur;
            $this->mdpChiffUtilisateur = $mdpChiffUtilisateur;
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
            $mysqli = $this->connectionToDatabase();
            $query = "
                INSERT INTO utilisateur(nom_utilisateur, mail_utilisateur, num_tel_utilisateur, naissance_utilisateur, mdp_chiff_utilisateur)
                    VALUES ('". $this->nomUtilisateur ."', '". $this->mailUtilisateur ."', '". $this->numTelUtilisateur ."', '". $this->naissanceUtilisateur ."', '".$this->mdpChiffUtilisateur ."', '". $this->cleVerificationUtilisateur ."', '". $this->compteActifUtilisateur ."');
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

        public function __setMdpChiff(string $mdpChiffUtilisateur):void {
            $this->mdpChiffUtilisateur = $mdpChiffUtilisateur;
        }

        public function __setCleVerification(string $cleVerificationUtilisateur):void {
            $this->cleVerificationUtilisateur = $cleVerificationUtilisateur;
        }

        public function __setCompteActif(string $compteActifUtilisateur):void {
            $this->compteActifUtilisateur = $compteActifUtilisateur;
        }
    }
?>
