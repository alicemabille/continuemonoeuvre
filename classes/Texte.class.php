<?php
    /**
     * Classe pour la manipulation, l'affichage des textes du site
     */
    class Texte {
        private int $idTexte;
        private string $titre;
        private string $contenu;
        private string $type;
        private string $image="";
        public const MAX_TXT_PREVIEW_LENGTH = 1000;
        public const MAX_POEM_LENGTH = 20;

        /**
         * Constructeur de la classe Texte
         * @param int $idTexte l'identifiant du texte dont on veut récupérer les informations
         */
        public function __construct(int $idTexte) {
            $this->idTexte = $idTexte;
            // Récupération des données associées au texte
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            // On récupère les données du texte à partir de la BD
            $query = "
                SELECT * FROM texte WHERE id_texte=?;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $idTexte);
                $stmt->execute();
                $result = $stmt->get_result();
                $fetch = $result->fetch_assoc();
                $stmt->close();
            }
            $mysqli->close();
            $this->titre = $fetch['titre_texte'];
            $this->contenu = $fetch['contenu_texte'];
            $this->type = $fetch['type_texte'];
            if ($fetch['image_texte'] !== null) {
                $this->image = $fetch['image_texte'];
            }
        }

        /**
         * Méthode statique de création de texte dans la base de données
         * 
         * @param string $titre Titre du texte
         * @param string $contenu Contenu du texte
         * @param string $auteur Nom d'utilisateur de l'auteur (->table ecrire)
         * @param string $date Date à laquelle le texte à été créé (->table ecrire)
         * @param string $type Type du texte : roman, poème, haiku. Par défaut roman
         * @param string $imageURL URL de l'image selectionné par l'utilisateur pour son texte. Par défaut null
         * @return int Retourne l'id du texte nouvellement créé, null en cas d'erreur
         */
        public static function creerTexte(string $titre, string $contenu, string $auteur, string $date, string $type='roman', string $imageURL=null):?int {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
           
            /*si le texte a déjà été enregistré, on évite de l'engistrer à nouveau et on renvoie l'id
            du texte existant*/
            $id = Texte::noDuplicata($titre, $contenu);
            if($id != -1){
                return $id;
            }

            if ($imageURL !== null) {
                $image = file_get_contents($imageURL);
                $queryTexte = "
                    INSERT INTO texte(titre_texte, contenu_texte, type_texte, image_texte)
                        VALUES(?, ?, ?, ?);
                ";
                $stmtTexte = $mysqli->prepare($queryTexte);
                if ($stmtTexte) {
                    $stmtTexte->bind_param("ssss", $titre, $contenu, $type, $image);
                    $stmtTexte->execute();
                    $stmtTexte->close();
                }
            } else {
                $queryTexte = "
                    INSERT INTO texte(titre_texte, contenu_texte, type_texte)
                        VALUES(?, ?, ?);
                ";
                $stmtTexte = $mysqli->prepare($queryTexte);
                if ($stmtTexte) {
                    $stmtTexte->bind_param("sss", $titre, $contenu, $type);
                    $stmtTexte->execute();
                    $stmtTexte->close();
                }
            }

            // Récupération de l'id de type AUTO_INCREMENT
            $id = $mysqli->insert_id;

            // Insertion dans la table ecrire
            $queryEcrire = "
                INSERT INTO ecrire(nom_auteur, id_ecrit, date_ecrit)
                    VALUES(?, ?, ?);
            ";
            $stmtEcrire = $mysqli->prepare($queryEcrire);
            if ($stmtEcrire) {
                $stmtEcrire->bind_param("sis", $auteur, $id, $date);
                $stmtEcrire->execute();
                $stmtEcrire->close();
            }
            $mysqli->close();
            return $id;
        }

        /**
         * Vérifie qu'aucun texte n'a été ajouté à la base de données avec le même titre et le même contenu.
         * Evite les duplicata.
         * @param string titre
         * @param string contenu
         * @return int l'id du texte existant le cas échéant, -1 sinon.
         */
        public static function noDuplicata(string $titre, string $contenu) : int {
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $id = -1;
            $queryTexte = "
                SELECT id_texte FROM texte
                WHERE titre_texte=? AND contenu_texte=?;
            ";
            $stmtTexte = $mysqli->prepare($queryTexte);
            if ($stmtTexte) {
                $stmtTexte->bind_param("ss", $titre, $contenu);
                $stmtTexte->execute();
                $result = $stmtTexte->get_result();
                $row = $result->fetch_assoc();
                if($row["id_texte"]!=null) $id = $row["id_texte"];
                $stmtTexte->close();
            }
            $mysqli->close();
            return $id;
        }

        /**
         * Preview of the text on the homepage when no user is connected.
         * @return string html containing cropped story
         */
        public function txtPreview():string {
            $res = $this->contenu;
            if ($this->type == "poeme") {
                $res = "<pre class='p-4 text-left'>" .$res;
                $res .= "</pre>";
            } else if ($this->type == "haiku") {
                $res = "<pre class='p-4 text-center'>".$res;;
                $res .= "</pre>";
            } else if ($this->type == "roman") {
                $res = "<p class='p-4 text-left'>".str_replace("\n\n","</p><p>",$res)."</p>";
            } else {
                $res = "<p class='alert alert-danger'>Catégorie de texte inconnue.</p>";
            }

            if ($this->image == "") {
                // Si pas d'image -> image par défaut
                $bg = "url('images/logo.png');";
            } else {
                $bg = "url(data:photo/jpeg;base64,". base64_encode($this->image) .");";
            }

            $res = "<article class=\"text-preview col bg-secondary text-white px-0 m-3 rounded shadow\"> \n\t\t\t\t".
                "<h3 class='p-2'>". $this->titre ."</h3>\n\t\t\t\t"
                .$res."<div class='preview-text-image container-fluid p-5 mb-0 bg-image' style=\"background-image: ". $bg ."\">
                <a href=\"lecture.php?txt_id=".$this->idTexte."\" class=\"btn btn-outline-light\" role=\"button\">Lire la suite</a></div> \n\t\t\t
                <p><i class='fa-solid fa-user'></i> <a href='#' class='link-light'>". self::getLastModifiedAuthor() ."</a></p>
                <p><i class='fa-solid fa-clock-rotate-left'></i> ". self::getLastModifiedDate() ."</p>
                </article> \n";
            return $res;
        }
        
        /**
         * Preview of the text on the homepage when no user is connected.
         * @return string html containing cropped story
         */
        public function txtPreviewCard():string {
            $res = $this->contenu;
            if ($this->type == "poeme") {
                $res = "<pre class='p-4 text-left'>". $res ."</pre>";
            } else if ($this->type == "haiku") {
                $res = "<pre class='p-4 text-center'>". $res ."</pre>";
            } else if ($this->type == "roman") {
                $res = "<p class='p-4 text-left'>".str_replace("\n\n","</p><p>",$res)."</p>";
            } else {
                $res = "<p class='alert alert-danger'>Catégorie de texte inconnue.</p>";
            }

            $imgCard = "<img src='images/logo.png' class='card-img-top' alt='image par défaut'/>";
            if ($this->image !== "") {
                $imgCard = "<img src='data:photo/jpeg;base64,". base64_encode($this->image) ."' class='card-img-top' alt='image associée à ". $this->titre ."'>";
            }

            $res = "<div class='col'>
                        <div class='card h-100 bg-dark text-light' style='max-width : 80vh'>
                            ". $imgCard ."
                            <div class='card-body'>
                                <h3 class='card-title'>". $this->titre ."</h3>
                                ". $res ."
                                <p class='card-text'><small class='text-muted'><i class='fa-solid fa-clock-rotate-left'></i> ". self::getLastModifiedDate() ." par ". self::getLastModifiedAuthor() ."</small></p>
                                <a href='lecture.php?txt_id=". $this->idTexte ."' class='btn btn-info'>Lire la suite</a>
                            </div>
                        </div>
                    </div>";
            return $res;
        }

        /**
         * Transforms full text into a pretty html piece of code. To be used when user is connected.
         * @return string html containing full story
         */
        public function txtFull():string {
            if ($this->type == "roman") {
                $res = "<p>".$this->contenu;
                $res = str_replace("\n\n","</p><p>",$res);
                $res .= "</p>";
                $alignment = "left";
            } else if ($this->type == "haiku") {
                $res = "<pre>".$this->contenu."</pre>";
                $alignment = "center";
            } else if ($this->type) {
                $res = "<pre>".$this->contenu."</pre>";
                $alignment = "left";
            } else {
                $res = "<p class='alert alert-danger'>Catégorie de texte inconnue.</p>";
            }
            $res = "<article class=\"text-".$alignment." bg-secondary text-white p-4 m-1 rounded shadow\"> \n\t\t\t\t<h3>".
                ucfirst($this->titre)."</h3>\n\t\t\t\t"
                .$res."\n\t\t\t <p class='text-light fs-6 fw-light'><i class='fa-solid fa-clock-rotate-left'></i> il y a ". self::getLastModifiedDate() ." par <a href='https://continuemonoeuvre.alwaysdata.net/profil-view.php?user=". self::getLastModifiedAuthor() ."'>". self::getLastModifiedAuthor() ."</a></p></article> \n";
            return $res;
        }

        /**
         * Preview of the end given text (used on the edit page).
         * @return string html containing cropped story
         */
        function txtEnd():string {
            if (strlen($this->contenu) > self::MAX_TXT_PREVIEW_LENGTH) {
                $res = substr($this->contenu, -self::MAX_TXT_PREVIEW_LENGTH);
            } else {
                $res = $this->contenu;
            }
            if ($this->type == "roman") {
                $res = str_replace("\n\n","</p><p>",$res);
                $res = "<p>...".$res."</p>";
                $alignment = "left";
            } else if ($this->type == "haiku") {
                $nbbr = substr_count($res,"\n");
                if($nbbr >  self::MAX_POEM_LENGTH) {
                    $res = substr($res, -self::MAX_POEM_LENGTH*11);
                }
                $res = "<pre>...".$res."</pre>";
                $alignment = "center";
            } else if ($this->type == "poeme") {
                $nbbr = substr_count($res,"\n");
                if($nbbr >  self::MAX_POEM_LENGTH) {
                    $res = substr($res, -self::MAX_POEM_LENGTH*11);
                }
                $res = "<pre>...".$res."</pre>";
                $alignment = "left";
            } else {
                $res = "<p class='alert alert-danger'>Catégorie de texte inconnue.</p>";
            }

            //styling the cropped result
            if($_SESSION['session']==true){
                $res = "<article class=\" text-".$alignment." bg-secondary text-white p-4 m-1 rounded shadow\"> \n\t\t\t\t<h3>".
                $this->titre."</h3>\n\t\t\t\t"
                .$res."\n\t\t\t </article> \n";
                return $res;
            }
            $res = "<article class=\" text-".$alignment." text-preview col-12 col-md-5 bg-secondary text-white p-4 m-4 rounded shadow\"> \n\t\t\t\t<h3>".
            $this->titre."</h3>\n\t\t\t\t"
            .$res."<a href=\"connexion.php?txt_id=".$this->idTexte."&txt_category=".$this->type."\" class=\"btn btn-info\" role=\"button\">Contribuer à ce texte</a> \n\t\t\t </article> \n";
            return $res;
        }

        /**
         * Vérifie que le texte peut être modifié
         * @param string $auteur L'auteur de la modification
         * @param string $date Date à laquelle le texte est modifié
         */
        public function checkTextEdit(string $auteur, string $date):void {
            if (isset($_GET['txt_id']) && !empty($_GET['txt_id'])) {
                if (isset($_POST['editor-textArea']) && !empty($_POST['editor-textArea'])) {
                    $this->editText($_POST["editor-textArea"], $auteur, $date);
                }
            } else {
                echo "<p class='alert alert-primary mt-2'>L'id du texte à modifier n'a pas pu être récupéré.</p>";
            }
        }

        /**
         * Modifie l'attribut $this->contenu et enregistre dans la BD ce nouveau texte ainsi que l'auteur
         * et la date de modification
         * @param string $textToAdd Le texte rajouté à la suite
         * @param string $auteur L'auteur de cette modification
         * @param string $date La date à laquelle cette modification a été faite
         */
        public function editText(string $textToAdd, string $auteur, string $date):void {
            // Modification de l'attribut
            $this->contenu .= "\n".$textToAdd;

            // Mise à jour du texte dans la table texte
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                UPDATE texte SET contenu_texte=? WHERE id_texte=?;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("si", $this->contenu, $this->idTexte);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();

            // On vérifie si l'utilisateur a déjà modifié ce texte par le passé
            $queryCount = "
                SELECT COUNT(*) FROM ecrire WHERE nom_auteur=? AND id_ecrit=?;
            ";
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $stmtCount = $mysqli->prepare($queryCount);
            if ($stmtCount) {
                $stmtCount->bind_param("si", $auteur, $this->idTexte);
                $stmtCount->execute();
                $resultCount = $stmtCount->get_result();
                $fetchCount = $resultCount->fetch_assoc();
                $occurrences = $fetchCount['COUNT(*)'];
                $stmtCount->close();
            }
            $mysqli->close();

            if (!$occurrences) {
                // Pas d'occurrence donc on insert une nouvelle ligne
                $mysqli = new mysqli($host, $username, $password, $database, $port);
                $query = "
                    INSERT INTO ecrire(nom_auteur, id_ecrit, date_ecrit)
                        VALUES (?, ?, ?);
                ";
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("sis", $auteur, $this->idTexte, $date);
                    $stmt->execute();
                    $stmt->close();
                }
                $mysqli->close();
            } else {
                // Il y a une occurrence donc on met à jour la date de modification de ce texte
                $query = "
                    UPDATE ecrire SET date_ecrit=? WHERE nom_auteur=? AND id_ecrit=?;
                ";
                $mysqli = new mysqli($host, $username, $password, $database, $port);
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("ssi", $date, $auteur, $this->idTexte);
                    $stmt->execute();
                    $stmt->close();
                }
                $mysqli->close();
            }
        }

        /**
         * Renvoie la date de dernière modification d'un texte
         * @return string La date de dernière modification
         */
        public function getLastModifiedDate():string {
            $res = "";
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT date_ecrit FROM ecrire WHERE id_ecrit=? ORDER BY date_ecrit DESC;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $this->idTexte);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $lastModified = new DateTime($row['date_ecrit']);
                $stmt->close();
            }
            $mysqli->close();
            
            // Comparaison avec la date actuelle
            $now = new DateTime('now');
            $interval = $now->diff($lastModified);

            // Temps UNIX
            $secondsNow = time();
            $secondsLastModified = $lastModified->getTimestamp();
            $secondsInterval = $secondsNow - $secondsLastModified;

            if ($secondsInterval > 3600) {
                // supérieur à 1h
                if ($secondsInterval > 86400) {
                    // supérieur à 1j
                    $res .= "il y a ". $interval->format('%dj');
                } else {
                    // supérieur ou égal à 1h et inférieur à 24h
                    $res .= "il y a ". $interval->format('%hh');
                }
            } else {
                // inférieur à 1h
                $res .= "récemment";
            }
            return $res;
        }

        /**
         * Renvoie l'auteur de la dernière modification d'un texte
         * @return string Le lien vers la page de l'auteur
         */
        public function getLastModifiedAuthor():string {
            $res = "";
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                SELECT nom_auteur FROM ecrire WHERE id_ecrit=? ORDER BY date_ecrit DESC;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $this->idTexte);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $lastModified = $row['nom_auteur'];
                $res = $lastModified;
                $stmt->close();
            }
            $mysqli->close();
            return $res;
        }
        
        /**
         * Tous les gifs que les utilisateurs ont associés à ce texte
         *
         * @return string code html des réactions des utilisateurs
         */
        public function getReactions():string {
            //$res = "<div class='row row-cols-1 row-cols-md-3 g-4'>";
            $res = "<div class='row'>";
            require 'conf/connexionbd.conf.php';
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "SELECT nom_auteur_reaction, url_reaction FROM reagir WHERE id_texte_reaction = ?;";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $this->idTexte);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    // Affichage des réactions des utilisateurs
                    $res .= "<div class='col'>
                                <div class='card bg-dark text-light' style='max-width: 10rem;'>
                                    <img src='". $row['url_reaction'] ."' class='card-img-top' alt='reaction gif'>
                                    <div class='card-body'>
                                        <p class='card-text'>Par ". $row['nom_auteur_reaction'] ."</p>
                                    </div>
                                </div>
                            </div>";
                }
            }
            $res .= "\n</div>";
            return $res;
        }

        public function __getId():int {
            return $this->idTexte;
        }

        public function __getTitre():string {
            return $this->titre;
        }

        public function __getContenu():string {
            return $this->contenu;
        }

        public function __getType():string {
            return $this->type;
        }

        public function image():bool {
            return $this->image !== "";
        }

        public function getImage():string {
            return base64_encode($this->image);
        }

        /**
         * Modifie l'image associée à un texte
         * @param string $newURL L'URL de la nouvelle image
         */
        public function setImage(string $newURL):void {
            $newImage = file_get_contents($newURL);

            // Modification de l'attribut
            $this->image = $newURL;

            // Modification de la BD
            require('conf/connexionbd.conf.php');
            $mysqli = new mysqli($host, $username, $password, $database, $port);
            $query = "
                UPDATE texte SET image_texte=? WHERE id_texte=?;
            ";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("si", $newImage, $this->idTexte);
                $stmt->execute();
                $stmt->close();
            }
            $mysqli->close();
        }
    }
?>
