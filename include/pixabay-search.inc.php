<section class="container bg-secondary rounded p-3">
    <form method="post">
        <fieldset>
            <legend class="text-light">Choisir une illustration</legend>
            <input type="text" name="search"/>
            <input type="submit" name="search_type" value="Photos" />
            <input type="submit" name="search_type" value="Vidéos" />
        </fieldset>
    </form>
    <p>
        <?php
            if(isset($_POST['search_type']) && isset($_POST['search']) && !empty($_POST['search_type']) && !empty($_POST['search'])) {
                if ($_POST['search_type'] == "Photos"){
                    $q = $_POST['search']; 
                    echo get_images($q);
                    echo "<figure id='pixabay_rights'><a href='https://pixabay.com/fr/' target='_blank'><img src='./images/droits_pixabay.png' alt='Images provided by Pixabay'/></a><figcaption>Images fournies par Pixabay_API</figcaption></figure>";
                } else if ($_POST['search_type'] == "Vidéos"){
                    $q = $_POST['search'];
                    echo get_videos($q);
                    echo "<figure id='pixabay_rights'><a href='https://pixabay.com/fr/' target='_blank'><img src='./images/droits_pixabay.png' alt='Images provided by Pixabay'/></a><figcaption>Images fournies par Pixabay_API</figcaption></figure>";
                }
            }
            if (isset($_POST['search_type']) && isset($_POST['search']) && empty($_POST['search'])){
                echo "Remplir le champ de recherche";
            }
        ?>
    </p>
</section>