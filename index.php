<!-- <php 
	if((isset($_GET["autcookie"]))&&(!empty($_GET["autcookie"]))){
		setcookie("aut_cookie",$_GET["autcookie"],time()+60*60*24*180);
	}
?> -->

<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8" />
		<title>Continue mon &oelig;uvre</title>
		<meta name="author" content="alice mabille"/>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/ico" href="favicon.ico"/>
	</head>
	
	<body>
		<?php include "include/header.inc.php"; ?>
	<main>
		<section>
			<h2>Poste un texte et laisse les autres le continuer ! Ajoute ton grain de sel et crée des œuvres aux côtés d'autres internautes !</h2>
		</section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>
