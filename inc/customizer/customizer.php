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
function perfectvision_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'perfectvision_customize_register' );
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
	
	$wp_customize->add_section('el_social_media',
		array(
			'title'			=> 'Social Media',
			'priority'		=> 51,
			'description'	=> __('Social media options for the theme. These are pulled into and used across parts of the sites templates', 'perfectvision')
		)
	);
	$wp_customize->add_section('el_footer',
		array(
			'title'			=> 'Footer',
			'priority'		=> 52,
			'description'	=> __('Options relating to the footer are stored here', 'perfectvision')
		)
	);
	$wp_customize->add_section('el_commitment_program_cta',
		array(
			'title'			=> 'Commitment Program CTA',
			'priority'		=> 80,
			'description'	=> __('Settings for the Commitment Program CTA displayed above the footer', 'perfectvision')
		)
	);
	$wp_customize->add_section('el_appointment_cta',
		array(
			'title'			=> 'Appointment CTA',
			'priority'		=> 81,
			'description'	=> __('Settings for the Appointment CTA displayed above the footer', 'perfectvision')
		)
	);
	$wp_customize->add_section('el_header_banner',
		array(
			'title'			=> 'Header Banner',
			'priority'		=> 53,
			'description'	=> __('Settings that adjust the main header banner displayed on every page. These can be overridden on a page by page basis', 'perfectvision')
		)
	);
	

	
	//Settings
	$wp_customize->add_setting('el_logo', array());

	//social media
	$wp_customize->add_setting('el_facebook_url',array());
	$wp_customize->add_setting('el_twitter_url',array());
	
	//association logos
	$wp_customize->add_setting('el_association_logos', array());
	
	
	
	//header banner
	$wp_customize->add_setting('el_header_banner_background_image', array());
	$wp_customize->add_setting('el_header_banner_text_colour', array('default' => '#fff'));
	$wp_customize->add_setting('el_header_banner_overlay_colour', array('default' => '#444'));
	$wp_customize->add_setting('el_header_banner_title', array());
	$wp_customize->add_setting('el_header_banner_subtitle', array());
	$wp_customize->add_setting('el_header_banner_primary_button_text', array('default' => 'Primary Button'));
	$wp_customize->add_setting('el_header_banner_primary_button_url', array());
	$wp_customize->add_setting('el_header_banner_secondary_button_text', array('default' => 'Secondary Button'));
	$wp_customize->add_setting('el_header_banner_secondary_button_url', array());
	
	

	//Commitment CTA
	$wp_customize->add_setting('el_commitment_program_cta_enabled', array('default' => 1, 'transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_background_image', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_background_colour', array('default' => '#f5f5f5', 'transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_title', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_content', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_button_text', array('default' => 'View More', 'transport' => 'refresh'));
	$wp_customize->add_setting('el_commitment_program_cta_button_url', array('transport' => 'refresh'));
	
	//Appointment CTA
	$wp_customize->add_setting('el_appointment_cta_enabled', array('default' => 1, 'transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_background_image', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_background_colour', array('default' => '#ffffff', 'transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_title', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_content', array('transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_button_text', array('default' => 'View More', 'transport' => 'refresh'));
	$wp_customize->add_setting('el_appointment_cta_button_url', array('transport' => 'refresh'));
	
	
	
	//Controls	
	
	
	
	
	//Header Banner 
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_header_banner_background_image',
			array(
				'label'			=> __('Background Image', 'perfectvision'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_header_banner_overlay_colour',
			array(
				'label'			=> __('Overlay Colour', 'perfectvision'),
				'decription'	=> __('Colour that\'s applied on top of the image for clarity', 'perfectvision'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_header_banner_text_colour',
			array(
				'label'			=> __('Text Colour', 'perfectvision'),
				'decription'	=> __('Colour used for both the title and subtitle text', 'perfectvision'),
				'section'		=> 'el_header_banner'
			)
		)
	);
	
	$wp_customize->add_control('el_header_banner_title',
		array(
			'label'			=> __('Title', 'perfectvision'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'text',
			'setting'		=> 'el_header_banner_title'
		)
	);
	
	$wp_customize->add_control('el_header_banner_subtitle',
		array(
			'label'			=> __('Subtitle', 'perfectvision'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'textarea',
			'setting'		=> 'el_header_banner_subtitle'
		)
	);
	//primary button
	$wp_customize->add_control('el_header_banner_primary_button_text',
		array(
			'label'			=> __('Primary Button Text', 'perfectvision'),
			'description'	=> __('Text used on the first action button', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'text',
			'setting'		=> 'el_header_banner_primary_button_text'
		)
	);
	$wp_customize->add_control('el_header_banner_primary_button_url',
		array(
			'label'			=> __('Primary Button URL', 'perfectvision'),
			'description'	=> __('Full URL to the target page for this button', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'url',
			'setting'		=> 'el_header_banner_primary_button_url'
		)
	);
	//secondary button
	$wp_customize->add_control('el_header_banner_secondary_button_text',
		array(
			'label'			=> __('Primary Button Text', 'perfectvision'),
			'description'	=> __('Text used on the second action button', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'text',
			'setting'		=> 'el_header_banner_secondary_button_text'
		)
	);
	$wp_customize->add_control('el_header_banner_secondary_button_url',
		array(
			'label'			=> __('Primary Button URL', 'perfectvision'),
			'description'	=> __('Full URL to the target page for this button', 'perfectvision'),
			'section'		=> 'el_header_banner',
			'type'			=> 'url',
			'setting'		=> 'el_header_banner_secondary_button_url'
		)
	);

	
	
	//Commitment Program CTA (Above Footer)
	$wp_customize->add_control('el_commitment_program_cta_enabled',
		array(
			'label'			=> __('Enable CTA', 'perfectvision'),
			'description'	=> __('Show or hide the Commitment Program CTA', 'perfectvision'),
			'section'		=> 'el_commitment_program_cta',
			'type'			=> 'checkbox',
			'setting'		=> 'el_commitment_program_cta_enabled'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_commitment_program_cta_background_image',
			array(
				'label'			=> __('Background Image', 'perfectvision'),
				'section'		=> 'el_commitment_program_cta'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_commitment_program_cta_background_colour',
			array(
				'label'			=> __('Background Colour', 'perfectvision'),
				'decription'	=> __('Background colour used for the CTA section', 'perfectvision'),
				'section'		=> 'el_commitment_program_cta'
			)
		)
	);
	$wp_customize->add_control('el_commitment_program_cta_title',
		array(
			'label'			=> __('Title', 'perfectvision'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'perfectvision'),
			'section'		=> 'el_commitment_program_cta',
			'type'			=> 'text',
			'setting'		=> 'el_commitment_program_cta_title'
		)
	);
	$wp_customize->add_control('el_commitment_program_cta_content',
		array(
			'label'			=> __('Content', 'perfectvision'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'perfectvision'),
			'section'		=> 'el_commitment_program_cta',
			'type'			=> 'textarea',
			'setting'		=> 'el_commitment_program_cta_content'
		)
	);
	$wp_customize->add_control('el_commitment_program_cta_button_text',
		array(
			'label'			=> __('Button Text', 'perfectvision'),
			'description'	=> __('Text used for the CTA button', 'perfectvision'),
			'section'		=> 'el_commitment_program_cta',
			'type'			=> 'text',
			'setting'		=> 'el_commitment_program_cta_button_text'
		)
	);
	$wp_customize->add_control('el_commitment_program_cta_button_url',
		array(
			'label'			=> __('Button URL', 'perfectvision'),
			'description'	=> __('Full URL to the target page', 'perfectvision'),
			'section'		=> 'el_commitment_program_cta',
			'type'			=> 'url',
			'setting'		=> 'el_header_banner_secondary_button_url'
		)
	);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
	//Appointment CTA (Above Footer)
	$wp_customize->add_control('el_appointment_cta_enabled',
		array(
			'label'			=> __('Enable CTA', 'perfectvision'),
			'description'	=> __('Show or hide the Commitment Program CTA', 'perfectvision'),
			'section'		=> 'el_appointment_cta',
			'type'			=> 'checkbox',
			'setting'		=> 'el_appointment_cta_enabled'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_appointment_cta_background_image',
			array(
				'label'			=> __('Background Image', 'perfectvision'),
				'section'		=> 'el_appointment_cta'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'el_appointment_cta_background_colour',
			array(
				'label'			=> __('Background Colour', 'perfectvision'),
				'decription'	=> __('Background colour used for the CTA section', 'perfectvision'),
				'section'		=> 'el_appointment_cta'
			)
		)
	);
	$wp_customize->add_control('el_appointment_cta_title',
		array(
			'label'			=> __('Title', 'perfectvision'),
			'description'	=> __('Displayed at the top of the CTA as a large title', 'perfectvision'),
			'section'		=> 'el_appointment_cta',
			'type'			=> 'text',
			'setting'		=> 'el_appointment_cta_title'
		)
	);
	$wp_customize->add_control('el_appointment_cta_content',
		array(
			'label'			=> __('Content', 'perfectvision'),
			'description'	=> __('Displays the main content for the CTA, used as standard text', 'perfectvision'),
			'section'		=> 'el_appointment_cta',
			'type'			=> 'textarea',
			'setting'		=> 'el_appointment_cta_content'
		)
	);
	$wp_customize->add_control('el_appointment_cta_button_text',
		array(
			'label'			=> __('Button Text', 'perfectvision'),
			'description'	=> __('Text used for the CTA button', 'perfectvision'),
			'section'		=> 'el_appointment_cta',
			'type'			=> 'text',
			'setting'		=> 'el_appointment_cta_button_text'
		)
	);
	$wp_customize->add_control('el_appointment_cta_button_url',
		array(
			'label'			=> __('Button URL', 'perfectvision'),
			'description'	=> __('Full URL to the target page', 'perfectvision'),
			'section'		=> 'el_appointment_cta',
			'type'			=> 'url',
			'setting'		=> 'el_appointment_cta_button_url'
		)
	);
	
	

	
	
	
	
	
	//add support for selective refresh for selective theme mods
	//https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
	// if(version_compare($GLOBALS['wp_version'], '4.5', '>=')){
// 			
		// //TODO: These render functions should be called from OBJECTS to keep the logic DRY	
// 			
		// //WP 4.5 refresh partial
		// $wp_customize->selective_refresh->add_partial('el_header_cta_title', array(
			// 'selector'			=> '.header-hero-customizer .title',
			// 'render_callback'	=> function(){
				// $el_header_cta_title = get_theme_mod('el_header_cta_title');
				// echo $el_header_cta_title;
			// }
		// ));
		// $wp_customize->selective_refresh->add_partial('el_header_cta_subtitle', array(
			// 'selector'			=> '.header-hero-customizer .subtitle',
			// 'render_callback'	=> function(){
				// $el_header_cta_subtitle = get_theme_mod('el_header_cta_subtitle');
				// echo $el_header_cta_subtitle;
			// }
		// ));
		// $wp_customize->selective_refresh->add_partial('el_header_cta_background_colour', array(
			// 'selector'			=> '.header-hero-customizer .background-color',
			// 'render_callback'	=> function(){
				// $el_header_cta_background_colour = get_theme_mod('el_header_cta_background_colour');
				// var_dump($el_header_cta_background_colour);
				// if(!empty($el_header_cta_background_colour)){
					// $style = '';
					// $style .= (!empty($el_header_cta_background_colour)) ? 'background-color: ' . $el_header_cta_background_colour . '; ' : '';
					// echo '<div class="background-color" style="' . $style . '"></div>';
				// }
// 				
			// }
		// ));
		// $wp_customize->selective_refresh->add_partial('el_header_cta_background_image', array(
			// 'selector'			=> '.header-hero-customizer .background-image',
			// 'render_callback'	=> function(){
				// $el_header_cta_background_image = get_theme_mod('el_header_cta_background_image');
				// if(!empty($el_header_cta_background_image)){
					// $style = '';
					// $style .= (!empty($el_header_cta_background_image)) ? 'background-image: url(' . $el_header_cta_background_image . '); ' : '';
					// echo '<div class="background-image" style="' . $style . '"></div>';	
				// }
// 				
// 				
			// }
// 		
		// ));
	// }
	
	
	//Site Identity (default)
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'el_logo',
			array(
				'label'			=> __('Theme Logo','perfectvision'),
				'description'	=> __('Selct the logo used for this theme. Will be displayed in the header and across other locations', 'perfectvision'),
				'section'		=> 'title_tagline'
			)
		)
	);
	
	
	//Social media settings
	$wp_customize->add_control('el_facebook_url', 
		array(
			'label'			=> __('Facebook URL','perfectvision'),
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
			'label'			=> __('Twitter URL','perfectvision'),
			'section'		=> 'el_social_media',
			'type'			=> 'text',
			'setting'		=> 'el_twitter_url',
			'input_attrs'	=> array(
				'placeholder'	=> 'Full URL to Twitter Page'
			)
		)
	);

	
	
	
	
}
add_action('customize_register', 'el_customizer_functionality', 15);







function perfectvision_customize_preview_js() {
	wp_enqueue_script( 'perfectvision_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'perfectvision_customize_preview_js' );