<?php
    class Session {
        private string $nomUtilisateur;
        private string $mdpChiffUtilisateur;

        public function __construct(string $nomUtilisateur, string $mdpChiffUtilisateur) {
            $this->nomUtilisateur = $nomUtilisateur;
            $this->mdpChiffUtilisateur = $mdpChiffUtilisateur;
        }

        public static function is_connected():string {
            return session_status() === PHP_SESSION_ACTIVE;
        }

        public function connection():bool {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);

            $query = "
                SELECT mdp_chiff_utilisateur FROM utilisateur WHERE nom_utilisateur='". $nomUtilisateur ."';
            ";
            $result = $mysqli->query($query);
            $result->data_seek(0);
            $mdp = $result->fetch_assoc();
            $mdp = $mdp["mdp_chiff_utilisateur"];
            // bug aussi -> renvoie toujours faux
            return password_verify($mdpChiffUtilisateur, $mdp);
        }

        public function __getNomUtilisateur():string {
            return $this->nomUtilisateur;
        }
    }
?>
