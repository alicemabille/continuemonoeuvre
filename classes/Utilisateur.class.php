<?php
    class Utilisateur {
        private string $nomUtilisateur;
        private string $mailUtilisateur;
        private string $numTelUtilisateur;
        private string $naissanceUtilisateur;
        private string $mdpChiffUtilisateur;

        public function __construct(string $nomUtilisateur, string $mailUtilisateur, string $numTelUtilisateur, string $naissanceUtilisateur, string $mdpChiffUtilisateur) {
            // Nom d'utilisateur entre 5 et 15 caractères alphanumériques
            if (strlen($nomUtilisateur) < 15 && strlen($nomUtilisateur) > 5 && ctype_alnum($nomUtilisateur)) {
                $this->nomUtilisateur = $nomUtilisateur;
                // Email valide
                if (filter_var($mailUtilisateur, FILTER_VALIDATE_EMAIL)) {
                    $this->mailUtilisateur = $mailUtilisateur;
                    // Numéro de téléphone compose de 10 chiffres
                    if (strlen($numTelUtilisateur) == 10 && is_numeric($numTelUtilisateur)) {
                        $this->numTelUtilisateur = $numTelUtilisateur;
                        // Vérifier que la date de naissance est correcte
                        $this->naissanceUtilisateur = $naissanceUtilisateur;
                        $this->mdpChiffUtilisateur = $mdpChiffUtilisateur;
                    }
                }
            }
        }

        private function connectionToDatabase():object {
            require('connexionbd.conf.php');
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
                    VALUES ('". $this->nomUtilisateur ."', '". $this->mailUtilisateur ."', '". $this->numTelUtilisateur ."', '". $this->naissanceUtilisateur ."', '".$this->mdpChiffUtilisateur ."');
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
    }
?>