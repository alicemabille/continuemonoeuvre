<!--PROFIL DE SON PROPRE PROFIL-->
<?php 
	$type = "article:author";
    $titre = "profil";
    if (isset($_GET["user"])&&!empty($_GET["user"])){
        $profile = $_GET["user"];
        $titre .= " - $profile";
    }
    
	include "include/header.inc.php";
?>
	<main class="background-image pb-3" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
		<div class="container mt-1">
		<?php 
			if(isset($_SESSION['session']) && !empty($_SESSION['session']) && $_SESSION['session']==true){//
				if(isset($_GET["user"]) && !empty($_GET["user"])){
		    		//require "./include/functions.inc.php";
                    echo get_userInfos($_GET["user"]);
				}
				else{
					echo "<p class='alert alert-warning'>Cette page n'existe pas. <a href='https://continuemonoeuvre.alwaysdata.net/'>Retour à la page d'accueil</a></p>";
				}
			}
			else{
				echo "<p class='alert alert-warning'>Veuillez vous connecter pour accéder à cette page <a href='https://continuemonoeuvre.alwaysdata.net/connexion.php'>Me connecter</a></p>";
			}
		?>
		</div>
	</main>

	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>