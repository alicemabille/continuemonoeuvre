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
		<meta name="author" content="alice mabille" />
		<link rel="stylesheet" href="style.css">
	</head>
	
	<body>
		<?php include "include/header.inc.php"; ?>
	<main>
		<?php
		if(!(isset($_COOKIE["aut_cookie"]))||(empty($_COOKIE["aut_cookie"]))){
			echo "
			\t\t <section> \n
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
		
		
			<h2>Exercice 1</h2>
			<article>
				<h3></h3>
				<p>
				</p>
			</article>
            <article>
				<h3></h3>
				<p>
				</p>
			</article>
		</section>
	</main>
	
	<footer>
  	<ul class="footer-list" >
  		<li class="footer-list-item">Alice MABILLE, Christella ARISTOR, Florent COURTIN</li>
  		<li class="footer-list-item">UE Développement Web Avancé</li>
  		<li class="footer-list-item">Cergy-Paris Université, UFR Sciences et Techniques</li>
    	<li class="footer-list-item">Dernière mise à jour : 7 octobre 2022</li>
  	</ul>
    </footer>
	
	</body>
</html>
