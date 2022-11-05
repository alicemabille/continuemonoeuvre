<!-- <php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?> -->

<?php include "include/header.inc.php"; ?>
	<main>
		<section class="container">
			<h2>Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
		</section>
		<section class="container row">
			<h2>Derniers textes complétés</h2>
			<?php 
				echo txt_preview("fallen","novel"); 
				echo txt_preview("haikus","poem");
			?>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
