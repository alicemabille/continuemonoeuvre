<!-- <php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?> -->

<?php 
	$type = "website";
	$titre = "Accueil";
	include "include/header.inc.php"; 
?>
	<main class="background-image pb-3 pt-2 mb-0 mt-0" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
		<section class="container m-2">
			<?php
                include "include/pixabay-search.inc.php";
            ?>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
