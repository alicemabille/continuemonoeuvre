<div class="container form-outline m-1">
    <form action="apercu-nouveau-texte.php" method="post" id="text-edit-form" class="row">
        <label class="text-light col-4" for="category-select">Catégorie</label>
        <select class="form-select mt-2 mb-2 col-4 col-md-4" id="category-select">
            <option value="roman">Roman</option>
            <option value="poem">Poème</option>
            <option value="haiku">Haikus</option>
        </select>
        <label class="text-light col-4" for="title-input">Titre</label>
        <input class="mt-2 mb-2" type="text" name="title" id="title-input">
        <textarea class="form-control rounded shadow" name="editor-textArea" rows="20" placeholder="Il était une fois..." form="text-edit-form" required></textarea>
        <button type="submit" class="btn btn-primary col-md-4 col-sm-6 m-1" id="editor-submit-btn">Publier</button>
    </form>
    <?php ?>
</div>