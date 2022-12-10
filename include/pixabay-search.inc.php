<section class="container bg-secondary rounded p-3">
    <h2 class="text-light">Choisir une illustration</h2>
    <form method="post">
        <fieldset>
            <input type="text" name="search"/>
            <input type="submit" name="search_type" value="Photos" />
            <input type="submit" name="search_type" value="Vidéos" />
        </fieldset>
    </form>
    <p>
        <?php
            //un mot-clé a été saisi et le type de média a été choisi
            if(isset($_POST['search_type']) && isset($_POST['search']) && !empty($_POST['search_type']) && !empty($_POST['search'])) {
                if ($_POST['search_type'] == "Photos"){ //afficher les images associées au mot-clé
                    $q = $_POST['search']; 
                    echo get_images($q);
                    echo "<figure id='pixabay_rights'><a href='https://pixabay.com/fr/' target='_blank'><img src='./images/droits_pixabay.png' alt='Images provided by Pixabay'/></a><figcaption>Images fournies par Pixabay_API</figcaption></figure>";
                } else if ($_POST['search_type'] == "Vidéos"){ //afficher les vidéos associées au mot-clé
                    $q = $_POST['search'];
                    echo get_videos($q);
                    echo "<figure id='pixabay_rights'><a href='https://pixabay.com/fr/' target='_blank'><img src='./images/droits_pixabay.png' alt='Images provided by Pixabay'/></a><figcaption>Images fournies par Pixabay_API</figcaption></figure>";
                }
            }
            //il manque le mot-clé ou le type de média
            if (isset($_POST['search_type']) && isset($_POST['search']) && empty($_POST['search'])){
                echo "Remplir le champ de recherche";
            }
        ?>
    </p>
</section>