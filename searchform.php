<form role="search" method="get" id="searchform" action="<?php bloginfo('url'); ?>/" class="pull-left">
	<img width="18" class="img-svg" src="<?php echo IMG_DIR; ?>/header-icon-search.svg" alt="Pesquisar">
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="Pesquisar">
	<input type="hidden" value="product" name="post_type[]" />
	<input type="hidden" value="post" name="post_type[]" />
	
</form>