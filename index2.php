<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Test</title>
		<meta name="author" content="Christella" />
		<meta charset="UTF-8" />
		<meta name="date" content="2022-11-03T23:03:07+0100" />
	<body>
        <?php
            $titre="API SERVEUR PIXABAY";
        ?>
		<main>
            <section>
				<h2 >Test pour API côté serveur</h2>
				<form method="post">
					<fieldset>
						<label for="search">Que cherchez-vous ?</label>
						<input type="text" name="search"/>
						<input type="submit" name="search_type" value="Photos" />
                    	<input type="submit" name="search_type" value="Vidéos" />
					</fieldset>
				</form>
				<p>
					<?php
						if(isset($_POST['search_type']) && isset($_POST['search']) && !empty($_POST['search_type']) && !empty($_POST['search'])) {
							if ($_POST['search_type'] == "Photos"){
								require "./fonctions.inc.php";
								$q = $_POST['search']; 
								echo get_images($q);
								echo "<figure id='pixabay_rights'><a href='https://pixabay.com/fr/' target='_blank'><img src='./images/droits_pixabay.png' alt='Images provided by Pixabay'/></a><figcaption>Images fournies par Pixabay_API</figcaption></figure>";
							} else if ($_POST['search_type'] == "Vidéos"){
								require "./fonctions.inc.php";
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
		</main>
	</body>
</html>
