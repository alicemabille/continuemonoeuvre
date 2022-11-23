<div class="form-outline m-1">
    <form action="ecriture.php?txt_id=<?php echo $txt_id; ?>" method="post" id="text-edit-form">
        <textarea class="form-control rounded shadow" name="editor-textArea" rows="4" placeholder="Écrire la suite..." form="text-edit-form" required></textarea>
        <button type="submit" class="btn btn-primary col-md-4 col-sm-6 m-1" id="editor-submit-btn">Publier</button>
    </form>
    <?php 
        if ($_SESSION['session']) {
            // auteur de la modification
            $editeur = $_SESSION['username'];
            // Date de la modification
            $date = date("Y-m-d H:i:s");
            $texte->checkTextEdit($editeur, $date);
        }
    ?>
</div>
