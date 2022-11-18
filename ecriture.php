<?php 
	$titre = "écriture";
	include "include/header.inc.php";
	$isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
	$isset_txt_category = isset($_GET["txt_category"])&&!empty($_GET["txt_category"]);
?>
	<main>
		<section class="container mt-1">
			<?php 
				if($isset_txt&&$isset_txt_category){
					$txt_id = $_GET["txt_id"];
					$txt_category = $_GET["txt_category"];
					echo txt_end($txt_id, $txt_category);
					include "include/text-editor.inc.php";
				}
				else{
					echo "<p class='alert alert-warning'>Pas de texte à afficher. Vérifiez que le titre et la catégorie existent.</p>";
				}
			?>
		</section>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
