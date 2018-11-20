	<footer>
		<div class="container container-reset">
			<div class="pull-left">
				<img class="pull-left img-svg" width="90" src="<?php echo IMG_DIR; ?>/footer-logo-ybuy-dark.svg" alt="YBUY">
				<p class="pull-left">2017 - Todos os direitos reservados</p>
			</div>
			<?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
			<!-- <nav class="pull-right">
				<a href="#">Termos e Condições</a>
				<a href="#">Políticas</a>
				<a href="#">Ajuda</a>
				<a href="#">Contato</a>
			</nav> -->
		</div>
	</footer>
<?php 
	wp_footer();
?>
</body>
</html>