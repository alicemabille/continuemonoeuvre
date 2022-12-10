<?php 
	$titre = "écriture";
	include "include/header.inc.php";
	$isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
    spl_autoload_register(function ($classe) {
        include('classes/'. $classe .'.class.php');
    });
    $texte = new Texte($_GET["txt_id"]);
    $title = $texte->__getTitre();

?>
	<main class="bg-image pb-3 pt-2 mb-0 mt-0" style="background-image: url('image/writing-pixabay2.jpg'); ">
		<div class="container mt-1">
			<?php 
				if($_SESSION["session"]==true) {
					if($isset_txt) {
						$txt_id = $_GET["txt_id"];
						$txt_category = $texte->__getType();
                        echo $texte->txtEnd();
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
		</div>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
