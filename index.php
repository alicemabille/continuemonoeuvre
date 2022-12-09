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
	<main class="background-image pb-3" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
		<section class="container">
			<h1 class="text-light">Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h1>
		</section>
		

		<section class="container-fluid row mt-5">
			<h2 class="text-center text-light">Les derniers textes édités</h2>
			<div class="row row-cols-1 row-cols-md-3 g-4">
				<?php
					$txt_ids = last_modified_txts_ids();
					spl_autoload_register(function ($classe) {
						include('classes/'. $classe .'.class.php');
					});
					for ($i=0; $i<12; $i++) {
						if ($txt_ids[$i] != null) {
							$texte = new Texte($txt_ids[$i]);
							echo $texte->txtPreviewCard();
						}
					}
				?>
			</div>
			<form action="nouveau-texte.php" class="d-grid mt-2">
				<button type="submit" class="btn btn-dark btn-lg btn-block">Publier un texte</button>
			</form>
		</section>

	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
