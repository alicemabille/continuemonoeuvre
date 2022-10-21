<?php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8" />
		<title>Continue mon &oelig;uvre</title>
		<meta name="author" content="alice mabille"/>
		<link rel="stylesheet" href="styles.css">
	</head>
	
	<body>
		<?php include "include/header.inc.php"; ?>
	<main>
		<?php
		if(!(isset($_COOKIE["aut_cookie"]))||(empty($_COOKIE["aut_cookie"]))){
			echo "
			\t\t <section class=\"cookie-demand-section\"> \n
				\t\t\t <h2>Autoriser les cookies</h2> \n
				\t\t\t <article> \n
					\t\t\t\t <h3>Voulez-vous autoriser les cookies sur ce site ?</h3> \n
					\t\t\t\t <p>Ils serviront uniquement à garder un style consistant sur le site.</p> \n
					\t\t\t\t <a href=\"index.php?autcookie=yes\"/>Oui</a>
					\t\t\t\t <a href=\"index.php?autcookie=no\"/>Non</a>
				\t\t\t </article> \n
			\t\t </section> \n";
		}
		?>
		
		<section class="container">
			<h2>Dessinez !</h2>
            <div id="toolbar">
                <label for="stroke">Stroke</label>
                <input id="stroke" name="stroke" type="color">
                <label for="lineWidth">Line width</label>
                <input id="lineWidth" name="lineWidth" type="number" value="5">
                <button id="clear">Clear</button>
            </div>
            <div class="drawing-board">
                <canvas id="drawing-board">Votre navigateur ne vous permet pas de dessiner. Désolés.</canvas>
            </div>
		</section>
        <script src="scripts/creation-dessin.js"></script>

        
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
