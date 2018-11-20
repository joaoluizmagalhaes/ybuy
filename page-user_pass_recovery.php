<?php

    define('IMG_DIR' , get_stylesheet_directory_uri() . '/__assets/img');

    $previous = "javascript:history.go(-1)";
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
    }

	global $wpdb;
        
    $error = '';
    $success = '';
    
    // check if we're in reset form
    if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) {
        $email = trim($_POST['user_login']);
        
        if( empty( $email ) ) {
            $error = 'Entre um endereço de e-mail!';
        } else if( ! is_email( $email )) {
            $error = 'E-mail inválido ou usuário!';
        } else if( ! email_exists( $email ) ) {
            $error = 'Não existe cadastro para esse e-mail!';
        } else {
            
            $random_password = wp_generate_password( 12, false );
            $user = get_user_by( 'email', $email );
            
            $update_user = wp_update_user( array (
                    'ID' => $user->ID, 
                    'user_pass' => $random_password
                )
            );
            
            // if  update user return true then lets send user an email containing the new password
            if( $update_user ) {
                $to = $email;
                $subject = 'Sua nova senha yBuy!';
                
                $message = 'Sua nova senha é: '.$random_password'<br>';
                $message .= 'Você pode trocar sua senha em "Editar Perfil".';
                
                $headers[] = 'MIME-Version: 1.0' . "\r\n";
                $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers[] = "X-Mailer: PHP \r\n";
                $headers[] = 'From: yBuy | < no-reply@ybuy.com.br >' . "\r\n";
                
                $mail = wp_mail( $to, $subject, $message, $headers );
                if( $mail ) {
                    wp_redirect(get_bloginfo('url') . '/recuperada?email="'.$email.'"');
                    exit;
                } else {
                    $error = $mail;
                    var_dump($error);
                    exit();
                }   

            } else {
                $error = 'Ops! Algo deu errado ao atualizar sua conta.';
            }
            
        }
    } ?>

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
                        <h2>Esqueceu a senha?</h2>
                        <p>Não tem problema! Fala pra gente o seu email, que vamos mandar lá os passos para recuperar.</p>
                        <form action="<?php echo get_bloginfo('url') . '/recuperar-senha/';?>" method="post">
                            <?php if( ! empty( $error ) )
                                echo '<div class="message"><p class="error">'. $error .'</p></div>'; ?>
                            <input type="email" placeholder="Email" name="user_login" id="user_login" value="<?php echo esc_attr($user_login); ?>" >
                            <button class="btn havelock-blue" type="submit">Enviar</button>
                            <input type="hidden" name="action" value="reset" />
                        </form>
                        <a href="<?php echo esc_url($previous); ?>" class="text-center">Voltar</a>
                    </section>
                </div>
            </div>
        </main>
    </body>

    <?php wp_footer();