<!-- <php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?> -->

<?php 
	$type = "website";
	$titre = "Accueil";
	include "include/header.inc.php"; 
?>
	<main class="background-image" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
		<section class="container">
			<h2 class="">Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
		</section>
		<section class="container row">
			<h2>Derniers textes complétés</h2>
			<?php 
				echo txt_preview("fallen","novel"); 
				echo txt_preview("haikus","poem");
			?>
		</section>
		<!-- <section>
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
				<?php /*
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
					} */
				?>
			</p>
		</section> -->
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
