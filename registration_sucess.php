<?php 
	define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img'); 

	if(isset($_GET['email'])) {
		$email = $_GET['email'];
	} else {
		wp_redirect(home_url());
		exit;
	}

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
						<h2>Cadastro realizado com sucesso!</h2>
						<p>Você precisa ativar a sua conta e já te enviamos um email para <?php echo $email; ?> informando como ativá-la.</p>
						<button class="btn havelock-blue" onclick="window.location.href='<?php echo get_bloginfo('url') . '/login'?>'">Login</button>
						<a href="<?php echo esc_url($previous); ?>" class="text-center">Voltar</a>
					</section>
				</div>
			</div>
		</main>
	</body>

	<?php wp_footer(); ?>