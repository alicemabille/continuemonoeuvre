<!--PROFIL DE SON PROPRE PROFIL-->
<?php 
	$type = "article:author";
    $titre = "profil";
	
	session_start();
	if (!$_SESSION['session']) {
		// Si l'utilisateur n'est pas connecté -> redirection
		header("Location: connexion.php");
	}
	$titre .= " - ". $_SESSION['username'];
    
	include "include/header.inc.php";

	spl_autoload_register(function ($classe) {
		include('classes/'. $classe .'.class.php');
	});
	// on récupère les infos de l'utilisateur en fonction de sa session
	$utilisateur = new Utilisateur($_SESSION['username']);
?>
	<main class="background-image pb-3" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
		<div class="container">
			<h1 class="text-center text-light">Bonjour <?php echo $utilisateur->__getNom(); ?> !</h1>
			<section class="container bg-info text-dark m-3 p-2 rounded">
				<h2 class="p-2">Informations personnelles</h2>
				<ul>
					<li>Nom : <?php echo $utilisateur->__getNom(); ?></li>
					<li>Date de naissance : <?php echo $utilisateur->__getNaissance(); ?></li>
					<li>Mail : <?php echo $utilisateur->__getMail(); ?></li>
					<li>Numéro de télephone : <?php echo $utilisateur->__getNumTel(); ?></li>
				</ul>
			</section>
			<section class="container bg-secondary text-light m-3 p-2 rounded">
				<h2 class="p-2">Les œuvres auxquelles vous avez contribué</h2>
				<div class="row row-cols-1 row-cols-md-3 g-4">
				<?php
					$idsTexts = $utilisateur->getTextsIds(); // array
					foreach ($idsTexts as $id) {
						$texte = new Texte($id);
						echo $texte->txtPreviewCard();
					}
				?>
				</div>
			</section>
			<section class="container bg-secondary text-light m-3 p-2 rounded">
				<h2 class="p-2">Les œuvres sur lesquelles vous avez réagi</h2>
				<div class="row row-cols-1 row-cols-md-3 g-4">
					<?php
						$idsReactions = $utilisateur->getReactionsIds();
						if(empty($idsReactions)) echo "<p class='alert alert-info m-3'>Rien à afficher ici.</p>";
						foreach ($idsReactions as $id) {
							$reaction = new Reaction($_SESSION['username'], $id);
							echo $reaction->getReactionCard();
						}
					?>
				</div>
			</section>
		</div>
	</main>

	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>