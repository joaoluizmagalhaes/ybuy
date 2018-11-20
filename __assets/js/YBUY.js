var $ = jQuery.noConflict();
$(document).ready(function() {

	var YBUY = (function($) {

		var mobile = /Mobi/.test(navigator.userAgent);

		var svgImageInline = function() {

			$('.img-svg').each(function() {

				var img = $(this),
					imgID = img.attr('id'),
					imgClass = img.attr('class'),
					imgURL = img.attr('src');

				$.get(imgURL, function(data) {

					var svg = $(data).find('svg');

					if(typeof imgID !== 'undefined') {
						svg = svg.attr('id', imgID);
					}

					if(typeof imgClass !== 'undefined') {
						svg = svg.attr('class', imgClass + ' replaced-svg');
					}

					svg = svg.removeAttr('xmlns:a');
					img.replaceWith(svg);

				}, 'xml');
			
			});
		};

		var newHome = function() {
			$(window).on('load', function() {
				setTimeout(function() {
					$("main#home.new")
						.find('svg#logo-big')
						.animate({
							'opacity' : '1'
						}, 750);
				}, 1);
			})
		};

		var coverDrag = function() {
			$(window).on('load', function() {
				if($('.covered').length > 0 ) {
	 				$('.covered').twentytwenty({
	 					before_label: '',
	 					after_label: ''
	 				});
	 			}
 			});
 		};

 		var carouselInteraction = function(carousel) {

 			var carouselSection    = carousel,
 				carouselWrapper    = $(carouselSection).find('.carousel-wrapper'),
 				getElementsWidth   = $(carouselSection).find('article').eq('0').outerWidth(),
 				fixedElementWidth  = $(carouselSection).find('article').eq('0').width(),
 				totalOfElements    = $(carouselSection).find('article').length,
 				lastVisibleElement = ($(carouselSection).find('.row').width() / getElementsWidth),
 				carouselTotalWidth = ( (getElementsWidth * totalOfElements) ),
 				controlElements    = '<div class="control"><a class="prev" href="#"></a><a class="next" href="#"></a></div>';

 			function controls() {

 				function removeControl(control) {
 					$(control).hide();	
 				}

 				function showControl(control) {
 					$(control).show();				
 				}

 				var selected        = $(this).attr('class'),
 					activeCarousel  = $(this).closest('.carousel'), 
 					carouselWrapper = $(activeCarousel).find('.carousel-wrapper'),
 					actualPosition  = $(carouselWrapper).position().left,
 					moviment        = '';

 				if(selected === 'next') {
 					
 					lastVisibleElement = (parseInt($(carouselWrapper).attr('data-carousel-last-visible'))+1);
 					
 					moviment = $(carouselWrapper).position().left < 0 ? ( actualPosition - getElementsWidth ) : ( '-' + (actualPosition + getElementsWidth) );

 					$(carouselWrapper).animate({
 						'left' : moviment + 'px'
 					}, 250, function() {

 						actualPosition = $(carouselWrapper).position().left;
 						$(carouselWrapper).attr('data-carousel-last-visible' , lastVisibleElement);

 						if( lastVisibleElement === totalOfElements) {
 							removeControl( activeCarousel.find('.control > .next') );
 						}

 						if( lastVisibleElement > 4 ) {
 							showControl( activeCarousel.find('.control > .prev') );
 						}

 					});

 				} else {

 					lastVisibleElement = (parseInt($(carouselWrapper).attr('data-carousel-last-visible'))-1);

 					$(carouselWrapper).animate({
 						'left' : (actualPosition + getElementsWidth) + 'px'
 					}, 250, function() {
 						
 						actualPosition = $(carouselWrapper).position().left;
 						$(carouselWrapper).attr('data-carousel-last-visible' , lastVisibleElement);

 						if( lastVisibleElement < totalOfElements) {
 							showControl( activeCarousel.find('.control > .next') );
 						}

 						if( activeCarousel.is('#home-content-posts') && lastVisibleElement === 4 || activeCarousel.is('#list-most-popular-manufacturers') && lastVisibleElement === 6 ) {
 							removeControl( activeCarousel.find('.control > .prev') );
 						}

 					});

 				}
 				
 				return false;
 			
 			}	

 			$(carouselWrapper)
 				.width(carouselTotalWidth + 'px')
 				.attr('data-carousel-last-visible' , lastVisibleElement)
 				.find('article').width(fixedElementWidth);

 			if(totalOfElements > 4) {
	 			$(carouselSection)
	 				.append(controlElements)
	 				.find('.control > a.prev').hide()
	 				.end()
	 				.find('.control > a')
	 				.on('click' , controls);
	 		}
 		};

 		var tabsInteraction = function() {
 			
			var tabNavigation     = $("[data-tabs]"),
				tabContent        = $('.tab-container'),
				totalOfElements   = $(tabContent).find('> div').length,
				tabContentWidth   = $(tabContent).find('> div:eq(0)').width(),
				totalContentWidth = (totalOfElements * tabContentWidth);

			$(tabContent)
				.width(totalContentWidth)
				.find('> div').width(tabContentWidth);

 			$(tabNavigation).find('a').on('click', function() {

				var anchor    = $(this),
 					index     = $(anchor).index(),
 					activeTab = $(tabContent).find('div[data-tab-anchor=' + $(this).attr('data-tab') + ']');

 				$(anchor)
 					.siblings()
 					.removeClass('active')
 					.end()
 					.addClass('active');

	 			$(tabContent).animate({
	 				'left' : '-' + (tabContentWidth * index) + 'px'
	 			}, 500);	
 				
 				return false;
 			
 			});
 		};

 		var menuUserLogin = function() {

 			var menu = $('#box-user');

 			if(mobile) {
 				$(menu).click(function() {
 					$(this).hasClass('active') ? $(menu).removeClass('active').find('ul').fadeOut(250) : $(menu).addClass('active').find('ul').fadeIn(250);
				});
 			} else {
	 			$(menu).on({
	 				mouseenter: function() {	
	 					$(menu)
	 						.addClass('active')
	 						.find('ul')
	 						.fadeIn(250);
	 				},
	 				mouseleave: function() {
	 					setTimeout(function() {
	 						if( $(menu).is(':hover') || $(menu).find('ul').is(':hover') ) {
	 							return;
	 						} else {
	 							$(menu)
		 							.removeClass('active')
		 							.find('ul')
		 							.fadeOut(250);
	 						}
	 					}, 300);
	 				}	
	 			});
 			}
 		};

 		var stickySubHeader = function() {
 			if($('#sub-header').length > 0) {
 				$(window).scroll(function(){
 					if($(window).scrollTop() > 60) {
 						$('#sub-header').addClass('sticky');
 						if(mobile) {
 							$('#sub-header').next().css('margin-top', '73px');
 						} else {
 							$('#sub-header').next().css('margin-top', '63px');
 						}
 					} else {
 						$('#sub-header').removeClass('sticky');
 						$('#sub-header').next().css('margin-top', '0');
 					}
 				});
 			}
 		};

 		var productGallery = function() {

 			//creating the variables
 			var currentImage,
 				currentThumb,
 				totalOfGalleryItems,
 				nextButton = $('.gallery-next-button'),
 				prevButton = $('.gallery-prev-button'),
 				currentURL = window.location.href,
 				videoID = [],
 				elementClicked;
 			

 			// if is the product page, load the script
 			if (currentURL.indexOf('produtos')) {

				if($('.youtube-video')[0]) {
					$.each( $('.youtube-video'), function(key, value) {
						videoID[$(value).data('active')] = $(value).data('video-id');
					});
				}
 	
 				//get the current image and current chumb
 				currentImage = $('.gallery-item-active').data('active');
 				currentThumb = $('.gallery-item-thumb.active').data('active');
 				
 				//get total of gallery items
 				totalOfGalleryItems = $('.gallery-item-thumb').length;

 				//gallery next button click
 				nextButton.on('click', function(e){
 					e.preventDefault();

 					if(currentThumb >= totalOfGalleryItems) {
 						nextButton.off('click', changeGalleryImage);
 					} else {
 						$('.gallery-prev-icon').removeClass('disabled');
						changeGalleryImage("next");
						if(currentThumb > (totalOfGalleryItems - 1)) {
							$('.gallery-next-icon').addClass('disabled');
						}
 					}
 				});

 				//gallery prev button click
 				prevButton.on('click', function(e){
 					e.preventDefault();
 					
 					if(currentThumb <= 1) {
 						prevButton.off('click', changeGalleryImage);
 					} else {
 						$('.gallery-next-icon').removeClass('disabled');
						changeGalleryImage("prev");
						if(currentThumb < 2) {
							$('.gallery-prev-icon').addClass('disabled');
						}
 					} 					
 				});

 				//bring thenew image or video
 				function changeGalleryImage(direction) {
 					$('.gallery-item-thumb[data-active='+currentThumb+']').removeClass('active');
 					
 					if(direction === "next") {
 						currentThumb++;
 					} else if(direction === "prev") {
 						currentThumb--;
 					}

 					$('.gallery-item-thumb[data-active='+currentThumb+']').addClass('active');

 					$('.gallery-item-active').fadeTo(200, 0, function() {
 						if($('.gallery-item-thumb[data-active='+currentThumb+']').hasClass('youtube-video')) {
	 						$('.gallery-item-active').css('display', 'none');
	 						$('iframe').remove();
	 						$('.gallery-container').append('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/'+videoID[currentThumb]+'?rel=0" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>')
	 					} else {
 							$('.gallery-item-active').attr('src', $('.gallery-item-thumb[data-active='+currentThumb+']').attr('src')).css('display', 'block');
 							$('iframe').remove();
 						}
 					}).fadeTo(200,1);
	 			}

	 			//when the thumb is clicked to chose the image or video
	 			$('.gallery-item-thumb').on('click', function(){

	 				elementClicked = $(this);

	 				if($(this).hasClass('active')) {
	 					return false;
	 				} else {
	 					$('.gallery-prev-icon, .gallery-next-icon').removeClass('disabled');

		 				$('.gallery-item-thumb.active').removeClass('active');

		 				$(this).addClass('active');
		 				currentThumb = $(this).data('active');

		 				$('.gallery-item-active').fadeTo(200, 0, function() {
		 					if(elementClicked.hasClass('youtube-video')) {
		 						$('.gallery-item-active').css('display', 'none');
		 						$('iframe').remove();
		 						$('.gallery-container').append('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/'+videoID[currentThumb]+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>')
		 					} else {
	 							$('.gallery-item-active').attr('src', $('.gallery-item-thumb[data-active='+currentThumb+']').attr('src')).css('display', 'block');
	 							$('iframe').remove();
	 						}
	 					}).fadeTo(200,1);

	 					if(currentThumb > (totalOfGalleryItems - 1)) {
	 						$('.gallery-prev-icon').removeClass('disabled');
							$('.gallery-next-icon').addClass('disabled');
						}

						if(currentThumb < 2) {
							$('.gallery-next-icon').removeClass('disabled');
							$('.gallery-prev-icon').addClass('disabled');
						}
	 				}
	 			});
 			}
 		};

 		var mobileMenuIteractions = function () {
 			var menu = $('.menu-button'),
 				close = $('.close-button');

 			menu.on('click', function() {
 				$(this).css('display', 'none');
 				$('.search-button').slideUp();
 				close.css('display', 'inline-block');
 				$('#box-user').slideDown();

 				if(window.location.href.indexOf("/produtos/") > -1 || window.location.href.indexOf("/categoria/") > -1) {
 					$('header').attr('id', 'fixed');
 					$('#sub-header').css('margin-top', '60px');
 				}
 			});

 			close.on('click', function() {
 				$(this).css('display', 'none');
 				$('.search-button').slideDown();
 				menu.css('display', 'inline-block');
 				$('#box-user').slideUp();

 				if(window.location.href.indexOf("/produtos/") > -1 || window.location.href.indexOf("/categoria/") > -1) {
 					$('header').attr('id', '');
 					$('#sub-header').css('margin-top', '0');
 				}
 			});
 		};

 		var searchMobileIteractions = function () {

 			var search_button = $('.search-button');

 			search_button.on('click', function() {
 				if(search_button.hasClass('open')){
 					$('.form-wrapper').slideUp();
 					search_button.removeClass('open');
 					if(window.location.href.indexOf("/produtos/") > -1 || window.location.href.indexOf("/categoria/") > -1) {
	 					$('header').attr('id', '');
	 					$('#sub-header').css('margin-top', '0');
	 				}

 				} else {
 					$('.form-wrapper').slideDown();
 					search_button.addClass('open');
 					if(window.location.href.indexOf("/produtos/") > -1 || window.location.href.indexOf("/categoria/") > -1) {
	 					$('header').attr('id', 'fixed');
	 					$('#sub-header').css('margin-top', '60px');
	 				}
 				}
 				
 			});
 		};

 		var getProductOffers = function () {
 			var currentURL = String(window.location.href);
 			var minPrice,
 				maxPrice;

 			if(currentURL.indexOf('produtos') !== -1) {
 				var productSellerId = $('.product-offer-id').data('product-id');

 				getProductsJSON('https://api.lomadee.com/v2/1513360209648ac4d7db9/offer/_product/'+productSellerId+'?sourceId=35898386&sort=price', productSellerId);
 				
 				function getProductsJSON(url, productID) {

 					$.ajax({
			            url: url,
			            type: 'GET',
			            async: false,
			            dataType: 'JSON',
			            success: function(data) {
			            	total = $(data.offers).length;
			            	$.each(data.offers, function(key, value){
			            		$('#list-where-to-find .row').append('<article class="col-xs-6 col-sm-6 col-md-4 col-lg-2"><a href="'+value.link+'" target="blank"><figure><img src="'+value.store.thumbnail+'" alt="'+value.store.name+'"></figure><hr><p>À partir de <br><strong>R$ '+$.number(value.price, 2, ',', '.')+'</strong></p></a></a></article>');
			            		if(0 === key) {
			            			minPrice = $.number(value.price, 2, ',', '.');
			            		} else if (total - 1 === key) {
			            			maxPrice = $.number(value.price, 2, ',', '.');
			            		}
			            	}); 
			            }
			        });
 				}

 				$('.min-price').html(minPrice);
 				$('.max-price').html(maxPrice);
 			}
 		};

 		var getPostOffers = function() {

 			if($('.half-content')[0]){
 				for(var count = 0; count <= $('.half-content').length; count++) {
 					
 					$('.half-content').eq(count).append("<ul></ul>");

 					var productID = $('.half-content').eq(count).children('.product-offer-id').data('product-id'),
 						numberOffers = $('.half-content').eq(count).children('.product-offer-id').data('number-of-offers');

 					getOffersJSON('https://api.lomadee.com/v2/1513360209648ac4d7db9/offer/_product/'+productID+'?sourceId=35898386&sort=price', productID, numberOffers);

 					function getOffersJSON(url, productID, numberOffers) {

	 					$.ajax({
				            url: url,
				            type: 'GET',
				            async: false,
				            dataType: 'JSON',
				            success: function(data) {
				            	for(var i = 0; i < numberOffers; i++) {
					            	$('.half-content ul').eq(count).append('<li><strong class="product-price">R$ '+$.number(data.offers[i].price, 2, ',', '.')+'</strong><span>'+data.offers[i].store.name+'</span><a href="'+data.offers[i].link+'" target="_blank">Comprar</a></li>');
					            }
				            }
				        });
	 				}
 				}
 			}
 		};

 		var moveErrorMsg = function() {
 			if($('.error-msg')[0]) {
 				$('.error-msg').insertAfter('.loging-form-wrapper h1').css('display', 'block');
 			}
 		};

		return { 
			init: function() {
				newHome();
				svgImageInline();
				coverDrag();
				tabsInteraction();
				menuUserLogin();
				stickySubHeader();
				productGallery();
				mobileMenuIteractions();
				searchMobileIteractions();
				getProductOffers();
				getPostOffers();
				moveErrorMsg();

				if(!mobile) {
					$('section.carousel').each(function() {
						carouselInteraction($(this));
					});
				}

				$('.sendgrid_mc_input_email').attr("placeholder" , "escreva seu e-mail aqui");
				$('form#loginform input#user_login').attr("placeholder" , "Usuário");
				$('form#loginform input#user_pass').attr("placeholder" , "Senha");

				if(mobile && ($('footer .menu > ul >li').length === 1)) {
					$('footer .menu > ul >li, footer .menu > ul >li a').css({
						'display':    'block',
						'text-align': 'center',
					});
				}	
			}
		};

	}(jQuery));
	YBUY.init();
});