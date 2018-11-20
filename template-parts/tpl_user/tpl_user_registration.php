<?php
	define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img');
	//default wp loggin form, with all atuthentications
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
		<div id="container">
			<main id="register">
				<div class="container container-reset">
					<div class="row">
						<section class="col-xs-12 col-lg-offset-4 col-lg-4 loging-form-wrapper">
							<h1><a class="text-hide" href="/" title="Home">YBUY<img class="img-svg" width="90" src="<?php echo IMG_DIR; ?>/footer-logo-ybuy-dark.svg" alt="YBUY"></a></h1>
							<!-- <button class="btn havelock-blue">Cadastrar com Facebook</button>
							<button class="btn wild-watermelon">Cadastrar com Google</button>
							<hr class="pull-left">
							<p class="pull-left">ou cadastrar com email</p>
							<hr class="pull-right"> -->
							<form method="post" id="adduser" class="user-forms" action="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
								<input type="text" placeholder="Primeiro Nome" name="first_name" id="first_name" required="required" value="<?php echo($_POST['first_name']); ?>">
								<input type="text" placeholder="Último Nome" name="last_name" id="last_name" required="required" value="<?php echo($_POST['last_name']); ?>">
								<input type="email" placeholder="Email" name="email" id="email" required="required" value="<?php echo($_POST['email']); ?>">
								<input type="password" placeholder="Senha" name="user_pass1" id="user_pass1" required="required">
								<input type="password" placeholder="Repita sua senha" name="user_pass2" id="user_pass2" required="required">
								<input type="submit" value="Cadastrar">
								<input name="action" type="hidden" id="action" value="adduser" />
								<input type="hidden" name="status" id="status" value="inactive">
								<?php  wp_nonce_field('user_registration_form', 'user_register_nonce'); ?>
							</form>
						</section>
					</div>
				</div>
			</main>
		</div>
	</body>

	<?php wp_footer(); ?>