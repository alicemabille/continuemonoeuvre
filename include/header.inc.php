<?php include "functions.inc.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
	<meta name="author" content="alice mabille"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/ico" href="favicon.ico"/>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" />
	<!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script> const TENOR_API_KEY = <?php echo "\"".$_ENV["TENOR_API_KEY"]."\""; ?></script>
    <title>Continue mon &oelig;uvre <?php if($tire!=null){ echo " - ".$titre; }?></title>
</head>

<body class="bg-primary bg-light">
  <header>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<!-- Logo -->
		<a href="index.php" class="navbar-brand"><img src="images/logo.png" alt="Continue mon &oelig;uvre" id="logo-header" class="rounded" style="width:50px;"/></a>
		<!-- Navbar links -->
		<ul class="navbar-nav">
			<li class="nav-item" id="signin-nav-item"><a class="nav-link <?php active_page("connexion.php");?>" href="connexion.php">Se connecter</a></li>
			<li class="nav-item" id="signup-nav-item"><a class="nav-link <?php active_page("inscription.php");?>" href="inscription.php">S'inscrire</a></li>
			<!-- <li class="nav-item" id="color-mode-button"><button class="nav-link" id="style_button"></button></li> -->
      	</ul>
    </nav>
    <h1>Continue mon &oelig;uvre</h1>
  </header>