<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8" />
		<title>Continue mon &oelig;uvre - connexion</title>
		<meta name="author" content="alice mabille"/>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/ico" href="favicon.ico"/>
	</head>
	
	<body>
		<?php include "include/header.inc.php"; ?>
	<main>
        <section><h2>Connexion</h2>
            <form id="signin_form">
                <label for="username_input">Nom d'utilisateur : </label>
                <input type="text" id="username_input" class="signin_form_field"/>
                <label for="password_input">Mot de passe : </label>
                <input type="password" id="password_input" class="signin_form_field"/>
				<input type="submit"  id="submit_button"/>
            </form>
        </section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>