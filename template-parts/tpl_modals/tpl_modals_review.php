<div class="review_modal">
	<form method="POST" action="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
		<label for="review_title">Título da sua avaliação</label>
		<div class="form-field">
			<input type="text" placeholder="Descreva aqui sua experiência com este produto em uma frase..." name="review_title" id="review_title" required="required"><br>
		</div>
		<label for="review_pro">Prós</label>
		<div class="form-field">
			<input type="text" class="review_input" name="review_pro[]" id="review_pro" required="required">
			<span class="add_field">+</span><br>
		</div>
		<label for="review_cons">Contras</label>
		<div class="form-field">
			<input type="text" class="review_input" name="review_cons[]" id="review_cons" required="required">
			<span class="add_field">+</span><br>
		</div>
		<label for="Sua Avaliação Geral">Avaliação</label>
		<div class="form-field">
			<input type="number" name="review_rate" id="review_rate" required="required" min="1" max="5"><br>
		</div>
		<label for="review_description">Detalhes</label>
		<div class="form-field">
			<textarea name="review_description" id="review_description" cols="30" rows="10" required="required"></textarea><br>
		</div>
		<label for="review_gallery">Imagens do seu produto</label>
		<div class="form-field">
			<div class="image_gallery_wrapper">
				<img src="http://localhost:8888/yBuy/wordpress/wp-content/uploads/2017/10/Captura-de-Tela-2017-10-27-às-9.57.21-AM.png" alt="" class="image_picker">
				<input type="hidden" name="review_gallery[]" id="review_gallery">
			</div><br>
		</div>
		<input type="hidden" name="like_reviews" value="0">
		<input type="hidden" name="dislike_reviews" value="0">
		<input type="hidden" name="posts_user_likes[]" value="0">
		<input type="hidden" name="users_post_likes[]" value="0">
		<input type="hidden" name="users_post_dislikes[]" value="0">
		<input type="submit" value="Enviar Avaliação">
		<input name="action" type="hidden" id="action" value="create_review" />
		<?php  wp_nonce_field('create_review_form', 'create_review_nonce'); ?>
	</form>
</div>