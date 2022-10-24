<!-- <php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?> -->

<?php include "include/header.inc.php"; ?>
	<main>
		<section class="container">
			<h2>Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
			<article>
				<h3 class="text-info row col-12 bg-dark text-white">GIF Powered by Tenor</h3>
				<div class="row">
					<button id="featured_gif_button" class="btn btn-dark dropdown col m-1">À la une</button>
					<input type="text" id="search_gif_input" class="col m-1"/>
					<button id="search_gif_button" class="btn btn-dark col m-1">Rechercher</button>
				</div>
				<div id="gif_list_1" class="row">
				</div>
				<div id="gif_list_2" class="row">
				</div>
				<div id="gif_list_3" class="row">
				</div>
			</article>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
