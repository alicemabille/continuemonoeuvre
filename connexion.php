<?php 
	$titre = "connexion";
	include "include/header.inc.php"; 
?>
	<main>
        <section class="container p-10"><h2>Connexion</h2>
            <form method="post" id="signin_form" class="needs-validation">
                <fieldset class="form-group">
                    <label for="username_input">Nom d'utilisateur</label>
                    <input name="username" type="text" id="username_input" class="signin_form form-control" maxlength="20" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
                <fieldset class="form-group">
                    <label for="password_input">Mot de passe</label>
                    <input name="password" type="password" id="password_input" class="signup_form form-control" maxlength="30" required/>
                    <p class="invalid-feedback">Veuillez compléter ce champ.</p>
                </fieldset>
				<button type="submit" class="signin_form btn btn-primary my-3" id="submit_button">Se connecter</button>
            </form>
        </section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>