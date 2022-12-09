<?php 
	$type = "article:author";
    $titre = "profil";
    if (isset($_GET["user"])&&!empty($_GET["user"])){
        $profile = $_GET["user"];
        $titre .= " - $profile";
    }
    
	include "include/header.inc.php";
?>
	<main>
		<div class="container mt-1">
		<?php 
				if(isset($_GET["user"])&&!empty($_GET["user"])){
					if($_SESSION['session']==true){
						echo "<p class='alert alert-warning'>Désolés, cette page n'est pas encore prête.<p>";
					}
					else{
						echo "<p class='alert alert-warning'>Veuillez vous connecter pour accéder à cette page.<p>";
					}
				}
				else{
					if($_SESSION['session']==true){
						//afficher le profil de l'utilisateur connecté
						echo "<p class='alert alert-warning'>Désolés, cette page n'est pas encore prête.<p>";
					}
					echo "<p class='alert alert-warning'>Veuillez vous connecter pour accéder à cette page.</p>
						<p>Vous avez déjà un compte ? <a href='connexion.php'>Se connecter</a></p>
						<p>Vous n'avez pas encore de compte ? <a href='inscription.php'>S'inscrire</a></p>";
				}
		?>
		</div>
	</main>

	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
