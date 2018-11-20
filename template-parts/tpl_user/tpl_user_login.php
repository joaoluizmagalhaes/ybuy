<?php
	define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img');
	//default wp loggin form, with all atuthentications
	?>
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109825748-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-109825748-1');
		</script>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
		
		<title>YBUY</title>

		<?php wp_head(); ?>

		<!-- LATO FONT -->
		<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">

		<!-- FAVICON -->
		<link href="<?php bloginfo('template_url'); ?>/favicon.ico" rel="shortcut icon"/>
		<link href="<?php bloginfo('template_url'); ?>/favicon.ico" rel="icon" type="image/x-icon"/>

	</head>
	
	<body class="gray">
		<main id="login">
			<div class="container container-reset">
				<div class="row">
					<section class="col-xs-12 col-lg-offset-4 col-lg-4">
						<h1><a class="text-hide" href="/" title="Home">YBUY<img class="img-svg" width="90" src="<?php echo IMG_DIR; ?>/footer-logo-ybuy-dark.svg" alt="YBUY"></a></h1>
						<span class="login-failed"><?php echo (isset($_GET['failed'])) ? 'Usuário ou senha não conferem, por favor tente novamente!' : ''; ?></span>
						<!-- <button class="btn havelock-blue">Entrar com Facebook</button>
						<button class="btn wild-watermelon">Entrar com Google</button>
						<hr class="pull-left">
						<p class="pull-left">ou entrar com email</p>
						<hr class="pull-right"> -->
							<?php
							$args = array(
		                        'echo'           => true,
		                        'redirect'       => get_bloginfo('url').'/perfil/', 
		                        'form_id'        => 'loginform',
		                        'label_username' => __( '' ),
		                        'label_password' => __( '' ),
		                        'label_remember' => __( 'Remember Me' ),
		                        'label_log_in'   => __( 'Login' ),
		                        'id_username'    => 'user_login',
		                        'id_password'    => 'user_pass',
		                        'id_remember'    => 'rememberme',
		                        'id_submit'      => 'wp-submit',
		                        'remember'       => true,
		                        'value_username' => NULL,
		                        'value_remember' => true
		                    ); 


							if(isset($_GET['next_page'])){
								$next_page = esc_url($_GET['next_page']);

								$args = array ( 'redirect' => $next_page );

								wp_login_form($args); ?>
								<a href="<?php echo get_bloginfo('url') . '/recuperar-senha/'; ?>" title="Esqueci minha senha">Esqueci minha senha</a>
							<?php } else { 
								
								 wp_login_form($args); ?>
								<a href="<?php echo get_bloginfo('url') . '/recuperar-senha/'; ?>" title="Esqueci minha senha">Esqueci minha senha</a>
							<?php } ?>
							<!-- <input type="text" placeholder="Usuário">
							<input type="password" placeholder="Senha"> -->
							<!-- <button class="btn havelock-blue">Login</button> -->
						<a href="<?php echo get_bloginfo('url') . '/cadastro'?>" class="text-center">Ainda não tenho uma conta</a>
					</section>
				</div>
			</div>
		</main>
	</body>

<?php wp_footer(); ?>
