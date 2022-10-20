<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8" />
		<title>Continue mon &oelig;uvre - inscription</title>
		<meta name="author" content="alice mabille"/>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/ico" href="favicon.ico"/>
	</head>
	
	<body>
		<?php include "include/header.inc.php"; ?>
	<main>
        <section><h2>Inscription</h2>
            <form id="signup_field">
                <label for="username_input">Nom d'utilisateur</label>
                <input type="text" id="username_input" class="signup_form" maxlength="20"/>
                <label for="email_input">Adresse mail</label>
                <input type="email" id="email_input" class="signup_form"/>
                <label for="tel_input">Numéro de téléphone</label>
                <input type="tel" id="tel_input" class="signup_form"/>
                <label for="birthdate_input">Date de naissance</label>
                <input type="date" id="birthdate_input" class="signup_form"/>
                <label for="password_input">Mot de passe</label>
                <input type="password" id="password_input" class="signup_form" maxlength="30"/>
                <input type="submit" id="submit_button" class="signup_form"/>
            </form>
        </section>
		
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>