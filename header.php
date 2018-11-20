<?php define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img'); ?>
<!DOCTYPE html>
<html lang="pt_br" class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109825748-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-109825748-1');
	</script>
	<meta name="lomadee-verification" content="22767041" />
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
	
	<title>YBUY</title>

	<meta name="robots" content ="index, follow">

	<?php wp_head(); ?>

	<!-- LATO FONT -->
	<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">

	<!-- FAVICON -->
	<link href="<?php bloginfo('template_url'); ?>/favicon.ico" rel="shortcut icon"/>
	<link href="<?php bloginfo('template_url'); ?>/favicon.ico" rel="icon" type="image/x-icon"/>

</head>
<body <?php body_class(); ?>>

	<?php if( is_category() || is_singular('product') ) { ?>	
		<header>	
	<?php } else { ?>	
		<header id="fixed">	
	<?php } ?>	

	<?php global $current_user; ?>

	<?php $user_meta = get_user_meta($current_user->ID); ?>

		<div class="pull-left">
			<h1 class="pull-left"><a class="text-hide" href="<?php echo get_bloginfo('url'); ?>" title="Home">YBUY<img width="90" class="img-svg" src="<?php echo IMG_DIR; ?>/header-logo-ybuy-white.svg" alt="YBUY"></a></h1>
			<!-- <button class="pull-left">Departamentos<img width="8" class="img-svg" src="<?php echo IMG_DIR; ?>/header-arrow-down.svg" alt="YBUY - Departamentos"></button> -->
			<div class="form-wrapper">
				<?php get_search_form(); ?>
			</div>
		</div>
		<div class="pull-right">
			<a class="menu-icon text-hide search-button" href="#">Buscar<img width="20" class="img-svg" src="<?php echo IMG_DIR; ?>/header-icon-search.svg" alt="busca"></a>
			<?php if (is_user_logged_in()) { ?>
				<a class="menu-icon text-hide menu-button" href="#">Notificaçãoes<img width="20" class="img-svg sanduiche" src="<?php echo IMG_DIR; ?>/header-icon-menu-mobile.svg" alt="menu"></a>
				<a class="menu-icon text-hide close-button" href="#">Close<img width="18" class="img-svg close" src="<?php echo IMG_DIR; ?>/close.svg" alt="menu"></a>
				<div id="box-user">
					<img class="pull-left" src="<?php echo $user_meta['user_profile_picture'][0]; ?>" alt="YBUY">
					<span class="pull-left"><?php echo $user_meta['first_name'][0]; ?></span>
					<img width="8" class="img-svg" src="<?php echo IMG_DIR; ?>/header-arrow-down.svg" alt="YBUY">
					<ul>
						<li><a href="<?php echo get_bloginfo('url') . '/perfil' ?>">Ver Perfil</a></li>
						<li><a href="<?php echo get_bloginfo('url') . '/editar-perfil' ?>">Editar Perfil</a></li>
						<li><a href="<?php echo wp_logout_url(home_url()); ?>">Sair</a></li>
					</ul>
				</div>
			<?php } else { ?>
	  			<a class="cadastre"  <?php echo 'href="' . get_bloginfo('url') . '/cadastro/">Cadastre-se'; ?></a>
				<a class="login" <?php echo 'href="' . get_bloginfo('url') . '/login">Login'; ?></a> 
				<!--<a class="notification text-hide" href="#">Notificaçãoes<img width="18" class="img-svg" src="<?php echo IMG_DIR; ?>/header-icon-notification.svg" alt="Notificações"></a>
				<a class="wishlist text-hide" href="#">Wishlist<img width="18" class="img-svg" src="<?php echo IMG_DIR; ?>/header-icon-wishlist.svg" alt="Wishlist"></a>-->
			<?php } ?>
		</div>
	</header>