<?php 

	$url = $_SERVER['REQUEST_URI'];

	if(strpos($url, 'validacao') !== false) {
		if(isset($_GET['email']) && isset($_GET['hash'])) {
			$user_email = esc_html($_GET['email']);
			$user_hash = esc_html($_GET['hash']);

			$user = get_user_by('login', $user_email);

			$user_meta = get_user_meta($user->ID);

			if('inactive' === $user_meta['status'][0] && $user_hash === $user_meta['user_hash'][0] ) {
				update_user_meta($user->ID, 'status', 'active');
				define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img');
				?>
				<head>
					<meta charset="utf-8">
				    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
					
					<title>YBUY</title>

					<?php wp_head(); ?>

					<!-- LATO FONT -->
					<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">

				</head>

				<body class="gray">
					<main id="forgotpassword">
						<div class="container container-reset">
							<div class="row">
								<section class="col-xs-12 col-lg-offset-4 col-lg-4">
									<h1><a class="text-hide" href="/" title="Home">YBUY<img class="img-svg" width="90" src="<?php echo IMG_DIR; ?>/footer-logo-ybuy-dark.svg" alt="YBUY"></a></h1>
									<h2>Cadastro ativado com sucesso!</h2>
									<p>Agora você já pode se logar no yBuy!</p>
									<button class="btn havelock-blue" onclick="window.location.href='<?php echo get_bloginfo('url') . '/login'?>'">Login</button>
									<a href="<?php echo esc_url($previous); ?>" class="text-center">Voltar</a>
								</section>
							</div>
						</div>
					</main>
				</body>

				<?php wp_footer();
			}

		} else {
			wp_redirect(home_url());
			exit;
		}
	}
	