var $ = jQuery.noConflict();
$(document).ready(function() {
    var meta_gallery_frame;
    // Runs when the image button is clicked.
    $('#product_gallery_button, #product_sellers_button').on('click', function(e){

        button = $(this);
        //Attachment.sizes.full.url/ Prevents the default action from occuring.
        e.preventDefault();

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

                if(button.attr('id') == 'product_gallery_button') {
                    if('image' === attachment.attributes.type) {
                        imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.full.url+'" style="max-width: 150px;"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="gallery_item['+attachment.attributes.id+']" value="'+attachment.attributes.sizes.full.url+'"></div></li>';
                    } else if ('video' === attachment.attributes.type) {
                        imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img class="video-thumb" id="'+attachment.attributes.id+'" src="'+attachment.attributes.image.src+'"><span class="gallery-video-title">'+attachment.attributes.name+'</span><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="gallery_item['+attachment.attributes.id+']" value="'+attachment.attributes.url+'"></div></li>';
                    }
                } else if(button.attr('id') == 'product_sellers_button') {
                    if('image' === attachment.attributes.type) {
                        imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.full.url+'" style="max-width: 150px;"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="product_seller[logo][]" value="'+attachment.attributes.sizes.full.url+'"></div></li>';
                    }

                }
                    
            });
            metadataString = imageIDArray.join(",");

            if (metadataString) { 
                
                button.closest('td').find(".product_gallery_list").append(imageHTML);
        
            }
        });

        // Finally, open the modal
        meta_gallery_frame.open();

    }); 

    $(document).on('click', '.product_gallery_close' ,function(e){

        e.preventDefault();

        if (confirm('Você tem certeza que deseja excluir este item da galeria?')) {
           $(this).closest('li').remove();
           
        }
    }); 

    $('.add_model').on('click', function() {
        var input_field = $('.model_wrapp');

        $('.product_data').append(input_field);
    });

    $('.add_input').on('click', function() {
       $(this).closest('.form-field').append($(this).prev('.product_input').clone().val(''));
    });


    $.getJSON('https://sandbox-api.lomadee.com/v2/1513360209648ac4d7db9/category/_all?sourceId=35898386', function(data){
        if(data.requestInfo.status === "OK") {
            $.each(data.categories, function(key, value){
                $('select#seller-category').append($('<option value="'+value.id+'">'+value.name+'</option>'));
            });
        } else {
            $('select#seller-category').append('<option>Não foi possível conectar ao servidor</option>');
        }
        
    });

    var categoryId;
    productList = [];
    productJSON = [];

    $('#seller-category').change(function(){
        var categoryId = $(this).val();

        $.ajaxSetup({cache: false});
        
        var products = loopJSONPages('https://sandbox-api.lomadee.com/v2/1513360209648ac4d7db9/product/_category/'+categoryId+'?sourceId=35898386', categoryId);

        if(products){
            $('#product_item').keyup(function(){
                $('#result').html('');
                $('#state').val('');

                var searchField = $('#product_item').val(),
                    expression = new RegExp(searchField, 'i');

                $.each(products, function(key, value){
                    if(value.name.search(expression) !== -1){
                        $('#result').css('display', 'block');
                        $('#result').append('<li class="list-group-item link-class" data-product-id="'+value.id+'"><img src="'+value.thumbnail.url+'" heigth="40px" width="40" class="img-thumbnail"> '+value.name+' | Menor Preço '+value.priceMin+'</li>');
                    }
                });

                $('.list-group-item').on('click', function(){
                    $('.product_chosen').empty();
                    $('.product_chosen').append($(this).clone());
                    $('.product_chosen').append('<input type="hidden" name="product_seller_id" value="'+$(this).data('product-id')+'">');
                    $('.product_chosen').append('<input type="hidden" name="product_seller_thumb" value="'+$(this).children('img').attr('src')+'">');
                    $('.product_chosen').append('<input type="hidden" name="product_seller_name" value="'+$(this).clone()    //clone the element
                                                                                                                 .children() //select all the children
                                                                                                                 .remove()   //remove all the children
                                                                                                                 .end()  //again go back to selected element
                                                                                                                 .text()+'">');                    
                    $('#result').empty().css('display', 'none');
                
                });

                if($('#product_item').val() === '') {
                    $('#result').empty().css('display', 'none');
                }
            });
        } else {
            $('#result').css('display', 'block');
            $('#result').append('<li class="list-group-item link-class"> Não existem produtos!</li>');
        }
    });

    function loopJSONPages(url, categoryId) {

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

    $('#postimagediv .inside').prepend('<p class="howto">Resolução ideal: 280px X 280px</p>');
    
});