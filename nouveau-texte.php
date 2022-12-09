<?php 
	$titre = "nouveau texte";
	include "include/header.inc.php";
?>
	<main class="background-image pb-3" style="background-image: url(/images/writing-pixabay2.jpg); margin-bottom: 0px;">
		<section class="container pt-2 pb-2">
			<?php 
                if($_SESSION["session"]==true) {
                        include "include/new-text-editor.inc.php";
                }
                else {
					echo "<form action='connexion.php' class='d-grid'><button class='btn btn-primary btn-lg' >Se connecter</button></form>";
				}
			?>
		</section>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>