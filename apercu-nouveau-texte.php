<?php 
	$titre = "nouveau texte - aperçu";
	include "include/header.inc.php";
?>
	<main class="background-image pb-3" style="background-image: url(/images/writing-pixabay2.jpg); margin-bottom: 0px;">
        <section class="container pt-2 pb-2">
            <h2 class="text-light">Aperçu</h2>
        </section>
		<section class="container pt-2 pb-2">
			<?php 
                if($_SESSION["session"]==true) {
                    if(isset($_POST["editor-textArea"])&&!empty($_POST["editor-textArea"])){
                        $title = (isset($_POST["title"])&&!empty($_POST["title"]))? $_POST["title"] : explode(" ",$_POST["editor-textArea"])[0];
						spl_autoload_register(function ($classe) {
							include('classes/'. $classe .'.class.php');
						});
						$date = date("Y-m-d H:i:s");
						$id = Texte::creerTexte($title, $_POST["editor-textArea"], $_SESSION['username'], $date, $_POST['category']);
						$texte = new Texte($id);
						echo $texte->txtFull();
						echo "<div class='container row'>
						<a href='lecture.php?txt_id=".$texte->__getId()."' class='btn btn-primary col m-5'>Voir la page de lecture</a>
						<a href='index.php' class='btn btn-primary col m-5 '>Accueil</a>
						</div>";
                    }
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