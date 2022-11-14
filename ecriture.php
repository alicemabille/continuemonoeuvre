<?php 
	$titre = "écriture";
	include "include/header.inc.php";
	$isset_txt = isset($_GET["txt_id"])&&!empty($_GET["txt_id"]);
?>
	<main>
		<section class="container mt-1">
			<?php 
				if($isset_txt){
					$txt_id = $_GET["txt_id"];
					include "include/text-editor.inc.php";
				}
				else{
					echo "<p class='alert alert-warning'>Pas de texte à afficher.</p>";
				}
			?>
			<article class="row">
				<form class="col-md-4 col-sm-6 m-1" action="ecriture.php?txt_id="<?php echo ($isset_txt) ? $txt_id : "" ?> method="post">
					<button class="btn btn-primary">Contribuer à ce texte</button>
				</form>
			</article>
		</section>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
