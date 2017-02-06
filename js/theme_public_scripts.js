jQuery(document).ready(function($){
	
	//menu toggling
	$('.menu-toggle').on('click', function(){
		
		$('.main-navigation.mobile, .main-navigation.mobile .mobile-menu').toggleClass('active');
		
	});

}); 