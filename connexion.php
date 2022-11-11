<?php 
	$titre = "connexion";
	include "include/header.inc.php"; 
?>
	<main>
        <section><h2>Connexion</h2>
            <form id="signin_form" method="post">
                <label for="username_input">Nom d'utilisateur : </label>
                <input name="username" type="text" id="username_input" class="signin_form_field"/>
                <label for="password_input">Mot de passe : </label>
                <input name="password" type="password" id="password_input" class="signin_form_field"/>
                <?php $return = check_signin(); ?>
				<input type="submit"  id="submit_button"/>
            </form>
            <?php
                echo "<p>". $return ."</p>";
            ?>
        </section>
	</main>
	
	<?php include "include/footer.inc.php"; ?>
	
	</body>
</html>