<?php
    /**
     * Classe associée à l'initialisation d'une session d'un utilisateur sur le site
     */
    class Session {
        private string $nomUtilisateur;
        private string $mdpUtilisateur;
        
        /**
         * __construct
         *
         * @param  mixed $nomUtilisateur
         * @param  mixed $mdpUtilisateur
         * @return void
         */
        public function __construct(string $nomUtilisateur, string $mdpUtilisateur) {
            $this->nomUtilisateur = $nomUtilisateur;
            $this->mdpUtilisateur = $mdpUtilisateur;
        }
        
        /**
         * Checks if the username matches the password
         *
         * @return bool
         */
        public function connection():bool {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT mdp_chiff_utilisateur FROM utilisateur WHERE nom_utilisateur=?;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $this->nomUtilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                $fetch = $result->fetch_assoc();
                $mdp = $fetch['mdp_chiff_utilisateur'];
                $stmt->close();
            }
                        
            $mysqli->close();
            return password_verify($this->mdpUtilisateur, $mdp);
        }
        
        /**
         * Check if the user has activated his account using the verification key sent by email
         *
         * @return bool
         */
        public function isActiveAccount() : bool {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT compte_actif_utilisateur FROM utilisateur WHERE nom_utilisateur=?;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("s", $this->nomUtilisateur);
                $stmt->execute();
                $result = $stmt->get_result();
                $fetch = $result->fetch_assoc();
                $active = $fetch['compte_actif_utilisateur'];
                $stmt->close();
            }
            $mysqli->close();
            return $active;
        }

        public function __getNomUtilisateur():string {
            return $this->nomUtilisateur;
        }

        public function __getMdpUtilisateur():string {
            return $this->mdpUtilisateur;
        }
    }
?>
