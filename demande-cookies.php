<?php
    if(!(isset($_COOKIE["aut_cookie"]))||(empty($_COOKIE["aut_cookie"]))){
        echo "
        \t\t <section class=\"cookie-demand-section\"> \n
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