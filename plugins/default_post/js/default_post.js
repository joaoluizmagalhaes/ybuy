var $ = jQuery.noConflict();
$(document).ready(function() {
    var meta_gallery_frame;
    // Runs when the image button is clicked.
    $('#product_gallery_button, .before, .after, .rating').on('click', function(e){
        e.preventDefault();

        //Attachment.sizes.thumbnail.url/ Prevents the default action from occuring.
        opem_media_modal($(this));
    }); 

    function opem_media_modal(clicked_button) {
        this_button = clicked_button;

        // If the frame already exists, re-open it.
        if ( meta_gallery_frame ) {
            meta_gallery_frame.open();
            return;
        }

        // Sets up the media library frame
        meta_gallery_frame = wp.media.frames.meta_gallery_frame = wp.media({
            multiple: true
        });            

        // When an image is selected, run a callback.
        meta_gallery_frame.on('select', function() {
            var imageIDArray = [];
            var imageHTML = '';
            var metadataString = '';
            images = meta_gallery_frame.state().get('selection');
            images.each(function(attachment) {
                imageIDArray.push(attachment.attributes.id);

                if($(this_button).hasClass('shortcode')) {
                    imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.thumbnail.url+'"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="widget_image_'+ $(this_button).data('position') +'" value="'+attachment.attributes.sizes.full.url+'"></div></li>';
                } else if ($(this_button).hasClass('rating')){
                    imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.thumbnail.url+'"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="rating_image" value="'+attachment.attributes.sizes.full.url+'"></div></li>';
                } else {
                    imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.thumbnail.url+'"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="widget_image" value="'+attachment.attributes.sizes.full.url+'"></div></li>';                    
                }
            });
           
            if($(this_button).hasClass('shortcode')){
                
                switch ($(this_button).data('position')) {
                    case 'before':
                        $('.before-wrapper').append(imageHTML);
                        break;
                    case 'after':
                        $('.after-wrapper').append(imageHTML);
                        break;
                    break;
               }
            } else if ($(this_button).hasClass('rating')){
                $('.rating-wrapper').append(imageHTML);
            } else {
                $(".product_gallery_list").html(imageHTML);
            }

        });

        // Finally, open the modal
        meta_gallery_frame.open();
    }

    $(document).on('click', '.product_gallery_close' ,function(e){

        e.preventDefault();

        if (confirm('Você tem certeza que deseja excluir este item da galeria?')) {
           $(this).closest('li').remove();
        }
    }); 

    $('#modal-box-insert-price').unbind('click').bind('click', function(){
        

        $.getJSON('https://api.lomadee.com/v2/1513360209648ac4d7db9/category/_all?sourceId=35898386', function(data){
            if(data.requestInfo.status === "OK") {
                $.each(data.categories, function(key, value){
                    $('select#seller-category').append($('<option value="'+value.id+'">'+value.name+'</option>'));
                });
            } else {
                $('select#seller-category').append('<option>Não foi possível conectar ao servidor</option>');
            }
            
        });

        var categoryId;

        $('#seller-category').change(function(){
            var categoryId = $(this).val(),
                products = [];
            $('#result').html('');
            $('.product_chosen').empty();

            $.ajaxSetup({cache: false});
            
            products = loopJSONPages('https://api.lomadee.com/v2/1513360209648ac4d7db9/product/_category/'+categoryId+'?sourceId=35898386', categoryId);

            if(products){
                $('.search_product').unbind('click').bind('click', function(e){

                    e.preventDefault();

                    $('#TB_ajaxContent #result').html('');
                    $('#state').val('');

                    var searchField = $('#TB_ajaxContent #product_item').val(),
                        expression = new RegExp(searchField, 'i');

                    $.each(products, function(key, value){
                        if(value.name.search(expression) !== -1){
                            console.log(value.name.search(expression));
                            $('#TB_ajaxContent #result').css('display', 'block');
                            $('#TB_ajaxContent #result').append('<li class="list-group-item link-class" data-product-id="'+value.id+'"><img src="'+value.thumbnail.url+'" heigth="40px" width="40" class="img-thumbnail"> '+value.name+' | Menor Preço '+value.priceMin+'</li>');
                        }
                    });

                    console.log(expression);
                    console.log(searchField);
                    console.log($('#result'));

                    $('.list-group-item').on('click', function(){
                        $('.product_chosen').empty();
                        $('.product_chosen').append($(this).clone());
                        $('.product_chosen').append('<input type="hidden" name="product-id" value="'+$(this).data('product-id')+'">');
                        $('#TB_ajaxContent #result').empty().css('display', 'none');
                    
                    });

                    if($('#TB_ajaxContent #product_item').val() === '') {
                        $('#TB_ajaxContent #result').empty().css('display', 'none');
                    }
                });

            } else {
                $('#TB_ajaxContent #result').css('display', 'block');
                $('#TB_ajaxContent #result').append('<li class="list-group-item link-class"> Não existem produtos!</li>');
            }
        });
    });

    

    function loopJSONPages(url, categoryId) {
        productList = [];

        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            dataType: 'JSON',
            success: function(data) {

                if (data.requestInfo.status !== 'NOT_FOUND') {
                    var totalPages = (data.hasOwnProperty('pagination')) ? data.pagination.totalPage : 1;

                    var i;

                    for(i = 1; i <= totalPages; i++) {
                        $.ajax({
                            url: 'https://api.lomadee.com/v2/1513360209648ac4d7db9/product/_category/'+categoryId+'?sourceId=35898386&page='+i+'',
                            type: 'GET',
                            async: false,
                            dataType: 'JSON',
                            success: function(dataProducts){
                                $.each(dataProducts.products, function(key, value){
                                    productList.push(value);
                                });
                            }
                        });
                    }              
                } else {
                    $('#result').css('display', 'block');
                    $('#result').append('<li class="list-group-item link-class"> Produtos não encontrados!</li>');
                }
            },
            error: function() {
                $('#result').css('display', 'block');
                $('#result').append('<li class="list-group-item link-class" >Não foi possível conectar ao servidor!</li>');
            }
        });

        return productList;
    }

    $('#postimagediv .inside').prepend('<p class="howto">Resolução ideal: 280px X 408px</p>');

});