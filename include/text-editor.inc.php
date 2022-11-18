<!-- <div class='container'>
    <h3 class='header'><?php echo ucfirst(str_replace("_", " ", $txt_id)); ?></h3>
    <div class='edit-container'>
        <div id='edit' contenteditable>

        </div>
    </div>
    <div id='display' class='display-container'>
    </div>
</div> -->

<div class="form-outline m-1">
    <!-- <label class="form-label" for="editor-textArea">Écrire la suite...</label> -->
    <form action="ecriture.php?txt_id=<?php echo $txt_id; ?>&txt_category=<?php echo $txt_category; ?>" method="post" id="text-edit-form">
        <textarea class="form-control rounded shadow" name="editor-textArea" rows="4" placeholder="Écrire la suite..." form="text-edit-form" required></textarea>
        <button type="submit" class="btn btn-primary col-md-4 col-sm-6 m-1" id="editor-submit-btn">Contribuer à ce texte</button>
    </form>
    <?php check_text_edit($txt_id, $txt_category); ?>
</div>