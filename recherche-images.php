<?php 
	$type = "website";
	$titre = "Accueil";
	include "include/header.inc.php"; 
?>
	<main class="background-image pb-3 pt-2 mb-0 mt-0" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
			<?php
				if (isset($_POST['txt_id_img']) && !empty($_POST['txt_id_img'])) {
					// une variable $_POST['txt_id_img] a été transmise depuis lecture.php pour modification d'image
					$_SESSION['img_txt_modify'] = $_POST['txt_id_img'];
				}

				if (isset($_POST['txt_image']) && !empty($_POST['txt_image'])) {
					// une image a été cliquée pour modifier l'image du texte
					spl_autoload_register(function ($classe) {
						include('classes/'. $classe .'.class.php');
					});
					$texte = new Texte($_SESSION['img_txt_modify']);
					$texte->setImage($_POST['txt_image']);
					header("Location: lecture.php?txt_id=". $_SESSION['img_txt_modify']);
					// Destruction de la variable de session de mofid de texte sur la page rédirigée
				}
                include "include/pixabay-search.inc.php";
            ?>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
