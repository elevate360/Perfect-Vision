<?php
/**
 * Theme Customizer
 *
 * Functionality for the theme that works in the customizer
 * - Contact details (phone, fax, Retail Operating Hours, Delivery Opening Hours, )
 * - Default content for top call to action section
 * - Default content for the bottom factory outlet section
 * - Social media icons / links (sticky menu + Social icons)
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ycc_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'ycc_customize_register' );
//Enqueue customizer styles
function enqueue_customizer_scripts_styles(){
	wp_enqueue_style('theme-customizer-style', get_stylesheet_directory_uri() . '/inc/customizer/customizer.css'); 
}
add_action('customize_preview_init', 'enqueue_customizer_scripts_styles'); //this hook to add styles inside the customizer ifrmae
//Add selective refresh support for customizer
function add_customizer_selective_refresh_support(){
	add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action('init', 'add_customizer_selective_refresh_support');
	
/**
 * Main Customiser functionality
 */
function el_customizer_functionality( $wp_customize ){
	//panels
	$wp_customize->add_panel( 'contact_details', 
		array(
		  'priority'       => 10,
		  'title'          => __('Contact Details', 'mytheme'),
		  'description'    => __('Contains settings for the contact details', 'ycc'),
		  )
	);
	
	//Sections
	$wp_customize->add_section('el_contact_details',
		array(
			'title'			=> 'General Contact Details',
			'priority'		=> 50,
			'description'	=> __('Theme specific contact details used throughout the site', 'ycc'),
			'panel' 		=> 'contact_details'
		)
	);
	$wp_customize->add_section('el_trading_hours',
		array(
			'title'			=> 'Trading Hours',
			'description'	=> __('Contains the trading hour settings for the site', 'ycc'),
			'panel' 		=> 'contact_details'
		)
	);
	$wp_customize->add_section('el_delivery_hours',
		array(
			'title'			=> 'Delivery Hours',
			'description'	=> __('Contains the delivery hour settings displayed in the site', 'ycc'),
			'panel' 		=> 'contact_details'
		)
	);
	
	
	$wp_customize->add_section('el_social_media',
		array(
			'title'			=> 'Social Media',
			'priority'		=> 51,
			'description'	=> __('Social media options for the theme. These are pulled into and used across parts of the sites templates', 'ycc')
		)
	);
	$wp_customize->add_section('el_footer',
		array(
			'title'			=> 'Footer',
			'priority'		=> 52,
			'description'	=> __('Options relating to the footer are stored here', 'ycc')
		)
	);
	$wp_customize->add_section('el_header_cta',
		array(
			'title'			=> 'Header CTA',
			'priority'		=> 53,
			'description'	=> __('Settings for the universal CTA section that is shown below the main banner. These can be overridden on a page by page basis', 'ycc')
		)
	);
	$wp_customize->add_section('el_header_banner',
		array(
			'title'			=> 'Header Banner',
			'priority'		=> 53,
			'description'	=> __('Settings that adjust the main header banner displayed on every page. These can be overridden on a page by page basis', 'ycc')
		)
	);
	$wp_customize->add_section('el_footer_cta',
		array(
			'title'			=> 'Footer CTA',
			'priority'		=> 54,
			'description'	=> __('Settings that adjust the footer CTA displayed at the bottom of each page', 'ycc')
		)
	);
	
	
	//Settings
	$wp_customize->add_setting('el_phone',array());
	$wp_customize->add_setting('el_fax',array());
	$wp_customize->add_setting('el_address', array());
	$wp_customize->add_setting('el_email', array());	
	$wp_customize->add_setting('el_trading_hours_monday_friday', array());
	$wp_customize->add_setting('el_trading_hours_saturday', array());
	$wp_customize->add_setting('el_trading_hours_holidays', array());
	
	
	$wp_customize->add_setting('el_logo', array());
	$wp_customize->add_setting('el_footer_logo', array());
	
	//social media
	$wp_customize->add_setting('el_facebook_url',array());
	$wp_customize->add_setting('el_twitter_url',array());
	$wp_customize->add_setting('el_show_link_to_order_page', array('default' => true));
	$wp_customize->add_setting('el_content_section', array());
	
	//association logos
	$wp_customize->add_setting('el_association_logos', array());
	
	
	
	//header banner
	$wp_customize->add_setting('el_header_banner_enabled', array('default' => true));
	$wp_customize->add_setting('el_header_banner_background_image', array());
	$wp_customize->add_setting('el_header_banner_featured_image', array());
	$wp_customize->add_setting('el_header_banner_text_colour', array('default' => '#fff'));
	$wp_customize->add_setting('el_header_banner_overlay_colour', array('default' => '#444'));
	$wp_customize->add_setting('el_header_banner_title', array());
	$wp_customize->add_setting('el_header_banner_subtitle', array());
	
	//footer CTA
	$wp_customize->add_setting('el_footer_cta_enabled', array('default' => true));
	$wp_customize->add_setting('el_footer_cta_title', array());
	$wp_customize->add_setting('el_footer_cta_subtitle', array());
	$wp_customize->add_setting('el_footer_cta_background_image', array());
	$wp_customize->add_setting('el_footer_cta_featured_image', array());
	
	//cta 
	$wp_customize->add_setting('el_header_cta_enabled', array('default' => true));
	$wp_customize->add_setting('el_header_cta_background_image', array('transport' => 'postMessage'));
	$wp_customize->add_setting('el_header_cta_background_colour', array('default' => '#f5f5f5', 'transport' => 'postMessage'));
	$wp_customize->add_setting('el_header_cta_title', array('transport' => 'postMessage'));
	$wp_customize->add_setting('el_header_cta_subtitle', array('transport' => 'postMessage'));
	
	
	
	
	//Controls	
	
	
	//Footer CTA
	$wp_customize->add_control('el_footer_cta_enabled',
		array(
			'label'			=> __('Enable Banner', 'ycc'),
			'description'	=> __('Do you want the footer banner displayed? (appears on all pages)', 'ycc'),
			'section'		=> 'el_footer_cta',
			'type'			=> 'checkbox',
			'setting'		=> 'el_footer_cta_enabled'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_footer_cta_background_image',
			array(
				'label'			=> __('Background Image', 'ycc'),
				'section'		=> 'el_footer_cta'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_footer_cta_featured_image',
			array(
				'label'			=> __('Featured Image Badge', 'ycc'),
				'section'		=> 'el_footer_cta'
			)
		)
	);
	$wp_customize->add_control('el_footer_cta_title',
		array(
			'label'			=> __('Title', 'ycc'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'ycc'),
			'section'		=> 'el_footer_cta',
			'type'			=> 'text',
			'setting'		=> 'el_footer_cta_title'
		)
	);
	$wp_customize->add_control('el_footer_cta_subtitle',
		array(
			'label'			=> __('Subtitle', 'ycc'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'ycc'),
			'section'		=> 'el_footer_cta',
			'type'			=> 'textarea',
			'setting'		=> 'el_footer_cta_subtitle'
		)
	);
	
	
	
	
	
	//Header Banner 
	$wp_customize->add_control('el_header_banner_enabled',
		array(
			'label'			=> __('Enable Banner', 'ycc'),
			'description'	=> __('Do you want the header banner displayed? (appears on all pages)', 'ycc'),
			'section'		=> 'el_header_banner',
			'type'			=> 'checkbox',
			'setting'		=> 'el_header_banner_enabled'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_header_banner_background_image',
			array(
				'label'			=> __('Background Image', 'ycc'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_header_banner_overlay_colour',
			array(
				'label'			=> __('Overlay Colour', 'ycc'),
				'decription'	=> __('Colour that\'s applied on top of the image for clarity', 'ycc'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_header_banner_text_colour',
			array(
				'label'			=> __('Text Colour', 'ycc'),
				'decription'	=> __('Colour used for both the title and subtitle text', 'ycc'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	
	$wp_customize->add_control('el_header_banner_title',
		array(
			'label'			=> __('Title', 'ycc'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'ycc'),
			'section'		=> 'el_header_banner',
			'type'			=> 'text',
			'setting'		=> 'el_header_banner_title'
		)
	);
	
	$wp_customize->add_control('el_header_banner_subtitle',
		array(
			'label'			=> __('Subtitle', 'ycc'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'ycc'),
			'section'		=> 'el_header_banner',
			'type'			=> 'textarea',
			'setting'		=> 'el_header_banner_subtitle'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_header_banner_featured_image',
			array(
				'label'			=> __('Featured Image (bottom right of slider)', 'ycc'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	
	
	
	
	
	//Header CTA
	$wp_customize->add_control('el_header_cta_enabled',
		array(
			'label'			=> __('Enable CTA', 'ycc'),
			'description'	=> __('Do you want the CTA displayed?', 'ycc'),
			'section'		=> 'el_header_cta',
			'type'			=> 'checkbox',
			'setting'		=> 'el_header_cta_enabled'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_header_cta_background_image',
			array(
				'label'			=> __('Background Image', 'ycc'),
				'section'		=> 'el_header_cta'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_header_cta_background_colour',
			array(
				'label'			=> __('Background Colour', 'ycc'),
				'decription'	=> __('Background colour used for the CTA section', 'ycc'),
				'section'		=> 'el_header_cta'
			)
		)
	);
	$wp_customize->add_control('el_header_cta_title',
		array(
			'label'			=> __('Title', 'ycc'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'ycc'),
			'section'		=> 'el_header_cta',
			'type'			=> 'text',
			'setting'		=> 'el_header_cta_title'
		)
	);
	$wp_customize->add_control('el_header_cta_subtitle',
		array(
			'label'			=> __('Subtitle', 'ycc'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'ycc'),
			'section'		=> 'el_header_cta',
			'type'			=> 'textarea',
			'setting'		=> 'el_header_cta_subtitle'
		)
	);
	
	//add support for selective refresh for selective theme mods
	//https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
	if(version_compare($GLOBALS['wp_version'], '4.5', '>=')){
			
		//TODO: These render functions should be called from OBJECTS to keep the logic DRY	
			
		//WP 4.5 refresh partial
		$wp_customize->selective_refresh->add_partial('el_header_cta_title', array(
			'selector'			=> '.header-hero-customizer .title',
			'render_callback'	=> function(){
				$el_header_cta_title = get_theme_mod('el_header_cta_title');
				echo $el_header_cta_title;
			}
		));
		$wp_customize->selective_refresh->add_partial('el_header_cta_subtitle', array(
			'selector'			=> '.header-hero-customizer .subtitle',
			'render_callback'	=> function(){
				$el_header_cta_subtitle = get_theme_mod('el_header_cta_subtitle');
				echo $el_header_cta_subtitle;
			}
		));
		$wp_customize->selective_refresh->add_partial('el_header_cta_background_colour', array(
			'selector'			=> '.header-hero-customizer .background-color',
			'render_callback'	=> function(){
				$el_header_cta_background_colour = get_theme_mod('el_header_cta_background_colour');
				var_dump($el_header_cta_background_colour);
				if(!empty($el_header_cta_background_colour)){
					$style = '';
					$style .= (!empty($el_header_cta_background_colour)) ? 'background-color: ' . $el_header_cta_background_colour . '; ' : '';
					echo '<div class="background-color" style="' . $style . '"></div>';
				}
				
			}
		));
		$wp_customize->selective_refresh->add_partial('el_header_cta_background_image', array(
			'selector'			=> '.header-hero-customizer .background-image',
			'render_callback'	=> function(){
				$el_header_cta_background_image = get_theme_mod('el_header_cta_background_image');
				if(!empty($el_header_cta_background_image)){
					$style = '';
					$style .= (!empty($el_header_cta_background_image)) ? 'background-image: url(' . $el_header_cta_background_image . '); ' : '';
					echo '<div class="background-image" style="' . $style . '"></div>';	
				}
				
				
			}
		
		));
	}
	
	
	//Site Identity (default)
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_logo',
			array(
				'label'			=> __('Theme Logo','ycc'),
				'description'	=> __('Selct the logo used for this theme. Will be displayed in the header and across other locations', 'ycc'),
				'section'		=> 'title_tagline'
			)
		)
	);
	
	
	
	//footer
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_footer_logo',
			array(
				'label'			=> __('Footer Logo','ycc'),
				'description'	=> __('Selct the logo to be displayed in the footer, along side the footer widget areas','ycc'),
				'section'		=> 'el_footer'
			)
		)
	);
	
	//Contact settings
	$wp_customize->add_control('el_phone', 
		array(
			'label'			=> __('Phone Number','ycc'),
			'section'		=> 'el_contact_details',
			'type'			=> 'text',
			'setting'		=> 'el_phone',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g (02) 9790 6474'
			)
		)
	);
	$wp_customize->add_control('el_fax', 
		array(
			'label'			=> __('Fax Number','ycc'),
			'section'		=> 'el_contact_details',
			'type'			=> 'text',
			'setting'		=> 'el_fax',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g (02) 9790 6474'
			)
		)
	);
	$wp_customize->add_control('el_address', 
		array(
			'label'			=> __('Address','ycc'),
			'section'		=> 'el_contact_details',
			'type'			=> 'textarea',
			'setting'		=> 'el_address',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g 2/425 Pacific Hwy, Crows Nest NSW 2065'
			)
		)
	);
	$wp_customize->add_control('el_email', 
		array(
			'label'			=> __('Address','ycc'),
			'section'		=> 'el_contact_details',
			'type'			=> 'email',
			'setting'		=> 'el_email',
			'input_attrs'	=> array(
				'placeholder'	=> 'admin@elevate360.com.au'
			)
		)
	);
	
	$wp_customize->add_control('el_trading_hours_monday_friday', 
		array(
			'label'			=> __('Monday - Friday','ycc'),
			'section'		=> 'el_trading_hours',
			'type'			=> 'text',
			'setting'		=> 'el_trading_hours_monday_friday',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g 7.00am - 4.00pm'
			)
		)
	);
	$wp_customize->add_control('el_trading_hours_saturday', 
		array(
			'label'			=> __('Saturday','ycc'),
			'section'		=> 'el_trading_hours',
			'type'			=> 'text',
			'setting'		=> 'el_trading_hours_saturday',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g 7.00am - 12.00pm'
			)
		)
	);
	$wp_customize->add_control('el_trading_hours_holidays', 
		array(
			'label'			=> __('Public Holidays & Sunday','ycc'),
			'section'		=> 'el_trading_hours',
			'type'			=> 'text',
			'setting'		=> 'el_trading_hours_holidays',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g Closed'
			)
		)
	);
	
	
	//Social media settings
	$wp_customize->add_control('el_facebook_url', 
		array(
			'label'			=> __('Facebook URL','ycc'),
			'section'		=> 'el_social_media',
			'type'			=> 'text',
			'setting'		=> 'el_facebook_url',
			'input_attrs'	=> array(
				'placeholder'	=> 'Full URL to Facebook Page'
			)
		)
	);
	$wp_customize->add_control('el_twitter_url', 
		array(
			'label'			=> __('Twitter URL','ycc'),
			'section'		=> 'el_social_media',
			'type'			=> 'text',
			'setting'		=> 'el_twitter_url',
			'input_attrs'	=> array(
				'placeholder'	=> 'Full URL to Twitter Page'
			)
		)
	);
	$wp_customize->add_control('el_show_link_to_order_page', 
		array(
			'label'			=> __('Show link to the order page', 'ycc'),
			'description'	=> __('Determines if on the fixed social media bar we display a link to the order page', 'ycc'),
			'section'		=> 'el_social_media',
			'type'			=> 'checkbox',
			'setting'		=> 'el_twitter_url',
			'input_attrs'	=> array(
				'placeholder'	=> 'Full URL to Twitter Page'
			)
		)
	);
	
	
	
	
}
add_action('customize_register', 'el_customizer_functionality', 15);







function ycc_customize_preview_js() {
	wp_enqueue_script( 'ycc_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'ycc_customize_preview_js' );