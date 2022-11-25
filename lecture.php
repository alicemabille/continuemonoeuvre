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

?>
	<main class="bg-image pb-3 pt-2 mb-0 mt-0" style="background-image: url('<?php echo first_pixabay_fullhd($title); ?>');">
		<div class="container mt-2">
		<?php 
			if($_SESSION["session"]==true) {
				if($isset_txt){
					$txt_id = $_GET["txt_id"];
					$txt_category = $texte->__getType();
					if($_SESSION['session']==true){
                        echo $texte->txtFull();
						echo '<div class="row">';
						include "include/gif-board.inc.php";
						echo '<form class="col-md-4 col-sm-6 m-1" action="ecriture.php?txt_id='.$txt_id.'" method="post">
							<button class="btn btn-primary">Contribuer à ce texte</button>
							</form>
							</div>';
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
