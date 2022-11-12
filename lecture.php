<?php 
	$titre = "lecture";
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
					echo txt_full($txt_id,$txt_category);
				}
				else{
					echo "<p class='alert alert-warning'>Pas de texte à afficher.</p>";
				}
			?>
			<article class="row">
				<?php include "include/gif-board.inc.php"; ?>
				<form class="col-md-4 col-sm-6 m-1" action="ecriture.php?txt_id="<?php echo ($isset_txt) ? $txt_id : "" ?> method="post">
					<button class="btn btn-primary">Contribuer à ce texte</button>
				</form>
			</article>
		</section>
	</main>
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
