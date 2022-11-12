			<div class="bg-secondary rounded p-3 col-md-4 col-sm-6">
				<p class="text-info rounded p-3 bg-dark text-white">Ajouter une réaction <small class="text-muted">Powered by Tenor</small></p>
				<div class="row form-group">
					<button id="featured_gif_button" class="btn btn-dark dropdown col m-1">À la une</button>
					<input type="text" id="search_gif_input" class="col m-1 form-control"/>
					<button id="search_gif_button" class="btn btn-dark col m-1">Rechercher</button>
				</div>
				<form action="lecture.php?txt_id=<?php echo (isset($_GET["txt_id"])&&!empty($_GET["txt_id"])) ? $_GET["txt_id"] : ""; ?>" method="post">
					<div id="gif-list" class="row col-md-4 col-sm-12 bg-secondary rounded shadow">
					</div>
				</form>
			</div>