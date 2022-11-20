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
			<h2 class="text-light">Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
		</section>
		<section class="container-fluid row">
			<?php 
				echo txt_preview("fallen","Fallen","novel"); 
				echo txt_preview("haikus","Haikus","haiku");
				echo txt_preview("haikus","Haikus","haiku");
			?>
		</section>
		<section class="container-fluid row">
			<form action="nouveau-texte.php" class="d-grid">
				<button type="submit" class="btn btn-dark btn-lg">Publier un texte</button>
			</form>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
