<?php 
	$type = "article";
	$titre = "lecture";
	include "include/header.inc.php";

	$isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
    spl_autoload_register(function ($classe) {
        include('classes/'. $classe .'.class.php');
    });

    $texte = new Texte($_GET["txt_id"]);
    $title = $texte->__getTitre();

	if ($texte->image()) {
		echo "<main class='bg-image pb-3 pt-2 mb-0 mt-0' style='background-image : url(data:photo/jpeg;base64,". $texte->getImage() .")'>";
	} else {
		echo "<main class='bg-image pb-3 pt-2 mb-0 mt-0' style='background-image : url(images/writing-pixabay.jpg);'>";
	}
?>
		<div class="container mt-2">
		<?php 
			if($_SESSION["session"]==true) {
				if($isset_txt){
					$txt_id = $_GET["txt_id"];
					$txt_category = $texte->__getType();
					if($_SESSION['session']==true){
                        echo $texte->txtFull();
						echo '<div class="row">';
						if (isset($_POST['gif']) && !empty($_POST['gif'])) {
							echo Reaction::ajouterReaction($_SESSION['username'], $_GET['txt_id'], $_POST['gif']);
						}
						if (isset($_POST['txt_image']) && !empty($_POST['txt_image'])) {
							$texte->setImage($_POST['txt_image']);
						} 
						include "include/gif-board.inc.php";
						echo '<form class="col-md-4 col-sm-6 m-1" action="ecriture.php?txt_id='.$txt_id.'" method="post">
							<button class="btn btn-primary">Contribuer à ce texte</button>
							</form>
							</div>';
						if($texte->getLastModifiedAuthor()==$_SESSION["username"]){
							// Destruction en cas de modification d'image précédente
							unset($_SESSION['img_txt_modify']);
							echo '<form class="col-md-4 col-sm-6 m-1" action="recherche-images.php?txt_id='.$txt_id.'" method="post">
									<input type="hidden" name="txt_id_img" value="'.$txt_id.'" >
									<button class="btn btn-primary">Changer l\'illustration</button>
								</form>';
						}
						echo $texte->getReactions();
						echo '<form class="col-md-4 col-sm-6 m-1" action="generer_pdf.php" method="post">
							<input type="hidden" name="txt_id_pdf" value="'.$txt_id.'" >
							<button class="btn btn-primary">Générer le pdf du texte</button>
							</form>';
					}
					else{
						echo $texte->txtPreview();
					}
				}	
				else{
					echo "<p class='alert alert-warning'>Pas de texte à afficher.</p>";
				}
			}
			else {
				echo "<a class='btn btn-info' role='button' href='connexion.php'>Se connecter</a>";
			}
		?>
		</div>
	</main>

	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
