function checkFormK2(type){
	// var array11 = jQuery("select[name='array11[]']").val();
	// alert(array11);
	$isOk = true;
	switch (type) {
		case 'insurance':
		var searchword1 = jQuery("select[name='searchword1']").val();
		var searchword2 = jQuery("select[name='searchword2']").val();
		var array3 = jQuery("select[name='array3[]']").val();
		var array4 = jQuery("select[name='array4[]']").val();
		var count = 0;
		if(searchword1 != '' && searchword1 != null && searchword1 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword2 != '' && searchword2 != null && searchword2 != undefined){
			$isOk = true;
			count++;
		}
		if(array3 != '' && array3 != null && array3 != undefined){
			$isOk = true;
			count++;
		}
		if(array4 != '' && array4 != null && array4 != undefined){
			$isOk = true;
			count++;
		}

		if(count == 0){
			$isOk = false;
		}
			break;


		case 'travel':
		var searchword20 = jQuery("select[name='searchword20']").val();
		var array21 = jQuery("select[name='array21[]']").val();
		var searchword22 = jQuery("select[name='searchword22']").val();
		var searchword23 = jQuery("select[name='searchword23']").val();
		var array24 = jQuery("select[name='array24[]']").val();
		var count = 0;
		if(searchword20 != '' && searchword20 != null && searchword20 != undefined){
			$isOk = true;
			count++;
		}
		if(array21 != '' && array21 != null && array21 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword22 != '' && searchword22 != null && searchword22 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword23 != '' && searchword23 != null && searchword23 != undefined){
			$isOk = true;
			count++;
		}
		if(array24 != '' && array24 != null && array24 != undefined){
			$isOk = true;
			count++;
		}

		if(count == 0){
			$isOk = false;
		}
			break;



		case 'home':
		var searchword41 = jQuery("select[name='searchword41']").val();
		var searchword42 = jQuery("select[name='searchword42']").val();
		var searchword43 = jQuery("select[name='searchword43']").val();
		var array44 = jQuery("select[name='array44[]']").val();
		var array45 = jQuery("select[name='array45[]']").val();
		var count = 0;
		if(searchword41 != '' && searchword41 != null && searchword41 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword42 != '' && searchword42 != null && searchword42 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword43 != '' && searchword43 != null && searchword43 != undefined){
			$isOk = true;
			count++;
		}
		if(array44 != '' && array44 != null && array44 != undefined){
			$isOk = true;
			count++;
		}
		if(array45 != '' && array45 != null && array45 != undefined){
			$isOk = true;
			count++;
		}

		if(count == 0){
			$isOk = false;
		}

			break;

		case 'illness':
		var searchword50 = jQuery("select[name='searchword50']").val();
		var searchword51 = jQuery("select[name='searchword51']").val();
		var searchword52 = jQuery("select[name='searchword52']").val();
		var array53 = jQuery("select[name='array53[]']").val();
		var array54 = jQuery("select[name='array54[]']").val();
		var count = 0;
		if(searchword50 != '' && searchword50 != null && searchword50 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword51 != '' && searchword51 != null && searchword51 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword52 != '' && searchword52 != null && searchword52 != undefined){
			$isOk = true;
			count++;
		}
		if(array53 != '' && array53 != null && array53 != undefined){
			$isOk = true;
			count++;
		}
		if(array54 != '' && array54 != null && array54 != undefined){
			$isOk = true;
			count++;
		}
		if(count == 0){
			$isOk = false;
		}
			break;


		case 'health':
			var array8 = jQuery("select[name='array8[]']").val();
			var searchword9 = jQuery("select[name='searchword9']").val();
			var searchword10 = jQuery("select[name='searchword10']").val();
			var array11 = jQuery("select[name='array11[]']").val();
			var count = 0;
			if(array8 != '' && array8 != null && array8 != undefined){
				$isOk = true;
				count++;
			}
			if(searchword9 != '' && searchword9 != null && searchword9 != undefined){
				$isOk = true;
				count++;
			}
			if(searchword10 != '' && searchword10 != null && searchword10 != undefined){
				$isOk = true;
				count++;
			}
			if(array11 != '' && array11 != null && array11 != undefined){
				$isOk = true;
				count++;
			}

			if(count == 0){
				$isOk = false;
			}
		break;


		case 'car':
		var searchword33 = jQuery("select[name='searchword33']").val();
		var searchword34 = jQuery("select[name='searchword34']").val();
		var array35 = jQuery("select[name='array35[]']").val();
		var array36 = jQuery("select[name='array36[]']").val();
		var count = 0;
		if(searchword33 != '' && searchword33 != null && searchword33 != undefined){
			$isOk = true;
			count++;
		}
		if(searchword34 != '' && searchword34 != null && searchword34 != undefined){
			$isOk = true;
			count++;
		}
		if(array35 != '' && array35 != null && array35 != undefined){
			$isOk = true;
			count++;
		}
		if(array36 != '' && array36 != null && array36 != undefined){
			$isOk = true;
			count++;
		}

		if(count == 0){
			$isOk = false;
		}
			break;

		default:
		break;

	}
	return $isOk;
}

jQuery(document).ready(function(){



  /* Check width on page load*/
	if (jQuery(window).width() < 1020) {
	  jQuery('body').addClass('mobile');
	}else {
		jQuery('body').addClass('desktop');
	}
	if (jQuery(window).width() <= 1150) {
		jQuery('.right-logo-search-account').addClass('pixel1150');
	}

	var width = window.screen.width;
	var height = window.screen.height;
	if (width >= 1014 && width <= 1034){
		jQuery('.k2filter-field-title .inputbox').css("width", "176px");
		jQuery('ul#top_main_menu_mobile').css("font-size", "11px");
		jQuery('ul#top_main_menu_mobile li').css("padding-right", "0px");
	}
	if (width >= 1142 && width <= 1162){
		jQuery('.right-logo-search-account .moduletable .menu.mod-list').css("padding-left", "30px");
		jQuery('ul#top_main_menu_mobile li').css("padding-right", "0px");
	}
	if (width >= 790 && width <= 810){
		jQuery('#K2FilterBox120 form .k2filter-table .k2filter-cell .k2filter-field-category-select').css({'font-size': '14px','width':'135px'});
		jQuery('.k2filter-field-title .inputbox').css("width", "130px");
		jQuery('.right-logo-search-account .moduletable .menu.mod-list').css({'padding-left':'115px','font-size':'12px'});
		//jQuery('ul#top_main_menu_mobile li').css("padding-right", "0px");
	}


	jQuery(window).scroll(function () {
			 if (jQuery(this).scrollTop() > 100) {
					 jQuery('.sp-scroll-up').fadeIn();
					 if(jQuery('#itemAuthorLatest').length){
						 var element_position = jQuery('#itemAuthorLatest').offset().top;
						 if(jQuery(this).scrollTop() >= element_position ){
							 jQuery('.sp-scroll-up-table').fadeOut();
						 }else{
							 jQuery('.sp-scroll-up-table').fadeIn();
						 }
					 }

			 } else {
					 jQuery('.sp-scroll-up').fadeOut(400);
					 jQuery('.sp-scroll-up-table').fadeOut(400);
			 }
	 });

	 jQuery(window).scroll(function () {
	 		 if (jQuery(this).scrollTop() >= 40) {
				 jQuery('.desktop #mySidenav').css('top','56px');
	 		 } else {
				 jQuery('.desktop #mySidenav').css('top','146px');
	 		 }
	  });


	 jQuery('.sp-scroll-up').click(function () {
			 jQuery("html, body").animate({
					 scrollTop: 0
			 }, 600);
			 return false;
	 });

	 jQuery('.sp-scroll-up-table').click(function () {
			 jQuery("html, body").animate({
					 scrollTop: jQuery("#table-wrap").offset().top - 70
			 }, 400);
			 return false;
	 });

	 jQuery(document).on('scroll', function() {
		 if(jQuery('#itemAuthorLatest').length){
			 if (jQuery(this).scrollTop() >= jQuery('#itemAuthorLatest').position().top) {
		     console.log('I have been reached');
		   }
		 }

	 })



	jQuery("#acym__user__edit__email").attr("placeholder","Địa chỉ Email của bạn");
	jQuery('#toggle-menu').click(function(){

		jQuery('#mySidenav').attr('style','left:0px');
		jQuery('#overlay').show();
	});

	jQuery('#mySidenav .closebtn').click(function(){

		jQuery('#mySidenav').attr('style','left:-365px');
		jQuery('#top_main_menu_mobile').css('display','none!important');
		jQuery('.collapse:not(.show)').css('display','none!important');

		jQuery('#overlay').hide();
	});

	jQuery('#overlay').click(function(){
		jQuery('#mySidenav').attr('style','left:-365px');
		jQuery('#mySidenav2').attr('style','right:-365px');
		jQuery('#overlay').hide();
	});


	jQuery('.desktop #mySidenav .aboutus-mobile ul.nav.menu.mod-list').slideUp(500);
	jQuery('.desktop #mySidenav .for-counselors-mobile ul.nav.menu.mod-list').slideUp(500);
	jQuery('.desktop #mySidenav .for-customer-mobile ul.nav.menu.mod-list').slideUp(500);

	jQuery('#mySidenav h3').addClass('plus');
	jQuery('.aboutus-mobile').click(function(){

		jQuery('#mySidenav .aboutus-mobile ul.nav.menu.mod-list').slideToggle(500);

		if(jQuery('#mySidenav .aboutus-mobile h3').hasClass('plus')){
			jQuery('#mySidenav .aboutus-mobile h3').addClass('min');
			jQuery('#mySidenav .aboutus-mobile h3').removeClass('plus');

		}else{
			jQuery('#mySidenav .aboutus-mobile h3').removeClass('min')
			jQuery('#mySidenav .aboutus-mobile h3').addClass('plus');
		};

	});


	jQuery('.for-counselors-mobile').click(function(){

		jQuery('#mySidenav .for-counselors-mobile ul.nav.menu.mod-list').slideToggle(500);

		if(jQuery('#mySidenav .for-counselors-mobile h3').hasClass('plus')){
			jQuery('#mySidenav .for-counselors-mobile h3').removeClass('plus');
			jQuery('#mySidenav .for-counselors-mobile h3').addClass('min');
		}else{
			jQuery('#mySidenav .for-counselors-mobile h3').removeClass('min')
			jQuery('#mySidenav .for-counselors-mobile h3').addClass('plus');
		};

	});
	jQuery('.for-customer-mobile').click(function(){

		jQuery('#mySidenav .for-customer-mobile ul.nav.menu.mod-list').slideToggle(500);

		if(jQuery('#mySidenav .for-customer-mobile h3').hasClass('plus')){
			jQuery('#mySidenav .for-customer-mobile h3').removeClass('plus');
			jQuery('#mySidenav .for-customer-mobile h3').addClass('min');
		}else{
			jQuery('#mySidenav .for-customer-mobile h3').removeClass('min')
			jQuery('#mySidenav .for-customer-mobile h3').addClass('plus');
		};

	});

	// jQuery('.for-counselors-mobile').click(function(){
	// 	jQuery('#mySidenav .for-counselors-mobile ul.nav.menu.mod-list').slideToggle(500);
	// 	if(current == 1){
	// 		jQuery('#mySidenav .for-counselors-mobile h3').addClass('min');
	// 		jQuery('#mySidenav .for-counselors-mobile h3').removeClass('plus');
	//
	// 		current = 2;
	// 	}
	// 	else{
	// 		jQuery('#mySidenav .for-counselors-mobile h3').addClass('plus');
	// 		jQuery('#mySidenav .for-counselors-mobile h3').removeClass('min')
	// 		current = 1;
	// 	};
	//
	// });
	//
	// jQuery('.for-customer-mobile').click(function(){
	// 	jQuery('#mySidenav .for-customer-mobile ul.nav.menu.mod-list').slideToggle(500);
	// 	if(current == 1){
	// 		jQuery('#mySidenav .for-customer-mobile h3').addClass('min');
	// 		jQuery('#mySidenav .for-customer-mobile h3').removeClass('plus');
	//
	// 		current = 2;
	// 	}
	// 	else{
	// 		jQuery('#mySidenav .for-customer-mobile h3').addClass('plus');
	// 		jQuery('#mySidenav .for-customer-mobile h3').removeClass('min')
	// 		current = 1;
	// 	};
	//
	// });


	jQuery('#overlay').hide();
	jQuery('#toggle-menu-right').click(function(){
		jQuery('#mySidenav2').attr('style','right:0px');
		jQuery('#overlay').show();

		jQuery('#mySidenav2').css('display','block');
	});

	jQuery("#mySidenav2 .item-281 ul.nav-child.unstyled.small").slideUp();
	jQuery('#mySidenav2 .closebtn').click(function(){
		jQuery('#mySidenav2').attr('style','right:-300px');
		jQuery('#overlay').hide();
	});


	jQuery('.item-281').addClass('plus');
	jQuery('.item-294').addClass('plus');

	jQuery('#mySidenav2 .item-281').click(function(){
		jQuery('#mySidenav2 .item-281 ul.nav-child.unstyled.small').slideToggle();
		if(jQuery('#mySidenav2 .item-281').hasClass('plus')){
			jQuery('#mySidenav2 .item-281').addClass('min');
			jQuery('#mySidenav2 .item-281').removeClass('plus');

		}
		else {
			jQuery('#mySidenav2 .item-281').removeClass('min');
			jQuery('#mySidenav2 .item-281').addClass('plus')
		}
	});
	// jQuery('#aside .well .item-281').hover(function(){
	// 	jQuery('#aside .well .item-281 ul.nav-child.unstyled.small').slideToggle();
	// });
	// jQuery('#aside .well .item-281 ul.nav-child.unstyled.small').hover(function(){
	//
	// });
	jQuery('#aside .well .item-281').click(function(){
		jQuery('#aside .well .item-281 ul.nav-child.unstyled.small').slideToggle();
		if(jQuery('#aside .well .item-281').hasClass('plus')){
			jQuery('#aside .well .item-281').addClass('min');
			jQuery('#aside .well .item-281').removeClass('plus');

		}
		else {
			jQuery('#aside .well .item-281').removeClass('min');
			jQuery('#aside .well .item-281').addClass('plus')
		}
	});



	jQuery("#mySidenav2 .item-294 ul").slideUp();
	jQuery('#mySidenav2 .item-294').click(function(){
		jQuery('#mySidenav2 .item-294 ul.nav-child.unstyled.small').slideToggle();
		if(jQuery('#mySidenav2 .item-294').hasClass('plus')){
			jQuery('#mySidenav2 .item-294').removeClass('plus');
			jQuery('#mySidenav2 .item-294').addClass('min');
		}
		else {
			jQuery('#mySidenav2 .item-294').removeClass('min');
			jQuery('#mySidenav2 .item-294').addClass('plus');
		}

	});

	// jQuery('#aside .well .item-294').hover(function(){
	// 	jQuery('#aside .well .item-294 ul.nav-child.unstyled.small').slideToggle();
	// });
	// jQuery('#aside .well .item-294 ul.nav-child.unstyled.small').hover(function(){
	//
	// });
	jQuery('#aside .well .item-294').click(function(){
		if(jQuery('#aside .well .item-294').hasClass('plus')){
			jQuery('#aside .well .item-294').addClass('min');
			jQuery('#aside .well .item-294').removeClass('plus');

		}
		else {
			jQuery('#aside .well .item-294').removeClass('min');
			jQuery('#aside .well .item-294').addClass('plus')
		}
		jQuery('#aside .well .item-294 ul.nav-child.unstyled.small').slideToggle();
	});

	jQuery('#mySidenav .item-102 ul.nav-child.unstyled.small').attr('style','display: none');
	jQuery('#mySidenav .item-102').click(function(){
		jQuery('#mySidenav .item-102 ul.nav-child.unstyled.small').slideToggle();
	});
	jQuery('#mySidenav .item-207 ul li ul').attr('style','display: none');


	jQuery("#mySidenav .item-211").click(function(){
		jQuery('#mySidenav a.amobile-211').attr('href','#');
		if (jQuery('#mySidenav .item-211 ul').css('display') == 'none') {
			jQuery('#mySidenav .item-214 ul').attr('style','display: none');
			jQuery('#mySidenav .item-215 ul').attr('style','display: none');
				jQuery('#mySidenav .item-211 ul').attr('style','display: block');
		}else {
				jQuery('#mySidenav .item-211 ul').attr('style','display: none');
		}
	});

	jQuery("#mySidenav .item-214").click(function(){
		jQuery('#mySidenav a.amobile-214').attr('href','#');
		if (jQuery('#mySidenav .item-214 ul').css('display') == 'none') {
				jQuery('#mySidenav .item-211 ul').attr('style','display: none');
				jQuery('#mySidenav .item-215 ul').attr('style','display: none');
				jQuery('#mySidenav .item-214 ul').attr('style','display: block');

		}else {
				jQuery('#mySidenav .item-214 ul').attr('style','display: none');
		}
	});

	jQuery("#mySidenav .item-215").click(function(){
		jQuery('#mySidenav a.amobile-215').attr('href','#');
		if (jQuery('#mySidenav .item-215 ul').css('display') == 'none') {
				jQuery('#mySidenav .item-211 ul').attr('style','display: none');
				jQuery('#mySidenav .item-214 ul').attr('style','display: none');
				jQuery('#mySidenav .item-215 ul').attr('style','display: block');

		}else {
				jQuery('#mySidenav .item-215 ul').attr('style','display: none');
		}
	});

	//jQuery('.desktop#mySidenav #top_main_menu_mobile .item-102 ul').show();
	jQuery(".desktop #mySidenav").mouseleave(function(){
		jQuery('#mySidenav #top_main_menu_mobile .item-102 ul').hide();

	});

	jQuery(".footer2 .for-customer-mobile h3").click(function(){
		if (jQuery('.footer2 .for-customer-mobile .mod-list').is(':visible')) {
			jQuery('.footer2 .for-customer-mobile .mod-list').css('display','none');
		}else{
			jQuery('.footer2 .for-customer-mobile .mod-list').css('display','block');
		}
	});

	jQuery(".footer2 .for-counselors-mobile h3").click(function(){
		if (jQuery('.footer2 .for-counselors-mobile .mod-list').is(':visible')) {
			jQuery('.footer2 .for-counselors-mobile .mod-list').css('display','none');
		}else{
			jQuery('.footer2 .for-counselors-mobile .mod-list').css('display','block');
		}
	});


	jQuery(".k2filter-field-category-select select option[value='112']").each(function() {
    jQuery(this).remove();
	});
	jQuery(".k2filter-field-category-select select option[value='117']").each(function() {
    jQuery(this).remove();
	});

});
