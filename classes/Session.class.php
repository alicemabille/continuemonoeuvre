<?php
    class Session {
        private string $nomUtilisateur;
        private string $mdpUtilisateur;

        public function __construct(string $nomUtilisateur, string $mdpUtilisateur) {
            $this->nomUtilisateur = $nomUtilisateur;
            $this->mdpUtilisateur = $mdpUtilisateur;
        }

        public static function is_connected():string {
            return session_status() === PHP_SESSION_ACTIVE;
        }

        public function connection():bool {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT mdp_chiff_utilisateur FROM utilisateur WHERE nom_utilisateur='". $this->nomUtilisateur ."';
            ";
            $result = $mysqli->query($query);
            $fetch = $result->fetch_row();
            $mdp = $fetch[0];
                        
            $mysqli->close();
            return password_verify($this->mdpUtilisateur, $mdp);
        }

        public function is_active_account():bool {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT compte_actif_utilisateur FROM utilisateur WHERE nom_utilisateur='". $this->nomUtilisateur ."';
            ";
            $result = $mysqli->query($query);
            $fetch = $result->fetch_row();
            $active = $fetch[0];
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
