<?php
	session_start();
	include "functions.inc.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- necessary meta -->
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/ico" href="favicon.ico">
	<link rel="stylesheet" href="style.css">

	<!-- authors -->
	<meta name="author" content="alice mabille">
	<meta name="author" content="florent courtin">
	<meta name="author" content="christella aristor">

	<!-- search engine optimizaion -->
	<?php if((active_page("index.php")=="active")||(active_page("connexion.php")=="active")||(active_page("lecture.php")=="active")){
		echo '<link rel="canonical" href="https://continuemonoeuvre.alwaysdata.net"/>';
		echo '<meta name="robots" content="index, nofollow">';
		}
		else{
			echo '<meta name="robots" content="noindex, nofollow">';
		}

		$description = "Site de création collective de textes : romans, poèmes, nouvelles, pièces de théâtre, etc. Chacun peut continuer des textes précédemment écrits par d'autres.";
	?>
	<meta name="description" content="<?php echo $description; ?>" >

	<!-- Open Graph -->
	<meta property="og:type" content="<?php echo ($type==null)?"" : $type; ?>" >
	<meta property="og:title" content="<?php echo ($titre==null)?"" : $titre;?>" >
	<meta property="og:description" content="<?php echo ($legend==null)?$description: $legend;?>" >
	<meta property="og:image" content="images/logo.png" >
	<meta property="og:url" content="<?php echo $_SERVER['REQUEST_URI']; ?>" >
	<meta property="og:site_name" content="Continue mon œuvre" >
	

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
	<!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script> const TENOR_API_KEY = <?php echo "\"".$_ENV["TENOR_API_KEY"]."\""; ?></script>
    <title>Continue mon &oelig;uvre <?php if($titre!=null){ echo " - ".$titre; }?></title>
</head>

<body class="bg-primary bg-light">
  <header>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<div class="container-fluid">
			<!-- Logo -->
			<a href="index.php" class="navbar-brand"><img src="images/logo.png" alt="Continue mon &oelig;uvre" id="logo-header" class="rounded" style="width:50px;">	Continue mon &oelig;uvre</a>
			<!-- Navbar links -->
			<ul class="navbar-nav">
				<li class='nav-item'>
					<a class='nav-link ".<?php echo active_page('recherche-images.php'); ?>."' href='recherche-images.php'>
						Chercher des images
					</a>
				</li>
			<?php
					if (isset($_SESSION['session']) && !empty($_SESSION['session']) && $_SESSION['session']) {
						// Session active
						echo "<li class='nav-item'><a class='nav-link ". active_page('profil.php?user=') ."' href='profil.php?user=". $_SESSION['username'] ."'>". $_SESSION['username'] ."</a></li>";
						echo "<li class='nav-item'><a class='nav-link ". active_page('deconnexion.php') ."' href='deconnexion.php'>Se déconnecter</a></li>";
					} else {
						// Pas de session
						echo "<li class='nav-item'><a class='nav-link ". active_page('connexion.php') ."' href='connexion.php'>Se connecter</a></li>";
						echo "<li class='nav-item'><a class='nav-link ". active_page('inscription.php') ."' href='inscription.php'>S'inscrire</a></li>";
					}
				?>
				<!-- <li class="nav-item" id="color-mode-button"><button class="nav-link" id="style_button"></button></li> -->
			</ul>
		</div>
    </nav>
  </header>