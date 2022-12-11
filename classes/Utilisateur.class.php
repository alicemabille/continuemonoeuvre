<?php
    /**
     * Classe associée à la manipulation d'un utilisateur du site
     */
    class Utilisateur {
        private string $nomUtilisateur;
        private string $mailUtilisateur;
        private string $numTelUtilisateur;
        private string $naissanceUtilisateur;
        private string $mdpChiffUtilisateur;
        private string $cleVerificationUtilisateur;
        private bool $compteActifUtilisateur;

        /**
         * Constructeur de la classe Utilisateur
         * @param string $nomUtilisateur Le nom de l'utilisateur dont on veut récupérer les informations
         */
        public function __construct(string $nomUtilisateur) {
            $this->nomUtilisateur = $nomUtilisateur;
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);

            // On récupère les informations de l'utilisateur dans la BD
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
         * Ajoute un nouvel utilisateur dans la base de données
         *
         * @param  mixed $nomUtilisateur
         * @param  mixed $mailUtilisateur
         * @param  mixed $naissanceUtilisateur
         * @param  mixed $mdpUtilisateur
         * @param  mixed $numTelUtilisateur
         * @param  mixed $cleVerificationUtilisateur
         * @return bool Vrai si l'ajout a été effectué, faux sinon
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
                
        /**
         * Vérifie la conformité du nom d'utilisateur
         *
         * @param  mixed $nomUtilisateur
         * @return bool Vrai si le nom est conforme, faux sinon
         */
        private static function checkUsername(string $nomUtilisateur):bool {
            $res = false;
            $err = "";
            if (strlen($nomUtilisateur) >= 5 && strlen($nomUtilisateur) <= 20) {
                // Taille du nom conforme
                if (ctype_alnum($nomUtilisateur)) {
                    // Caractères alphanumériques uniquement

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
                            // Le nom est unique
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
         * Vérifie la conformité de l'adresse mail
         *
         * @param  mixed $mailUtilisateur
         * @return bool Vrai si l'adresse est conforme, faux sinon
         */
        private static function checkMail(string $mailUtilisateur):bool {
            $res = false;
            $err = "";
            if (filter_var($mailUtilisateur, FILTER_VALIDATE_EMAIL)) {
                // L'adresse mail contient les caractères @ et .
                require 'conf/connexionbd.conf.php';
                $mysqli = new mysqli($host, $username, $password, $database, $port);
                $query = "SELECT COUNT(*) FROM utilisateur WHERE mail_utilisateur = ?;";
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("s", $mailUtilisateur);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fetch = $result->fetch_assoc();
                    $stmt->close();
                    if ($fetch['COUNT(*)'] == 0) {
                        // L'adresse mail est unique
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
         * Vérifie la conformité du numéro de téléphone
         *
         * @param  mixed $numTelUtilisateur
         * @return bool Vrai si le numéro est conforme, faux sinon
         */
        private static function checkNum(string $numTelUtilisateur):bool {
            $res = false;
            $err = "";
            if (strlen($numTelUtilisateur) == 10) {
                // Le numéro de téléphone est composé de 10 caractères
                if (ctype_digit($numTelUtilisateur)) {
                    // Les caractères sont numériques
                    if (substr($numTelUtilisateur, 0, 2) == "06" || substr($numTelUtilisateur, 0, 2) == "07") {
                        // Les caractères commencent par 06 ou 07
                        $res = true;
                    } else {
                        $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone est invalide</p>";
                    }
                } else {
                    $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone ne peut contenir que des chiffres.</p>";
                }
            } else if (strlen($numTelUtilisateur) == 0) {
                // Le numéro de téléphone est vide -> vrai car non obligatoire
                $res = true;
            } else {
                $err = "<p class='alert alert-danger mt-2'>Votre numéro de téléphone doit être composé de 10 chiffres.</p>";
            }
            echo $err;
            return $res;
        }
        
        /**
         * Vérifie la conformité de la date de naissance
         *
         * @param  mixed $naissanceUtilisateur
         * @return bool Vrai si la date de naissance est conforme, faux sinon
         */
        private static function checkNaissance(string $naissanceUtilisateur):bool {
            $res = false;
            $err = "";
            $format = "Y-m-d"; // AAAA-MM-JJ
            $date = date($format);
            $date100 = date($format, strtotime("-100 years"));
            $dt = DateTime::createFromFormat($format, $naissanceUtilisateur);

            if ($dt->format($format) == $naissanceUtilisateur) {
                // La date est au bon format AAAA-MM-JJ
                if ($naissanceUtilisateur < $date) {
                    // La date est inférieure à la date du jour
                    if ($naissanceUtilisateur > $date100) {
                        // L'intervalle de date est supérieur à 100 ans
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
         * Vérifie la conformite du mot de passe
         *
         * @param  mixed $mdpUtilisateur
         * @return bool Vrai si le mot de passe est conforme, faux sinon
         */
        private static function checkMdp($mdpUtilisateur):bool {
            $res = false;
            $err = "";
            if ((strlen($mdpUtilisateur) >= 8) && (strlen($mdpUtilisateur) <= 20) && (preg_match('/[a-z]/', $mdpUtilisateur)) && (preg_match('/[A-Z]/', $mdpUtilisateur)) && (preg_match('/[0-9]/', $mdpUtilisateur))) {
                // minimum 8 caractères et maximum 20 caractères, au moins 1 min, au moins 1 MAJ, au moins 1 chiffre 
                return true;
            }
            echo "<p class='alert alert-danger mt-2'>Votre mot de passe doit contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et 8 caractères.</p>";
            return false;
        }
        
        /**
         * Retourne tous les id de texte pour lesquels l'utilisateur a contribué
         *
         * @return array La liste des id de textes
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
         * Retourne la liste des id des réactions produites par l'utilisateur
         *
         * @return array La liste des id de réactions
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

        public function __setCleVerification(string $cleVerificationUtilisateur):void {
            $this->cleVerificationUtilisateur = $cleVerificationUtilisateur;
        }

        public function __setCompteActif(string $compteActifUtilisateur):void {
            $this->compteActifUtilisateur = $compteActifUtilisateur;
        }
    }
?>
