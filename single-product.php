<?php 

    global $current_user;

    //if the form is correctly send, save the reveiw
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] === 'create_review' && wp_verify_nonce( $_POST['create_review_nonce'], 'create_review_form' )) {

        $product_id = get_the_id();

        // the args for the query to check if the author have created an review for this product
        $args = array(
            'author'     => $current_user->ID,
            'post_type'  => 'review',
            'meta_key'   => 'product_id_reviews',
            'meta_value' => $product_id
        );

        //get the product ID (need to be before the insert post function)
        $product_reviews = new WP_Query($args);

        //check if the user have already create a review for this product
        if(!$product_reviews->have_posts()){

        	$post_information = array (
        			'post_title'      => $_POST['review_title'],
        			'post_content'    => $_POST['review_description'],
        			'post_type'       => 'review',
        			'post_status'     => 'publish'
        	);

            //create the new review post type`
        	$new_review_id = wp_insert_post($post_information);

            //if have no errors, save the post mewta
        	if(is_wp_error($new_review_id)) {
                echo $new_review_id->get_error_message();
            } else {           
               
        		$gallery_items = array();
                $cons_items = array();
                $pros_items = array();
                $users_post_likes = array();
                $users_post_dislikes = array();

        		if(is_array($_POST['review_gallery'])) {
        			foreach ($_POST['review_gallery'] as $key => $gallery_item) {
        				$gallery_items[$key] = sanitize_text_field($gallery_item);
        			}
        		}

                if(is_array($_POST['review_cons'])) {
                    foreach ($_POST['review_cons'] as $key => $cons_item) {
                        $cons_items[$key] = sanitize_text_field($cons_item);
                    }
                }

                if(is_array($_POST['review_pro'])) {
                    foreach ($_POST['review_pro'] as $key => $pros_item) {
                        $pros_items[$key] = sanitize_text_field($pros_item);
                    }
                }

               if(is_array($_POST['users_post_likes'])) {
                    foreach ($_POST['users_post_likes'] as $key => $user_post_likes) {
                        $users_post_likes[$key] = sanitize_text_field($user_post_likes);
                    }
                }
                if(is_array($_POST['users_post_dislikes'])) {
                    foreach ($_POST['users_post_dislikes'] as $key => $user_post_dislikes) {
                        $users_post_dislikes[$key] = sanitize_text_field($user_post_dislikes);
                    }
                }

                update_post_meta($new_review_id, 'product_review_rate', sanitize_text_field($_POST['review_rate']));
        		update_post_meta($new_review_id, 'gallery_item', $gallery_items);
    			update_post_meta($new_review_id, 'product_id_reviews', $product_id);
    			update_post_meta($new_review_id, 'cons_reviews', $cons_items);
    			update_post_meta($new_review_id, 'pros_reviews', $pros_items);
                update_post_meta($new_review_id, 'like_reviews', sanitize_text_field($_POST['like_reviews']));
                update_post_meta($new_review_id, 'dislike_reviews', sanitize_text_field($_POST['dislike_reviews']));
                update_post_meta($new_review_id, 'users_post_likes', $users_post_likes);
                update_post_meta($new_review_id, 'users_post_dislikes', $users_post_likes);
    			
        	}
        } else {
            echo 'Você já avaliou esse produto.';
        }
    }

    //create a varible to check is the user is logged, if not, when the user try to create a review, the user will be redirected to loggin page
    $logged = is_user_logged_in();

    get_header(); 
	?>

    <?php while (have_posts()): the_post(); 

        $post_meta = get_post_meta(get_the_ID()); 
        $categories = get_the_category();
        $brand = get_the_terms(get_the_ID(), 'brands');
            
        $has_subcategory = false;

        foreach ($categories as $category) {
            if($category->category_parent > 0){
                $has_subcategory = true;
                $the_category = $category;
                $category_parent_id = $category->parent;
            }
        } 

        ?>

        <section id="sub-header">
            <div class="pull-left">
                <strong>
                    <?php 
                        the_title();
                        
                        if ($has_subcategory) {
                            $category_parent = get_term($the_category->parent, 'category');
                            echo '<span>Em <a href="' . get_bloginfo('url') . '/categoria/' . $category_parent->slug . '">' . $category_parent->name .' </a> > <a href="' . get_bloginfo('url') . '/categoria/' . $the_category->slug . '">' . $the_category->cat_name .' </a>';
                        } else {
                            echo '<span>Em <a href="' . get_bloginfo('url') . '/categoria/' . $categories[0]->slug . '">' . $categories[0]->cat_name . '</a>';
                        } 
                    ?>
                </strong>
            </div>
           <!--  <nav class="pull-right">
                <?php foreach ($dynamicAnchorMenu as $value) { ?>
                    <a href="#"><?php echo $value; ?> </a>
                <?php } ?>
                <?php if ($buttonWhereToFind) { ?>
                    <a class="btn btn-small havelock-blue" href="#">Onde Encontrar</a>  
                <?php } ?>
            </nav> -->
        </section>
        <main id="product">
            <section id="product-header">
                <div class="pull-left gallery-container">
                    <?php $gallery = unserialize($post_meta['gallery_item'][0]); ?>
                    <?php $youtube_videos = unserialize($post_meta['product_youtube'][0]); ?>
                    <?php $count = 0; ?>
                    <img class="img-responsive gallery-item-active" src="<?php echo esc_url(reset($gallery)); ?>" alt="YBUY" data-active= "<?php echo esc_html($count+1);?>">
                    <?php /*<img class="img-svg gallery-expand-icon" src="<?php echo IMG_DIR; ?>/icon-arrow-expand.svg" alt="">*/ ?>
                    <a class="gallery-next-button" href="#"><img src="<?php echo IMG_DIR; ?>/icon-arrow-carousel-right.svg" alt="" class="gallery-next-icon img-svg"></a>
                    <a class="gallery-prev-button" href="#"><img src="<?php echo IMG_DIR; ?>/icon-arrow-carousel-left.svg" alt="" class="gallery-prev-icon disabled img-svg"></a>
                    <div class="gallery-thumb-wrapper">
                        <?php foreach ($gallery as $key => $gallery_item) { 

                            //getting the type of the media
                            $media_type = get_post_mime_type($key); 

                            //testing the media type, video or image
                            if(false !== strpos($media_type, 'video')) { ?>
                                <img class="video-thumb" id="<?php echo esc_attr($item); ?>" src="http://localhost:8888/yBuy/wordpress/wp-includes/images/media/video.png">
                                <span class="gallery-video-title"><?php echo esc_html(get_the_title($key)); ?></span>
                                
                            <?php } else if (false !== strpos($media_type, 'image')){ ?>
                                <img src="<?php echo esc_url($gallery_item); ?>" alt="" class="gallery-item-thumb <?php echo $count === 0 ? 'active' : '' ?>" data-active= "<?php echo esc_html($count+1);?>">
                            <?php } ?>
                            <?php $count++; ?>
                        <?php } ?>

                        <?php foreach ($youtube_videos as $key => $video) { ?>
                            <?php $count++; ?>
                            <img src="https://img.youtube.com/vi/<?php echo esc_attr($video); ?>/0.jpg" alt="" class="gallery-item-thumb youtube-video <?php echo $count === 0 ? 'active' : '' ?>" data-active= "<?php echo esc_html($count);?>" data-video-id="<?php echo esc_attr($video); ?>">
                        <?php } ?>

                    </div>
                </div>
                <div class="pull-right">
                    <section>
                        <header class="hidden-xs">
                            <h2 class="pull-left"><?php echo get_the_title(); ?></h2>
                            <!-- <a class="pull-right" href="#"><img width="18" class="img-svg" src="<?php echo IMG_DIR; ?>/product-header-favorite-heart.svg" alt=""> Adicionar aos favoritos</a> -->
                            <h3><a href="<?php echo get_bloginfo('url') . '/marcas/' . esc_html($brand[0]->slug); ?>"><?php echo esc_html($brand[0]->name); ?></a></h3>
                        </header>
                        <section id="review">
                           <!-- <div class="pull-left">
                                <strong>4.8</strong>
                                <span>Excelente</span>
                                <footer>
                                    <div class="review-element">
                                        <img width="30" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="30" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="30" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="30" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="30" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                    </div>
                                </footer>
                            </div> -->
                            <p class="pull-left">Preço médio deste produto: <br>
                                <strong>R$ <span class="min-price">0</span> até R$ <span class="max-price">0</span></strong> <br>
                                <img width="18" class="img-svg" src="<?php echo IMG_DIR; ?>/product-coin.svg" alt=""><span>Valor <b>alto</b> em comparação a produtos semelhantes</span>
                            </p>
                        </section> 
                        <section id="tags">

                            <p>Veja o que já falamos sobre este produto: </p>
                           <?php 
                                for($i = 0; $i < count(unserialize($post_meta['product_collection'][0])['title']); $i++) { ?>
                                    <a href="<?php echo esc_url(unserialize($post_meta['product_collection'][0])['link'][$i]); ?>"><?php echo esc_html(unserialize($post_meta['product_collection'][0])['title'][$i]); ?></a>
                                <?php }
                           ?>
                        </section>
                        <p><?php the_content(); ?></p>
                        <!-- <section id="review-slider">
                            <div id="sliders">
                                <div class="slider">
                                    <img src="<?php echo IMG_DIR; ?>/examples/review-slider/1.jpg" alt="YBUY">
                                    <h4>Brandon Jackson</h4>
                                    <div class="review-element">
                                        <img width="10" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="10" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="10" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="10" class="img-svg full" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <img width="10" class="img-svg empty" src="<?php echo IMG_DIR; ?>/rating-star-empty.svg" alt="">
                                        <strong>4.7</strong>
                                    </div>
                                    <strong>“Melhor compra que fiz esse ano, sem dúvidas.”</strong>
                                </div>
                            </div>
                            <div id="sliders-count">
                                <span class="active"></span>
                                <span></span>
                                <span></span>
                            </div>
                        </section> -->
                        <a class="btn btn-big havelock-blue" href="#list-where-to-find">Onde Encontrar</a>
                    </section>
                </div>
            </section>
            <div class="container container-reset">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Produtos (antes de lojas)") ) :
                    endif; ?>
            </div>
            <div class="full-wide">
                <div class="container container-reset">
                    <section id="list-where-to-find">
                        <h3 class="list-title">Onde Encontrar</h3>
                        <div class="row">
                            <div class="product-offer-id" style="display: none" data-product-id="<?php echo esc_attr($post_meta['product_seller_id'][0]); ?>"></div>
                            <?php /* for($i = 0; $i < count(unserialize($post_meta['product_seller'][0])['store']); $i++) { ?>
                                <article class="col-xs-6 col-sm-6 col-md-4 col-lg-2">
                                    <a href="<?php echo esc_url(unserialize($post_meta['product_seller'][0])['link'][$i]); ?>" target="blank">
                                        <figure>
                                            <img src="<?php echo esc_url(unserialize($post_meta['product_seller'][0])['logo'][$i]); ?>" alt="YBUY">
                                        </figure>
                                        <hr>
                                        <p>À partir de <br><strong><?php echo esc_html(unserialize($post_meta['product_seller'][0])['price'][$i]); ?></strong></p></a>
                                    </a>
                                </article>
                            <?php } */?>
                        </div>
                    </section>
                </div>
            </div>
            <div class="container container-reset">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Produtos (depois de lojas)") ) :
                    endif; ?>
            </div>
        </main>
        <?php

        endwhile; ?>
            
        <!-- <button id="add_review" data-logged="<?php echo $logged; ?>">Fazer Avaliação</button>
        <p class="yuui" data-yuui="<?php echo $current_user->ID ?>" hidden></p><br>
         -->
        <?php

       /* //get the review modal template part 
        get_template_part('template-parts/tpl_modals/tpl_modals_review');

        $product_id = get_the_id();

         //get the 'reviews rate' for the current product
        $request = wp_remote_get(get_site_url() . '/reviews-rate-custom/' . $product_id);
        $response = json_decode(wp_remote_retrieve_body($request));
        echo wp_kses($response->html, html_allowed());


        //list all 'reviews' for the current product
        $request = wp_remote_get(get_site_url() . '/reviews-list-custom/' . $product_id);
        $response = json_decode(wp_remote_retrieve_body($request));
        echo wp_kses($response->html, html_allowed());*/


        get_footer();
