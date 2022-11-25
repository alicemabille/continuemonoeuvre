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
			<h2 class="text-light">Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
		</section>
		<section class="container-fluid row">
			<?php 
				$txt_ids = last_modified_txts_ids();
				// TEST
				spl_autoload_register(function ($classe) {
					include('classes/'. $classe .'.class.php');
				});
				for($i=0; $i<3; $i++){
					if($txt_ids[$i]!=null){
						$texte = new Texte($txt_ids[$i]);
						echo $texte->txtPreview();
					}
				}

				// $idpoeme = 8;
				// $poeme = new Texte($idpoeme);
				// echo $poeme->txtPreview();

				// $idHaiku = 9;
				// $haiku = new Texte($idHaiku);
				// echo $haiku->txtPreview();
				//
			?>
		</section>
		<section class="container-fluid row">
			<?php
				for($i=3; $i<6; $i++){
					if($txt_ids[$i]!=null){
						$texte = new Texte($txt_ids[$i]);
						echo $texte->txtPreview();
					}
				}
			?>
		</section>
		<section class="container-fluid row">
			<?php
				for($i=6; $i<12; $i++){
					if($txt_ids[$i]!=null){
						$texte = new Texte($txt_ids[$i]);
						echo $texte->txtPreview();
					}
				}
			?>
		</section>
		<section class="container-fluid row mb-3">
			<form action="nouveau-texte.php" class="d-grid">
				<button type="submit" class="btn btn-dark btn-lg btn-block">Publier un texte</button>
			</form>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
