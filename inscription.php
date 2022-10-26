<?php 
    $titre = "inscription";
    include "include/header.inc.php"; 
?>
	<main>
        <section class="container p-10"><h2>Inscription</h2>
            <form id="signup_field" class="needs-validation">
                <fieldset class="form-group">
                    <label for="username_input">Nom d'utilisateur</label>
                    <input type="text" id="username_input" class="signup_form form-control" maxlength="20" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="email_input">Adresse mail</label>
                    <input type="email" id="email_input" class="signup_form form-control" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="tel_input">Numéro de téléphone</label>
                    <input type="tel" id="tel_input" class="signup_form form-control"/>
                </fieldset>
                <fieldset class="form-group">
                    <label for="birthdate_input">Date de naissance</label>
                    <input type="date" id="birthdate_input" class="signup_form form-control" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="password_input">Mot de passe</label>
                    <input type="password" id="password_input" class="signup_form form-control" maxlength="30" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="password_input">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirm_input" class="signup_form form-control" maxlength="30" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <button type="submit" class="signup_form btn btn-primary my-3" id="submit_button">S'inscrire</button>
            </form>
        </section>
		
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>