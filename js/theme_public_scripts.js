jQuery(document).ready(function($){
	
	//menu toggling
	$('.menu-toggle').on('click', function(){
		
		$('.menu.mobile-menu').toggleClass('active');
		$('.mobile-background').toggleClass('active');
		
		//close any sub-menus that might be open
		setTimeout(function(){
			$('.menu.mobile-menu').find('.sub-menu').hide();	
		}, 500);
		
	});

	//clicking mobile menu background
	$('.mobile-background').on('click', function(){
		$('.menu.mobile-menu').toggleClass('active');
		$('.mobile-background').toggleClass('active');
	});
	
	//When scrolling past top, adjust menu
	$(window).on('scroll', function(){
		console.log("scrolling");
		
		var header = $('#masthead');
		var header_height = header.outerHeight();
		var header_offset = header.offset().top;
		
		if(header_offset >= header_height){
			header.addClass('active');
		}else{
			header.removeClass('active')
		}
		
		console.log(header_offset);
	});

	//Clicking on the sub-menu to toggle menus
	$('.submenu-toggle').on('click', function(e){
		
		e.preventDefault();
		
		$(this).toggleClass('active');
		var submenu = $(this).closest('li').find('.sub-menu');
		
		submenu.slideToggle('fast');
	});
	
	

}); 