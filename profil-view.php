<!--PROFIL DE SON PROPRE PROFIL-->
<?php 
	$type = "article:author";
    $titre = "profil-view";
    if (isset($_GET["user"])&&!empty($_GET["user"])){
        $profile = $_GET["user"];
        $titre .= " - $profile";
    }
    
	include "include/header.inc.php";
?>
	<main>
		<div class="container mt-1">
		<?php 
			if(isset($_SESSION['session']) && !empty($_SESSION['session']) && $_SESSION['session']==true){
				if(isset($_GET["user"]) && !empty($_GET["user"])){
		    		//require "./include/functions.inc.php";
                    echo get_userInfos_else($_GET["user"]);
				}
				else{
					echo "<p class='alert alert-warning'>Cette page n'existe pas ! <a href='https://continuemonoeuvre.alwaysdata.net/'>Retour à l'accueil</a></p>";
				}
			}
			else{
				echo "<p class='alert alert-warning'>Veuillez vous connecter pour accéder à cette page. <a href='https://continuemonoeuvre.alwaysdata.net/connexion.php'>Me connecter</a></p>";
			}
		?>
		</div>
	</main>

	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>