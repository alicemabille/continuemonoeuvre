<?php
    class Reaction {
        private string $auteur;
        private int $texte;
        private string $url;

        public function __construct(string $auteur, int $texte, string $url) {
            $this->auteur = $auteur;
            $this->texte = $texte;
            $this->url = $url;
        }

        /**
         * Méthode statique pour ajouter une réaction (gif) à un texte. Maximum 1 réaction par utilisateur.
         * @param string $auteur Le nom de l'auteur de la réaction
         * @param int $texte L'id du texte sur lequel la réaction est faite
         * @param string $url L'URL du gif
         * @return string Message de la validation ou d'erreur
         */
        public static function ajouterReaction(string $auteur, int $texte, string $url):string {
            $res = "<p class='alert alert-danger'>Une erreur s'est produite.</p>";
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "SELECT COUNT(*) FROM reagir WHERE nom_auteur_reaction = ? AND id_texte_reaction = ?;";
            $stmt->prepare($query);
            if ($stmt) {
                $stmt->bind_param("si", $auteur, $texte);
                $stmt->execute();
                $stmt->close();
                $result = $stmt->get_result();
                $fetch = $result->fetch_assoc();
            }
            $occurrence = $fetch['count'];
            if ($occurrence == 0) {
                // On ajoute la réaction au texte
                if (self::verifUrl($url)) {
                    $query = "INSERT INTO reagir(nom_auteur_reaction, id_texte_reaction, url_reaction) VALUES (?, ?, ?);";
                    $stmt = $mysqli->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param("sis", $auteur, $texte, $url);
                        $stmt->execute();
                        $stmt->close();
                        $res = "<p class='alert alert-success'>Réaction ajoutée.</p>";
                    }
                } else {
                    $res = "<p class='alert alert-danger'>URL du GIF fourni incorrect.</p>";
                }
            } else {
                $res = "<p class='alert alert-danger'>Impossible d'ajouter une seconde réaction au même texte.</p>";
            }
            $mysqli->close();
            return $res;
        }

        /**
         * Vérifie que l'URL du gif transmise est correcte
         * @param string $url L'URL du gif
         * @return bool Vrai si l'URL est correcte, faux sinon
         */
        public function verifUrl(string $url):bool {
            $prefixe = "https://media.tenor/";
            return strpos($url, $prefixe) === true;
        }

        /**
         * Supprime la réaction du texte courant de la base de données
         */
        public function suppReaction():void {
            // À appeller avant l'affichage des réactions
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "SELECT COUNT(*) FROM reagir WHERE nom_auteur_reaction = ? AND id_texte_reaction = ?;";
            $stmt->prepare($query);
            if ($stmt) {
                $stmt->bind_param("si", $auteur, $texte);
                $stmt->execute();
                $stmt->close();
                $result = $stmt->get_result();
                $fetch = $result->fetch_assoc();
            }
            $occurrence = $fetch['count'];
            if ($occurence == 1) {
                // on supprime la réaction
                $query = "DELETE FROM reagir WHERE nom_auteur_reaction = ? AND id_texte_reaction = ?;";
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("sis", $auteur, $texte);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $mysqli->close();

        }

        public function __getAuteur():string {
            return $this->auteur;
        }

        public function __getTexte():int {
            return $this->texte;
        }

        public function __getUrl():string {
            return $this->url;
        }
    }
?>