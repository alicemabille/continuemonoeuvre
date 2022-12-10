<?php 
	$type = "website";
	$titre = "Accueil";
	include "include/header.inc.php"; 
?>
	<main class="background-image pb-3 pt-2 mb-0 mt-0" style="background-image: url(/images/writing-pixabay.jpg); margin-bottom: 0px;">
			<?php
                include "include/pixabay-search.inc.php";
            ?>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
