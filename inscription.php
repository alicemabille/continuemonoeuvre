<?php 
    $type = "website";
    $titre = "inscription";
    include "include/header.inc.php"; 
?>
	<main>
        <section class="container p-10"><h2>Inscription</h2>
            <form method="post" id="signup_field" class="needs-validation mb-5">
                <fieldset class="form-group">
                    <label for="username_input">Nom d'utilisateur</label>
                    <input name="username" type="text" id="username_input" class="signup_form form-control" maxlength="20" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="email_input">Adresse mail</label>
                    <input name="email" type="email" id="email_input" class="signup_form form-control" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="tel_input">Numéro de téléphone</label>
                    <input name="tel" type="tel" id="tel_input" class="signup_form form-control"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="birthdate_input">Date de naissance</label>
                    <input name="birthdate" type="date" id="birthdate_input" class="signup_form form-control" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="password_input">Mot de passe</label>
                    <input name="password" type="password" id="password_input" class="signup_form form-control" maxlength="30" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="password_input">Confirmer le mot de passe</label>
                    <input name="password-confirm" type="password" id="password_confirm_input" class="signup_form form-control" maxlength="30" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                    <?php echo check_signup(); ?>
                </fieldset>
                <button type="submit" class="signup_form btn btn-primary my-3" id="submit_button">S'inscrire</button>
                <p class="pb-3">Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            </form>
        </section>
		
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>