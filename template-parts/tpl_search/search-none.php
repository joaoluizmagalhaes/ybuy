<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Não encontramos =(', 'yubuy-tema' ); ?></h1>
	</header>

	<div class="page-content">
		<?php
		if ( is_search() ) : ?>

			<p><?php esc_html_e( 'Desculpe, mas não encontramos o que você procura, tente uma nova busca com palavras diferentes
			.', 'yubuy-tema' ); ?></p>
		<?php else : ?>

			<p><?php esc_html_e( 'Pare que não pudemos encontrar o que você queria, talvez a busca possa te ajudar.', 'yubuy-tema' ); ?></p>
		<?php endif; ?>
	</div>
</section>