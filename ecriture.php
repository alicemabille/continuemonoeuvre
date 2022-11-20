<?php 
	$titre = "écriture";
	include "include/header.inc.php";
	$isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
	$isset_txt_category = isset($_GET["txt_category"])&&!empty($_GET["txt_category"]);
	if (isset($_GET["txt_title"])&&!empty($_GET["txt_title"])){
		$title = $_GET["txt_title"];
	}
	else {
		$title = "orange";
	}
?>
	<main class="bg-image pb-3 pt-2 mb-0 mt-0" style="background-image: url('<?php echo first_pixabay_fullhd($title); ?>');">
		<section class="container mt-1">
			<?php 
				if($_SESSION["session"]==true) {
					if($isset_txt&&$isset_txt_category){
						$txt_id = $_GET["txt_id"];
						$txt_category = $_GET["txt_category"];
						echo txt_end($txt_id, $txt_category);
						include "include/text-editor.inc.php";
					}
					else{
						echo "<p class='alert alert-warning'>Pas de texte à afficher. Vérifiez que le titre et la catégorie existent.</p>";
					}
				}
				else {
					echo "<a class='btn' href='connexion.php'>Se connecter</a>";
				}
			?>
		</section>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
