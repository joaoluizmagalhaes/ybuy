<form method="POST" id="updateuser" class="user-forms" action="https://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
	<table class="form-table">
		<tr class="form-field">  
			<th>
				<label for="user_profile_picture"><?php _e( 'Foto do perfil' ); ?></label>
			</th>
			<td class="image-container">
				<img src="<?php echo ($user_meta['user_profile_picture'][0] !== "" ? esc_html($user_meta['user_profile_picture'][0]) : ""); ?>" alt="" class="user_profile_picture_src" style="max-width: 100px;">
				<div class="buttons-container">
					<input type="hidden" name="user_profile_picture" id="user_profile_picture" class="user_profile_picture" value="<?php echo ($user_meta['user_profile_picture'][0] !== "" ? esc_html($user_meta['user_profile_picture'][0]) : ""); ?>">
					<button class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" >Trocar Imagem</button>
					<a class="remove-image" href="#">Remover Imagem</a>
				</div>
			</td>
		</tr>
	</table>
	<table class="form-table">
		<tr class="form-field">  
	        <th>
       			<label for="first_name"><?php _e('Primeiro Nome'); ?></label>
       		</th>
	        <td>
	        	<input type="text" name="first_name" id="first_name" value="<?php echo ($user_meta['first_name'][0] !== "" ? esc_html($user_meta['first_name'][0]) : ""); ?>">
	        </td>
		</tr>
		<tr class="form-field">  
	        <th>
       			<label for="last_name"><?php _e('Último Nome'); ?></label>
       		</th>
	        <td>
	        	<input type="text" name="last_name" id="last_name" value="<?php echo ($user_meta['last_name'][0] !== "" ? esc_html($user_meta['last_name'][0]) : ""); ?>">
	        </td>
		</tr>
		<tr class="form-field">  
			<th>
				<label for="user_email"><?php _e( 'E-mail' ); ?></label>
			</th>
			<td>
				<input type="email" name="user_email" id="user_email" class="user_email" value="<?php echo ($current_user->user_email !== "" ? esc_html($current_user->user_email) : ""); ?>">
				<span class="alert-msg"><?php echo $_POST['error']['email'] ?></span>
			</td>
		</tr>
		<tr class="form-field">  
			<th>
       			<label for="user_pass1"><?php _e('Senha'); ?></label>
       		</th>
	        <td>
	        	<input type="password" name="user_pass1" id="user_pass1" value="<?php echo ($current_user->user_pass !== "" ? esc_html($current_user->user_pass) : ""); ?>">
	        	<span class="alert-msg"><?php echo $_POST['error']['password'] ?></span>
	        </td>
	    </tr>
	    <tr class="form-field">  
	        <th>
       			<label for="user_pass2"><?php _e('Repita a Senha'); ?></label>
       		</th>
	        <td>
	        	<input type="password" name="user_pass2" id="user_pass2" value="<?php echo ($current_user->user_pass !== "" ? esc_html($current_user->user_pass) : ""); ?>">
	        </td>
		</tr>
	</table>
	<input type="hidden" name="action" value="update-user">
	<input type="submit" value="Salvar Alterações" class="save-edit">
	<?php  wp_nonce_field('user_edit_form', 'user_edit_nonce'); ?>
</form>