<?php
/**
 * ycc functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ycc
 */

 /**
  * Main function for site, loads other requires modules
  */
 class ycc_theme{
 	
	private static $instance = null;
	private $number_of_footer_widgets = 4;
	
	/**
	 * Main Constrcutor
	 */
	public function __construct(){
		
		$this->load_customizer_functionality();
		$this->load_el_content_type_class();
		$this->load_products_content_type();
		$this->load_faq_content_type();
		$this->load_specials_content_type();
	
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles'));
		add_action('widgets_init', array($this, 'register_widget_areas')); //registeres widget zones / sidebars
		add_action('widgets_init', array($this, 'register_widgets')); //new widgets for use in theme
		add_action('init', array($this, 'remove_content_from_post_types')); //removes the content block from some post types

				
		add_action('add_meta_boxes', array($this, 'register_post_metaboxes')); //registers metaboxes for use on posts, pages, products etc
		add_action('save_post', array($this, 'save_post_metaboxes')); //triggers on save, to save metabox fields
				
		//Custom action hooks (used in theme templates)
		add_action('el_display_footer_widgets', array($this, 'el_display_footer_widgets'));
		add_action('el_display_association_logos', array($this, 'el_display_association_logos'));
		add_action('el_display_theme_logo', array($this, 'el_display_theme_logo'));
		add_action('el_display_footer_logo', array($this, 'el_display_footer_logo'));
		add_action('el_display_header_cta', array($this, 'el_display_header_cta'), 10, 1); 
		add_action('el_display_header_banner', array($this, 'el_display_header_banner'));
		add_action('el_display_footer_cta', array($this, 'el_display_footer_cta'));
	}
	
	/**
	 * Returns (sets or gets) the instance of this class
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Registers metaboxes for use on post types
	 */
	public function register_post_metaboxes(){
		$post_types = array('post', 'page', 'products');
		
		foreach($post_types as $post_type){
			//Customise header CTA
			add_meta_box(
		        'header_cta',
		        __( 'Header CTA Customisation', 'ycc' ),
		        array($this, 'display_post_metaboxes'),
		        $post_type,
		        'normal',
		        'low',
		        array('metabox_id' => 'header_cta')
		    );
			//Customize header banner
			add_meta_box(
		        'header_banner',
		        __( 'Header Banner Customisation', 'ycc' ),
		        array($this, 'display_post_metaboxes'),
		        $post_type,
		        'normal',
		        'low',
		        array('metabox_id' => 'header_banner')
		    );
		}
		
	}

	/**
	 * Render function for displaying our post metaboxes
	 */
	public function display_post_metaboxes($post, $args){
			
		
			
		$html = '';
		
		//header CTA metabox 
		if($args['args']['metabox_id'] == 'header_cta'){
			
			
			//We use the 'el_content_type' master class to render our fields for simplicity
			$html .= '<p>Complete the following information if you would like to customise the header CTA displayed on this page</p>';
			
			$fields = array(
			
				array(
					'id'			=> 'el_header_cta_show',
					 'title'		=> __('Hide Header CTA','ycc'),
					 'description'	=> __('By default the header CTA is shown, you can forcefully hide this if you wish'),
					 'type'			=> 'checkbox',
					 'args'			=> array(
						'options'	   => array(
							array('id' => 'hide', 'title' => 'Hide CTA'),
						)
					) 
				),
				array(
					 'id'			=> 'el_header_cta_enabled',
					 'title'		=> __('Customise Header CTA','ycc'),
					 'description'	=> __('Determine if you want to customize the header CTA'),
					 'type'			=> 'select',
					 'args'			=> array(
						'options'	   => array(
							array('id' => 'no', 'title' => 'No'),
							array('id' => 'yes', 'title' => 'Yes')
						)
					) 
				),
				array(
					 'id'			=> 'el_header_cta_title',
					 'title'		=> __('Title','ycc'),
					 'description'	=> __('Displayed at the top of the CTA as a large title (<b>Note:</b> if not entered will default to the post title)'),
					 'type'			=> 'text'
				),
				array(
					 'id'			=> 'el_header_cta_subtitle',
					 'title'		=> __('Subtitle','ycc'),
					 'description'	=> __('Displays the main content for the CTA, used as standard text'),
					 'type'			=> 'text'
				),
			);
		}
		//header banner metabox
		if($args['args']['metabox_id'] == 'header_banner'){
			
			$fields = array(
				array(
					 'id'			=> 'el_header_banner_enabled',
					 'title'		=> __('Customise Header Banner','ycc'),
					 'description'	=> __('Determine if you want to customize the header banner'),
					 'type'			=> 'select',
					 'args'			=> array(
						'options'	   => array(
							array('id' => 'No', 'title' => 'No'),
							array('id' => 'yes', 'title' => 'Yes')
						)
					) 
				),
				array(
					 'id'			=> 'el_header_banner_background_image',
					 'title'		=> __('Background Image','ycc'),
					 'description'	=> __('Select the image you want displayed in the banner'),
					 'type'			=> 'upload-image'
				),
				array(
					 'id'			=> 'el_header_banner_overlay_colour',
					 'title'		=> __('Overlay Colour','ycc'),
					 'description'	=> __('Colour that\'s applied on top of the image for clarity'),
					 'type'			=> 'color'
				),
				array(
					 'id'			=> 'el_header_banner_text_colour',
					 'title'		=> __('Text Colour','ycc'),
					 'description'	=> __('Colour used for both the title and subtitle text'),
					 'type'			=> 'color'
				),
				array(
					 'id'			=> 'el_header_banner_title',
					 'title'		=> __('Title','ycc'),
					 'description'	=> __('Displayed at the top of the CTA as a large title'),
					 'type'			=> 'text'
				),
				array(
					 'id'			=> 'el_header_banner_subtitle',
					 'title'		=> __('Title','ycc'),
					 'description'	=> __('Displays the main content for the banner, used as standard text'),
					 'type'			=> 'text'
				),
			);
			
		}
		

		//go through fields and render
		foreach($fields as $field){	
			$html .= el_content_type::render_metafield_element($field, $post);
		}
		
		
		echo $html;
	}

	/**
	 * Called when we save post, saves additional metadata that overrides the header CTA / banner on a post by post basis
	 */
	public function save_post_metaboxes($post_id){
			
		$post_types = array('page','post', 'products');	
		$post_type = get_post_type($post_id);

		if(in_array($post_type, $post_types)){
			
			//header CTA 
			$el_header_cta_show  = isset($_POST['el_header_cta_show']) ? json_encode($_POST['el_header_cta_show']) : '';
			$el_header_cta_enabled = isset($_POST['el_header_cta_enabled']) ? sanitize_text_field($_POST['el_header_cta_enabled']) : '';
			$el_header_cta_title = isset($_POST['el_header_cta_title']) ? sanitize_text_field($_POST['el_header_cta_title']) : '';
			$el_header_cta_subtitle = isset($_POST['el_header_cta_subtitle']) ? sanitize_text_field($_POST['el_header_cta_subtitle']) : '';
			update_post_meta($post_id, 'el_header_cta_show', $el_header_cta_show);
			update_post_meta($post_id, 'el_header_cta_enabled', $el_header_cta_enabled);
			update_post_meta($post_id, 'el_header_cta_title', $el_header_cta_title);
			update_post_meta($post_id, 'el_header_cta_subtitle', $el_header_cta_subtitle);
			
			
			//Banner 
			$el_header_banner_enabled = isset($_POST['el_header_banner_enabled']) ? sanitize_text_field($_POST['el_header_banner_enabled']) : '';
			$el_header_banner_background_image = isset($_POST['el_header_banner_background_image']) ? sanitize_text_field($_POST['el_header_banner_background_image']) : '';
			$el_header_banner_overlay_colour = isset($_POST['el_header_banner_overlay_colour']) ? sanitize_text_field($_POST['el_header_banner_overlay_colour']) : '';
			$el_header_banner_text_colour = isset($_POST['el_header_banner_text_colour']) ? sanitize_text_field($_POST['el_header_banner_text_colour']) : '';
			$el_header_banner_title = isset($_POST['el_header_banner_title']) ? sanitize_text_field($_POST['el_header_banner_title']) : '';
			$el_header_banner_subtitle = isset($_POST['el_header_banner_subtitle']) ? sanitize_text_field($_POST['el_header_banner_subtitle']) : '';
		
			update_post_meta($post_id, 'el_header_banner_enabled', $el_header_banner_enabled);
			update_post_meta($post_id, 'el_header_banner_background_image', $el_header_banner_background_image);
			update_post_meta($post_id, 'el_header_banner_overlay_colour', $el_header_banner_overlay_colour);
			update_post_meta($post_id, 'el_header_banner_text_colour', $el_header_banner_text_colour);
			update_post_meta($post_id, 'el_header_banner_title', $el_header_banner_title);
			update_post_meta($post_id, 'el_header_banner_subtitle', $el_header_banner_subtitle);

		}
		
	}

	
	/**
	 * Removes the content block from certain post types as it's not needed
	 * 
	 * Posts and pages have their content removed as it's replaces with Advanced Custom Fields flexible content blocks. Configured and out
	 * putted on each template
	 */
	public function remove_content_from_post_types(){
		
		$post_types = array('post','page');
		
		foreach($post_types as $post_type){
			remove_post_type_support($post_type, 'editor');
		}
		
		
		
	}
	

	/**
	 * Localises scripts, gives our JS scripts access to variables
	 */
	public function localize_scripts(){
		
		//Add our colours to our admin scripts (so they can be used in colour pickers)
		$theme_colours = array(
			'blue_normal' 	=> '#008da8',
			'blue_dark' 	=> '#025f72',
			'blue_light'	=> '#63d7ee',
			'red_normal'	=> '#9f393d',
			'red_dark'		=> '#6e1f22',
			'black'			=> '#000000',
			'white'			=> '#ffffff'
		);
		wp_localize_script('theme-admin-script', 'el_theme_colours', $theme_colours);
	}
	
	/**
	 * register generic widgets for the theme.
	 * - Download object widget (used for downloading a file)
	 * - Image widget (used for displaying a single image)
	 * - Contact Widget
	 */
	public function register_widgets(){
		register_widget('el_download_widget');
		register_widget('el_contact_us_widget');
		register_widget('el_editor_widget');
	}
	
	/**
	 * Loads the generic content type master class for us to use
	 * 
	 * Used to create basic content types with the ability to add metaboxes, taxonomies and other elements
	 * @link https://github.com/elevate360/El-Content-Type
	 */
	public function load_el_content_type_class(){
		require get_template_directory() . '/inc/el_content_type.php';
	}
	
	/**
	 * Load the FAQ content type used throughout the site
	 */
	public function load_faq_content_type(){
		require get_template_directory() . '/inc/faq/el_faq.php';
		$el_faq = $this->get_faq_object();
	}
	/**
	 * Loads the special content type
	 */
	public function load_specials_content_type(){
		require get_template_directory() . '/inc/specials/el_specials.php';
		$el_specials = $this->get_specials_object();
		
	}
	
	/**
	 * Loads the product content type for use
	 * 
	 * Loads the products file located in '/inc/products/el_products.php'
	 */
	public function load_products_content_type(){
		require get_template_directory() . '/inc/products/el_products.php';
		$el_products = $this->get_products_object();
		
	}
	
	/**
	 * Loads the customizer functionality for the theme
	 */
	public function load_customizer_functionality(){
		require get_template_directory() . '/inc/customizer/customizer.php';
	}
	
	
	/**
	 * Gets the 'el_product' object instance 
	 * 
	 * Used for when we need to get the products object for manipulation so we don't hard code 
	 * it's value in templates 
	 */
	public function get_products_object(){
			
		$result = false;
		
		if(class_exists('el_products')){
			$result = el_products::getInstance();
		}
		
		return $result; 
	}
	
	/**
	 * Gets the 'el_faq' object instance
	 * 
	 * Used when we need to access methods and elements specific to that class
	 */
	public function get_faq_object(){
			
		$result = false;
		if(class_exists('el_faq')){
			$result = el_faq::getInstance();
		}
		return $result;
	}
	
	/**
	 * Gets the 'el_specials' object instance
	 * 
	 * Used to access specific functions inside that class
	 */
	public function get_specials_object(){
		$result = false;
		if(class_exists('el_specials')){
			$result = el_specials::getInstance();
		}
		return $result;
	}
	
	/**
	 * Gets the HTML markup for the fixed social media bar displayed on the right hand side of the site
	 * 
	 * Pulls in settings from the customizer (Fixed social bar) such as where it links to 
	 */
	public function get_fixed_social_bar_html(){
		
		$html = '';
		
		//look for customizer elements
		$el_twitter_url = get_theme_mod('el_twitter_url');
		$el_facebook_url = get_theme_mod('el_facebook_url');
		$el_show_link_to_order_page = get_theme_mod('el_show_link_to_order_page');
		
		$html .= '<div class="sticky-side-element">';
		
		if(!empty($el_show_link_to_order_page) && $el_show_link_to_order_page){
			$url = '';
			$html .= '<div class="element quote">';
				$html .= '<a href="' . $url . '" target="_blank" title="Place your order!">';
					$html .= __('Place Your Order!', 'ycc');
				$html .= '</a>';
			$html .= '</div>';
		}
		if(!empty($el_twitter_url)){
			$html .= '<div class="element twitter">';
				$html .= '<a href="' . $el_twitter_url . '" target="_blank" title="Twitter">';
					$html .= '<i class="fa fa-twitter" aria-hidden="true"></i>';
				$html .= '</a>';
			$html .= '</div>';
		}
		if(!empty($el_facebook_url)){
			$html .= '<div class="element facebook">';
				$html .= '<a href="' . $el_facebook_url .'" target="_blank" title="Facebook">';
					$html .= '<i class="fa fa-facebook" aria-hidden="true"></i>';
				$html .= '</a>';
			$html .= '</div>';
		}
		
		
	
		
		
		$html .= '</div>';
		
		
		return $html;
	}
	
	/**
	 * Loads public facing CSS/JS
	 */
	public function enqueue_public_scripts_and_styles(){
		$dir = get_stylesheet_directory_uri();
		wp_enqueue_style('theme-fonts', '//fonts.googleapis.com/css?family=Dosis:300,400,500,600,700');
		wp_enqueue_style('theme-font-awesome-icons' , '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_script('theme-scripts', $dir . '/js/theme_public_scripts.js', array('jquery'));
	}

	/**
	 * Enqueue admin only CSS / JS
	 */
	public function enqueue_admin_scripts_and_styles(){
		
		$dir = get_stylesheet_directory_uri();
		wp_enqueue_script('theme-admin-script', $dir . '/js/theme_admin_scripts.js', array('jquery','wp-color-picker','jquery-ui-sortable'));
		wp_enqueue_style('theme-admin-styles', $dir . '/inc/theme_admin_stylesheet.css');
		wp_enqueue_style('wp-color-picker');
		
		$this->localize_scripts();
	}
	
	/**
	 * Register theme widget areas
	 */
	function register_widget_areas() {
		
		//primary sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar' , 'ycc' ),
			'id'            => 'sidebar-primary',
			'description'   => esc_html__( 'Standard sidebar', 'ycc' ),
			'before_widget' => '<section id="%1$s" class="widget small-padding-top-bottom-small el-col-small-12 %2$s ">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		
		//Contact sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Contact Us Sidebar' , 'ycc' ),
			'id'            => 'sidebar-contact',
			'description'   => esc_html__( 'Sidebar displayed on the \'Contact Page\' template. Used to provide additional info to the user', 'ycc' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			)
		);
		
		//wholesale sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Wholesale Sidebar' , 'ycc' ),
			'id'            => 'sidebar-wholesale',
			'description'   => esc_html__( 'Sidebar displayed on the \'Wholesale Page\' template.', 'ycc' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			)
		);
		
		//News sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'News Sidebar' , 'ycc' ),
			'id'            => 'sidebar-news',
			'description'   => esc_html__( 'Sidebar displayed on the blog listing page. Used to display filters for searching through the news', 'ycc' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			)
		);
		
		//Products sidebar 
		register_sidebar( array(
			'name'          => esc_html__( 'Products Sidebar' , 'ycc' ),
			'id'            => 'sidebar-products',
			'description'   => esc_html__( 'Sidebar displayed on the \'Products\' template, product category pages and single product template', 'ycc' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			)
		);
	

		//footer widget zones
		for($i = 1; $i <= $this->number_of_footer_widgets; $i++){
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar ' . $i , 'ycc' ),
				'id'            => 'footer-sidebar-' . $i,
				'description'   => esc_html__( 'Widget displayed in your sites footer', 'ycc' ),
				'before_widget' => '<section id="%1$s" class="widget small-padding-top-bottom-small el-col-small-12 collapse %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
			
		}
	
	}

	/**
	 * Displays any Advanced Custom Fields page templates that this post has assigned to it. Template layouts are
	 * shared across post types so we have this one function to render them.
	 */
	public static function display_page_templates($post_id){
		
		if(!$post_id){
			return false;
		}

		$instance = self::getInstance();
		
		$html = '';
		$html .= $instance->get_page_templates_html($post_id);
		
		echo $html;
	}
	
	/**
	 * Creates the HTML markup for the Advanced Custom Field template sections on each page / post.
	 */
	public function get_page_templates_html($post_id){
		
		if(!$post_id){
			return false;
		}
		
		$html = '';	
			
		if( have_rows('content_layout_templates', $post_id) ){
			
			while( have_rows('content_layout_templates') ){
				
				$row = the_row();
				
				$layout = get_row_layout();
				
				//left and right column layout (with optional highlight background)
				if($layout == 'left_right_content_section' || $layout == 'left_right_highlight_section'){
					
					$title = get_sub_field('title');
					$left_content = get_sub_field('left_column_text');
					$right_content = get_sub_field('right_column_text');
									
					$html .= '<div class="el-row small-margin-bottom-medium small-padding-top-bottom-small  content_section ' . $layout .'">';
						if(!empty($title)){
							//adjust title based on which section it's used in	
							if($layout == 'left_right_content_section'){
								$html .= '<h2 class="el-col-small-12">' . $title . '</h2>';
							}else{
								$html .= '<h3 class="el-col-small-12">' . $title . '</h3>';
							}
						}	
						if(!empty($left_content)){
							$html .= '<div class="el-col-small-12 el-col-medium-6">';
								$html .= $left_content;
							$html .= '</div>';
						}
						if(!empty($right_content)){
							$html .= '<div class="el-col-small-12 el-col-medium-6">';
								$html .= $right_content;
							$html .= '</div>';
						}

					$html .= '</div>';
	
				}
				//full content section
				else if($layout == 'full_content_section'){
					
					$content = get_sub_field('content');
					
					if(!empty($content)){
						$html .= '<div class="el-col-small-12 small-padding-top-bottom-small content-section"' . $layout . '">';
							$html .= $content;
						$html .= '</div>';
					}
					
				}
				//centered CTA Section
				else if($layout == 'standard_cta_section'){
					
					$title = get_sub_field('title');
					$subtitle = get_sub_field('subtitle');
					$button_url_object = get_sub_field('button_url');
					$button_text = get_sub_field('button_text');
					$background_colour = get_sub_field('background_colour');
					$text_colour = get_sub_field('text_colour');
					
					$style = '';
					$style .= !empty($background_colour) ? 'background-color: ' . $background_colour . '; ' : '';
					$style .= !empty($text_colour) ? 'color: ' . $text_colour . '; ' : '';
					$html .= '<div class="el-col-small-12 small-align-center small-padding-top-bottom-medium content-section ' . $layout . '" style="' . $style . '">';
						if(!empty($title)){
							$html .= '<h2 class="title">' . $title . '</h2>';
						}
						if(!empty($subtitle)){
							$html .= '<div class="subtitle">' . $subtitle . '</div>';
						
						}
		
						//cta button
						if(!empty($button_url_object)){
							$resource_id = $button_url_object->ID;
							$url = get_permalink($resource_id);
							
							$html .= '<a class="button primary-button" href="' . $url . '">' . $button_text  . '</a>';
						}
						
					$html .= '</div>';
					
					
				}
				//Full width image section
				else if($layout == 'full_width_image_section'){
						
					$image_id = get_sub_field('image'); 

					if(!empty($image_id)){
						$image = wp_get_attachment_image($image_id, 'large', false);
						
						$html .= '<div class="el-row small-margin-bottom-medium small-align-center content-section ' . $layout .' ">';
							$html .= '<div class="el-col-small-12">';
								$html .= $image;
							$html .= '</div>';
						$html .= '</div>';
					}
				}
				//Half width image section
				else if($layout == 'half_width_image_section'){
						
					$image_array = get_sub_field('image'); 
					
					if(!empty($image_array)){
						$image = wp_get_attachment_image($image_array['ID'], 'large', false);
						
						$html .= '<div class="el-row small-margin-bottom-medium small-align-center content-section ' . $layout . '">';
							$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-medium-offset-3">';
								$html .= $image;
							$html .= '</div>';
						$html .= '</div>';
					}
				}
				
				
			}	
		}
		
		return $html;
				
	} 
	
	/**
	 * Displays the fixed social media and link bar on the right hand side of the screen
	 */
	public static function el_display_fixed_social_bar(){
		
		$instance = self::getInstance();
		$html = '';
		
		$html .= $instance->get_fixed_social_bar_html();
		
		echo $html;
	}
	
	/**
	 * Displays either the default header banner set in the theme customizer or a customized version 
	 * that's dictated on a page by page approach. 
	 */
	public static function el_display_header_banner($object){
		
		$instance = self::getInstance();
		$html = '';
		
		$post_types = array('post', 'page', 'products');
		$display_custom_banner = false;
		
		//object is a post, not term or misc
		if($object instanceof WP_Post){
			
			$post_type = get_post_type($object->ID);
			
			//on applicable post type
			if(in_array($post_type, $post_types)){
				
				$el_header_banner_enabled = get_post_meta($object->ID, 'el_header_banner_enabled', true);		
				//opted to override CTA
				if($el_header_banner_enabled == 'yes'){
					$display_custom_banner = true;
				}
			}
			
		}
		
	
		
		//overriden banner on page level
		if($display_custom_banner == true){
			
			
			$el_header_banner_background_image = get_post_meta($object->ID, 'el_header_banner_background_image', true);
			$el_header_banner_overlay_colour = get_post_meta($object->ID, 'el_header_banner_overlay_colour', true);
			$el_header_banner_text_colour = get_post_meta($object->ID, 'el_header_banner_text_colour', true);
			$el_header_banner_title = get_post_meta($object->ID, 'el_header_banner_title', true);
			$el_header_banner_subtitle = get_post_meta($object->ID, 'el_header_banner_subtitle', true);
			$el_post_title = apply_filters('post_title', $object->post_title);
			
			
			
			
			$html .= '<section class="header-banner el-row small-align-center">';

				//background image
				$style = '';
				if(!empty($el_header_banner_background_image)){
					$url = wp_get_attachment_image_src($el_header_banner_background_image, 'large', false)[0];
					$style .= 'background-image: url(' . $url . ');';
					$html .= '<div class="background-image" style="background-image:url(' . $url . ');"></div>';
				}
				
				//overlay
				if(!empty($el_header_banner_overlay_colour)){
					$html .= '<div class="overlay" style="background-color: ' . $el_header_banner_overlay_colour .';"></div>';
				}
				
				//main content
				$style = '';
				$style .= !empty($el_header_banner_text_colour) ? 'color: ' . $el_header_banner_text_colour . '; ' : '';
				$html .= '<div class="content el-col-small-12" style="' . $style . '">';	
					$html .= '<div class="el-row inner small-padding-top-bottom-large medium-padding-top-bottom-xx-large">';
						//use the post title if we dont have a custom title set
						if(!empty($el_header_banner_title)){
							$html .= '<h1 class="title">' . $el_header_banner_title . '</h1>';
						}else{
							$html .= '<h1 class="title">' . $el_post_title . '</h1>';
						}
						if(!empty($el_header_banner_subtitle)){
							$html .= '<p class="subtitle">' . $el_header_banner_subtitle . '</p>';
						}
						//featured image badge
						if(!empty($el_header_banner_featured_image)){
							$html .= '<div class="badge" style="background-image:url(' . $el_header_banner_featured_image . ');"></div>';	
						}
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</section>';
			
			
		}
		//display customizer version
		else{
			
			$el_header_banner_enabled = get_theme_mod('el_header_banner_enabled');
			$el_header_banner_background_image = get_theme_mod('el_header_banner_background_image');
			$el_header_banner_featured_image = get_theme_mod('el_header_banner_featured_image');
			$el_header_banner_overlay_colour = get_theme_mod('el_header_banner_overlay_colour');
			$el_header_banner_text_colour = get_theme_mod('el_header_banner_text_colour');
			$el_header_banner_title = get_theme_mod('el_header_banner_title');
			$el_header_banner_subtitle = get_theme_mod('el_header_banner_subtitle');
			
			//if we want it displayed 
			if($el_header_banner_enabled){
				
				$style = '';
				$style .= (!empty($el_header_banner_background_image)) ? 'background-image: url(' . $el_header_banner_background_image . '); ' : '';
				
				$html .= '<section class="header-banner el-row small-align-center">';

					//background image
					if(!empty($el_header_banner_background_image)){
						$html .= '<div class="background-image" style="background-image:url(' . $el_header_banner_background_image . ');"></div>';
					}
					
					//overlay
					if(!empty($el_header_banner_overlay_colour)){
						$html .= '<div class="overlay" style="background-color: ' . $el_header_banner_overlay_colour .';"></div>';
					}
					
					//main content
					$style = '';
					$style .= !empty($el_header_banner_text_colour) ? 'color: ' . $el_header_banner_text_colour . '; ' : '';
					$html .= '<div class="content el-col-small-12" style="' . $style . '">';	
						$html .= '<div class="el-row inner small-padding-top-bottom-large medium-padding-top-bottom-xx-large">';
							if(!empty($el_header_banner_title)){
								$html .= '<h1 class="title">' . $el_header_banner_title . '</h1>';
							}
							if(!empty($el_header_banner_subtitle)){
								$html .= '<p class="subtitle">' . $el_header_banner_subtitle . '</p>';
							}
							//featured image badge
							if(!empty($el_header_banner_featured_image)){
								$html .= '<div class="badge" style="background-image:url(' . $el_header_banner_featured_image . ');"></div>';	
							}
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</section>';
			}
			
		}
		
		
			
		
		
		
		
		
		
		


		
		
		echo $html;
		
	}
	
	/**
	 * Displays the footer CTA (content pulled from customizer)
	 */
	public static function el_display_footer_cta(){
			
		$instance = self::getInstance();
		$html = '';
		
		$html .= $instance->get_footer_cta();
		
		echo $html;
	}
	
	/**
	 * Determine if the current post overrides the banner.
	 */
	public function get_header_banner_custom($post_id){
		
		$applicable_types = array('page','post','products');
		$post_type = get_post_type($post_id);
		
		if(!$post_id){
			return false;
		}
		if(!in_array($post_type, $applicable_types)){
			return false; 
		}
		

		return $html ; 
		
	}
	
	/**
	 * Displays static association images in the theme
	 */
	public static function el_display_association_logos(){
		$instance = self::getInstance();
		
		$html = '';
		
		$html .= $instance->get_association_logos();
		
		
		
		
		echo $html;
	}
	
	/**
	 * Gets the association logos to be displayed on the site in a grid
	 */
	public function get_association_logos(){
		
		$html = '';
		
		$html .= '<div class="association-logos small-align-center">';
			$html .= '<div class="el-row inner inner-small small-padding-top-bottom-small medium-padding-top-bottom-medium">';
		
				$html .= '<div class="logo el-col-small-6 el-col-medium-3 small-padding-bottom-small">';
					$html .= '<img alt="haccp approved" src="' . get_stylesheet_directory_uri() . '/img/associations/logo-haccp-gray.png' . '"/>';
				$html .= '</div>';
				$html .= '<div class="logo el-col-small-6 el-col-medium-3 small-padding-bottom-small">';
					$html .= '<img alt="SGS" src="' . get_stylesheet_directory_uri() . '/img/associations/logo-sgs-gray.png' . '"/>';
				$html .= '</div>';
				$html .= '<div class="logo el-col-small-6 el-col-medium-3 small-padding-bottom-small">';
					$html .= '<img alt="Halal Certified" src="' . get_stylesheet_directory_uri() . '/img/associations/logo-halal-gray.png' . '"/>';
				$html .= '</div>';
				$html .= '<div class="logo el-col-small-6 el-col-medium-3 small-padding-bottom-small">';
					$html .= '<img alt="NSW Food Authority" src="' . get_stylesheet_directory_uri() . '/img/associations/logo-nswfoodauthority-gray.png' . '"/>';
				$html .= '</div>';
				
			$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	

	
	/**
	 * Gets the HTML output for the footer CTA element
	 * 
	 * Settings pulled from the back of the customizer 
	 */
	public static function get_footer_cta(){
		
		$instance = self::getInstance();
		$html = '';
		
		$el_footer_cta_enabled = get_theme_mod('el_footer_cta_enabled');
		$el_footer_cta_background_image = get_theme_mod('el_footer_cta_background_image');
		$el_footer_cta_featured_image = get_theme_mod('el_footer_cta_featured_image');
		$el_footer_cta_title = get_theme_mod('el_footer_cta_title');
		$el_footer_cta_subtitle = get_theme_mod('el_footer_cta_subtitle');
		

		if($el_footer_cta_enabled){
			$style = '';
			$style .= (!empty($el_header_cta_background_image)) ? 'background-image: url(' . $el_header_cta_background_image . '); ' : '';
			
			
			$html .= '<section class="footer-hero el-row" style="' . $style . '">';
				
				$html .= '<div class="el-row inner small-padding-top-botom-medium medium-padding-top-bottom-large">';
				
					//Display badge
					if(!empty($el_footer_cta_featured_image)){
						$html .= '<div class="badge" style="background-image:url(' . $el_footer_cta_featured_image .');"></div>';
					}
				
					//background image
					if(!empty($el_footer_cta_background_image)){
						$html .= '<div class="background-image" style="background-image:url(' . $el_footer_cta_background_image . ');"></div>';
					}
					//content
					$html .= '<div class="content small-margin-top-bottom-large el-col-small-12">';
							
						if(!empty($el_footer_cta_title)){
							$html .= '<h2 class="title">' . $el_footer_cta_title . '</h2>'; 
						}
						if(!empty($el_footer_cta_subtitle)){
							$html .= '<p class="subtitle h4">' . $el_footer_cta_subtitle . '</p>';
						}

						//TODO: Retail shop hours here
						$el_trading_hours_monday_friday = get_theme_mod('el_trading_hours_monday_friday');
						$el_trading_hours_saturday = get_theme_mod('el_trading_hours_saturday');
						$el_trading_hours_holidays = get_theme_mod('el_trading_hours_holidays');
						
						if(!empty($el_trading_hours_monday_friday)){
							$html .= '<div>';
							$html .= __('Monay - Friday: ', 'ycc') . $el_trading_hours_monday_friday;
							$html .= '</div>';
						}
						if(!empty($el_trading_hours_saturday)){
							$html .= '<div>';
							$html .= __('Saturday: ', 'ycc') . $el_trading_hours_saturday;
							$html .= '</div>';
						}
						if(!empty($el_trading_hours_holidays)){
							$html .= '<div>';
							$html .= __('Public Holidays / Sunday: ', 'ycc') . $el_trading_hours_holidays;
							$html .= '</div>';
						}

					$html .= '</div>';
				
				$html .= '</div>';
			$html .= '</section>';
		}
		
		return $html;
	}
	
	
	/**
	 * Displays the header CTA 
	 * 
	 * Displays either the default CTA set in the theme customizer or a customized version on
	 * a page by page basis.
	 */
	public static function el_display_header_cta($object){
		
		
		$instance = self::getInstance();
		$html = '';
		
		
		$post_types = array('post', 'page', 'products');
		$display_custom_banner = false;
		
		//object is a post, not term or misc
		if($object instanceof WP_Post){
			
			$post_type = get_post_type($object->ID);
			
			//on applicable post type
			if(in_array($post_type, $post_types)){
				
				$el_header_cta_enabled = get_post_meta($object->ID, 'el_header_cta_enabled', true);
					
				//opted to override CTA
				if($el_header_cta_enabled == 'yes'){
					$display_custom_banner = true;
				}
			}
			
		}
		
		//overriden CTA page level
		if($display_custom_banner == true){
			
			//customizer values
			$el_header_cta_background_image = get_theme_mod('el_header_cta_background_image');
			$el_header_cta_background_colour = get_theme_mod('el_header_cta_background_colour');	
			
			$el_header_cta_show = get_post_meta($object->ID, 'el_header_cta_show', true);
			if(!empty($el_header_cta_show)){
				$el_header_cta_show = json_decode($el_header_cta_show);
			}
			$el_header_cta_title = get_post_meta($object->ID, 'el_header_cta_title', true);
			$el_header_cta_subtitle = get_post_meta($object->ID, 'el_header_cta_subtitle', true);
			
		
			//if we have chosen not to show the CTA
			if(is_array($el_header_cta_show) && (in_array('hide', $el_header_cta_show))){
				return false;
			}
			
		
			$style = '';
			$style .= (!empty($el_header_cta_background_image)) ? 'background-image: url(' . $el_header_cta_background_image . '); ' : '';
			$style .= (!empty($el_header_cta_background_colour)) ? 'background-color: ' . $el_header_cta_background_colour . '; ' : '';
			
			$html .= '<section class="header-hero el-row" style="' . $style . '">';
				$html .= '<div class="el-row inner-small small-padding-top-xx-large medium-padding-top-x-large">';
				
					if(!empty($el_header_cta_title)){
						$html .= '<h2 class="title el-col-small-12 small-align-center">' . $el_header_cta_title . '</h2>'; 
					}
					if(!empty($el_header_cta_subtitle)){
						$html .= '<p class="subtitle el-col-small-12 small-align-center">' . $el_header_cta_subtitle . '</p>';
					}
				$html .= '</div>';
			$html .= '</section>';
			
			
		}
		//Display customizer default values
		else{
			//customizer values
			$el_header_cta_enabled = get_theme_mod('el_header_cta_enabled');
			$el_header_cta_background_image = get_theme_mod('el_header_cta_background_image');
			$el_header_cta_background_colour = get_theme_mod('el_header_cta_background_colour');
			$el_header_cta_title = get_theme_mod('el_header_cta_title');
			$el_header_cta_subtitle = get_theme_mod('el_header_cta_subtitle');
			
			if($el_header_cta_enabled){
				
				
				
				
				$html .= '<section class="header-hero header-hero-customizer el-row">';
					
					//background colour
					if(!empty($el_header_cta_background_colour)){
						$style = '';
						$style .= (!empty($el_header_cta_background_colour)) ? 'background-color: ' . $el_header_cta_background_colour . '; ' : '';
						$html .= '<div class="background-color" style="' . $style . '"></div>';
					}
					
					//background image
					if(!empty($el_header_cta_background_image)){
						$style = '';
						$style .= (!empty($el_header_cta_background_image)) ? 'background-image: url(' . $el_header_cta_background_image . '); ' : '';
						$html .= '<div class="background-image" style="' . $style . '"></div>';
					}

					$html .= '<div class="content el-row inner-small small-padding-top-xx-large medium-padding-top-x-large">';
						if(!empty($el_header_cta_title)){
							$html .= '<h2 class="title el-col-small-12 small-align-center">' . $el_header_cta_title . '</h2>'; 
						}
						if(!empty($el_header_cta_subtitle)){
							$html .= '<p class="subtitle el-col-small-12 small-align-center">' . $el_header_cta_subtitle . '</p>';
						}
					$html .= '</div>';
				$html .= '</section>';
			}
		}
		
		
		
		
		
		

		
		
		echo $html;
		
	}
	
	/**
	 * Displays a logo in the footer, setting pulled from the theme customiser
	 */
	public static function el_display_footer_logo(){
			
		$instance = self::getInstance();
		
		$html = '';
		
		$url = get_theme_mod('el_footer_logo');
		if($url){
			$html .= '<img src="' . $url . '" class="theme-logo"/>';
		}
		
		echo $html;
	}
	
	/**
	 * Hooked function to display footer widgets
	 */
	public static function el_display_footer_widgets(){
		
		$instance = self::getInstance();
		
		$html = '';
		
		//get all footer widgets 
		for($i = 1; $i <= $instance->number_of_footer_widgets; $i++){
			
			//last widgets are bigger
			$class = '';
			if($i == $instance->number_of_footer_widgets){
				$class = ' el-col-medium-3';
			}else{
				$class = ' el-col-medium-2';
			}
			$html .= '<div class="widget-area el-col-small-12 ' . $class . '">';
			
			ob_start();
			dynamic_sidebar('footer-sidebar-' . $i);
			$html .= ob_get_contents();
			
			
			$html .= '</div>';
			ob_end_clean();
		}
		
		
		
		echo $html;
	}
	
	/**
	 * Displays the theme logo, setting pulled from the theme customiser
	 */
	public static function el_display_theme_logo(){
			
		$instance = self::getInstance();
		
		$html = '';
		
		$url = get_theme_mod('el_logo');
		if($url){
			$html .= '<img src="' . $url . '" class="theme-logo"/>';
		}
		
		echo $html;
	}
	
	
 }
 $ycc_theme = ycc_theme::getInstance();
 
 
 
 /**
 * File Download or link widget
 * 
 * used to let users click on a widget and download a selected resource. Or visit a selected page on click
 */
 
 class el_download_widget extends WP_Widget{
 	
	public function __construct(){
			
		$args = array(
			'description'	=> 'Creates a simple download widget, useful for linking users to a single PDF or file'
		);
		
		parent::__construct(
			'el_download_widget', esc_html__('Simple Download / URL Widget', 'ycc'), $args
		);
		
	}

	
	/**
	 * Visual output frontend
	 */
	public function widget($args, $instance){
		
		$title = isset($instance['title']) ? $instance['title'] : '';
		$resource_id = isset($instance['resource_id']) ? $instance['resource_id'] : '';
		$background_id = isset($instance['background_id']) ? $instance['background_id'] : '';
		$background_colour = isset($instance['background_colour']) ? $instance['background_colour'] : '';
		$text_colour = isset($instance['text_colour']) ? $instance['text_colour'] : '';
		$link_url = isset($instance['link_url']) ? $instance['link_url'] : ''; 
		
		$html = '';
		
		$html .= $args['before_widget'];
		
		
			$style = '';
			$style .= !empty($background_colour) ? 'background-color: ' . $background_colour . '; ' : '';
			$style .= !empty($text_colour) ? 'color: ' . $text_colour . '; ' : '';
			
			$html .= '<div class="widget-wrap" style="' . $style . '">';
			
				//widget background
				if(!empty($background_id)){
					$background_url = wp_get_attachment_image_src($background_id, 'medium', false)[0];
					$html .= '<div class="background-image" style="background-image:url(' . $background_url . ');"></div>';
;				}
			
				//title if supplied
				if(isset($instance['title'])){
					$html .= $args['before_title'];
						$html .= $instance['title'];
					$html .= $args['after_title'];	
				}
				
				//main content
				$html .= '<div class="widget-content">';
					//Link to a resource
					if(!empty($resource_id)){
						$resource_url = get_permalink($resource_id);
						
						$style = '';
						$style .= !empty($text_colour) ? 'border-color: ' . $text_colour . '; ' : '';
						$html .= '<a download class="download" style="' . $style . '" href="' . $resource_url . '"><i class="fa fa-angle-down" aria-hidden="true"></i></a>';
					}
					//Link to a URL
					if(!empty($link_url)){
						$style = '';
						$style .= !empty($text_colour) ? 'border-color: ' . $text_colour . '; ' : '';
						$html .= '<a class="download" style="' . $style . '" href="' . $link_url . '"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';
					}
				$html .= '</div>';
			
			$html .= '</div>';
		
		$html .= $args['after_widget'];
		
		
		echo $html;
		
		
	}
	
	/**
	 * Form output on admin
	 */
	public function form($instance){
		
		//enqueue media scripts
		wp_enqueue_media();	

		$title = isset($instance['title']) ? $instance['title'] : '';
		$background_id = isset($instance['background_id']) ? $instance['background_id'] : '';
		$resource_id = isset($instance['resource_id']) ? $instance['resource_id'] : '';
		$background_colour = isset($instance['background_colour']) ? $instance['background_colour'] : '';
		$text_colour = isset($instance['text_colour']) ? $instance['text_colour'] : '';
		$link_url = isset($instance['link_url']) ? $instance['link_url'] : '';
		
		
		$html = '';
		
		//TODO: Come back and adjust this to collect right pallets
		$html .= '<script id="test" type="text/javascript">';
		$html .= 'jQuery(document).ready(function($){
							
				//on update, set colours back up again
				$(document).on("widget-updated", function (event, $widget) {
					console.log("Updasted colour");
					$(".colorpicker-field").wpColorPicker({
						hide: true
					});
				});

			  });';
		$html .= '</script>';
		
		
			
		
		
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		
		//Resources Selection
		$field_name = $this->get_field_name('resource_id');
		$field_id = $this->get_field_id('resource_id');
		$html .= '<p>';
			$html .= '<div class="image-upload-container">';
				$html .= '<label for="' . $field_id . '">' . __('If you want this to link to a resource, select it below <br/>','ycc') . '</label>';
				$html .= '<input type="button" value="Select Resource" class="widget-upload image-upload-button" data-multiple-upload="false" data-file-type="image" data-field-name="' . $field_name .'"/>';
				$html .= '<div class="image-container cf">';
				
				if(!empty($resource_id)){
					$image_url = wp_get_attachment_image_src($resource_id, 'thumbnail', false)[0];
					
					$html .= '<div class="image">';
					$html .=	'<input type="hidden" id="' . $field_id . '" name="' . $field_name . '" value="' .  $resource_id . '"/>';
					$html .=	'<div class="image-preview" style="background-image:url(' . $image_url . ');"></div>';
					$html .=	'<div class="image-controls cf">';
					$html .=		'<div class="control remove_image">Remove Resource<i class="fa fa-minus"></i></div>';	
					$html .=	'</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
					
			$html .= '</div>';
		$html .= '</p>';
		
		//Link URL
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('link_url') . '">' . __('Link URL', 'ycc') .'</label></br>';
			$html .= '<span>If you want this widget instead to link to a URL, enter the full URL here </span>';
			$html .= '<input class="widefat" type="url" name="' . $this->get_field_name('link_url') . '" id="' . $this->get_field_id('link_url') . '" value="' . $link_url .'"/>';
		$html .= '</p>';
		
		//Background Image Selection
		$field_name = $this->get_field_name('background_id');
		$field_id = $this->get_field_id('background_id');
		
		$html .= '<p>';
			$html .= '<div class="image-upload-container">';
				$html .= '<label for="' . $field_id . '">' . __('Select the image that will be used as the background image <br/>','ycc') . '</label>';
				$html .= '<input type="button" value="Select Resource" class="widget-upload image-upload-button" data-multiple-upload="false" data-file-type="image" data-field-name="' . $field_name .'"/>';
				$html .= '<div class="image-container cf">';
				
				if(!empty($background_id)){
					$image_url = wp_get_attachment_image_src($background_id, 'thumbnail', false)[0];
					
					$html .= '<div class="image">';
					$html .=	'<input type="hidden" id="' . $field_id . '" name="' . $field_name . '" value="' .  $background_id . '"/>';
					$html .=	'<div class="image-preview" style="background-image:url(' . $image_url . ');"></div>';
					$html .=	'<div class="image-controls cf">';
					$html .=		'<div class="control remove_image">Remove Resource<i class="fa fa-minus"></i></div>';	
					$html .=	'</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
					
			$html .= '</div>';
		$html .= '</p>';
		
		
		
		
		//Background colour
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('background_colour') . '">' . __('Background Colour', 'ycc') .'</label></br>';
			$html .= '<input class="widefat colorpicker-field" type="text" name="' . $this->get_field_name('background_colour') . '" id="' . $this->get_field_id('background_colour') . '" value="' . $background_colour .'"/>';
		$html .= '</p>';

		//Text Colour
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('text_colour') . '">' . __('Text Colour', 'ycc') .'</label></br>';
			$html .= '<input class="widefat colorpicker-field" type="text" name="' . $this->get_field_name('text_colour') . '" id="' . $this->get_field_id('text_colour') . '" value="' . $text_colour .'"/>';
		$html .= '</p>';
	
	
		
		echo $html;
	}
	
	
	/**
	 * Save callback
	 */
	public function update($new_instance, $old_instance){
		
		$instance = array();
		
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		$instance['resource_id'] = isset($new_instance['resource_id']) ? sanitize_text_field($new_instance['resource_id']) : '';
		$instance['background_id'] = isset($new_instance['background_id']) ? sanitize_text_field($new_instance['background_id']) : '';
		$instance['background_colour'] = isset($new_instance['background_colour']) ? sanitize_text_field($new_instance['background_colour']) : '';
		$instance['text_colour'] = isset($new_instance['text_colour']) ? sanitize_text_field($new_instance['text_colour']) : '';
		$instance['link_url'] = isset($new_instance['link_url']) ? sanitize_text_field($new_instance['link_url']) : '';
	
	
		return $instance;
		
	}
	
 }



 //TODO: LOOK INTO THIS FURTHER, CANT USE TINYMCE EDITOR HERE FOR SOME REASON
 /**
 * Basic Wysiwyg editor in a widget
 * 
 * Used to provide an editor widget to be used in the sidebar
 */
 
 class el_editor_widget extends WP_Widget{
 	
	public function __construct(){
			
		$args = array(
			'description'	=> 'Creates a simple editor widget to let you add HTML / Content into widget areas'
		);
		
		parent::__construct(
			'el_editor_widget', esc_html__('Basic WYSIWYG Widget', 'ycc'), $args
		);
		
	}

	
	/**
	 * Visual output frontend
	 */
	public function widget($args, $instance){
		
		$title = isset($instance['title']) ? $instance['title'] : '';
		$editor = isset($instance['editor']) ? $instance['editor'] : '';
		
		$html = '';
		
		$html .= $args['before_widget'];
				
			$html .= '<div class="widget-wrap el-col-small-12 small-padding-top-bottom-small">';
			
				//title if supplied
				if(isset($instance['title'])){
					$html .= $args['before_title'];
						$html .= $title;
					$html .= $args['after_title'];	
				}
		
				//main content
				$html .= '<div class="widget-content ">';
					$html .= apply_filters('the_content', $editor);
				$html .= '</div>';
					
			
			$html .= '</div>';
		
		$html .= $args['after_widget'];
		
		
		echo $html;
		
		
	}
	
	/**
	 * Form output on admin
	 * 
	 * TODO: Come back and clean this up, experimental
	 * @link https://codex.wordpress.org/TinyMCE
	 * @link http://wordpress.stackexchange.com/questions/82670/why-cant-wp-editor-be-used-in-a-custom-widget
	 * @link http://wordpress.stackexchange.com/questions/227165/wp-editor-in-widget-breaks-after-save-no-buttons-and-visual-tab-broken
	 */
	public function form($instance){
		
		//enqueue media scripts
		wp_enqueue_media();	

		$title = isset($instance['title']) ? $instance['title'] : '';
		$editor = isset($instance['editor']) ? $instance['editor'] : '';
		
		$html = '';
		
		
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		

		//Editor
		$html .= '<div>';
		
			//add a trigger to the TinyMCE element to ensure editor is saved when editing terms
			//http://wordpress.stackexchange.com/questions/39594/wp-editor-textarea-value-not-updating
			
			
			$widget_id = $this->id;
			
			//Name of this widget instance
			$random = rand( 0, 999 );
			$id = $this->get_field_id('editor_' . $random);
			$name = $this->get_field_name('editor_' . $random);
			
			//Handle the re-init process for tiny MCE after save,
			//Handle saving of tiny MCE field when about to save (else the value never gets updated)
			$html .= '<script type="text/javascript">';
			$html .= 'jQuery(document).ready(function($){
							
						options = {
							selector: "textarea[id*=' . $id .']",
							height: 400,
            				theme: "modern",
            				plugins: "tabfocus,paste,media,wordpress,wpeditimage,wpgallery,wplink,wpdialogs",
            				toolbar1: "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv",
            				toolbar2: "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help"
						};
						
						//tinyMCE.init(options);
						
						//console.log("#' . $id . '");
						
						//save when hovering over the submit button
						$("#widget-' . $widget_id . '-savewidget").on("hover", function(){
							tinyMCE.triggerSave();
							console.log("Hover save");
						});
						
						//on update, set the widget area back up again
						$(document).on("widget-updated", function (event, $widget) {
							console.log("Updasted widget");
							tinyMCE.remove();
							tinyMCE.init(options);
						});

					  });';
			$html .= '</script>';
			

		
			$html .= '<label for="' . $id  . '">' . __('Content', 'ycc') . '</label>';
			$html .= '<input type="hidden" id="' . $this->get_field_id('editor_number') .'" name="' . $this->get_field_name('editor_number'). '" value="' . $random .'" />'; 
			ob_start();
			$editor_args = array(
				'textarea_name'		=> $name,
				'textarea_rows'		=> 10,
				'teeny'				=> true
			);
			
			wp_editor($editor, $id, $editor_args);
			$markup = ob_get_clean();
			
			$html .= $markup;
		
		$html .= '</div>';
		
		echo $html;
	}
	
	/**
	 * Save callback
	 */
	public function update($new_instance, $old_instance){
		
		$instance = array();
			
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		$instance['editor_number'] = isset($new_instance['editor_number']) ? $new_instance['editor_number'] : '';
		$field_name = 'editor_' . $instance['editor_number'];
		$instance['editor'] = isset($new_instance[$field_name]) ? $new_instance[$field_name] : '';
		

		
		return $instance;
		
	}
	
 }







 /**
  * Contact Us Widget
  * 
  * Provides an interactive widget to show to the user phone number, fax, email, address and trading hours
  * pulled in from the theme customizer
  */
 class el_contact_us_widget extends WP_widget{
 	
	 /**
	 * Constrcutor 
	 */
	public function __construct(){
		
		$args = array(
			'description'	=> 'Displays a contact widget that shows various contact elements such as number, fax, email, address etc'
		);
		
		parent::__construct(
			'el_contact_us_widget', esc_html__('Contact Widget', 'ycc'), $args
		);
		
	}

	
	
	/**
	 * Visual output frontend
	 */
	public function widget($args, $instance){
		
		$eq_faq = el_faq::getInstance();
		
		$html = '';
		
		$html .= $args['before_widget'];
		
			$html .= '<div class="widget-wrap">';
				
				$title = isset($instance['title']) ? $instance['title'] : '';
				$contact_options = isset($instance['contact_options']) ? $instance['contact_options'] : '';
				if(!empty($contact_options)){
					$contact_options = json_decode($contact_options);
				}
				
				//title if supplied
				if(isset($instance['title'])){
					$html .= $args['before_title'];
						$html .= $title;
					$html .= $args['after_title'];	
				}
				
				//Customizer settings
				$el_phone = get_theme_mod('el_phone');
				$el_fax = get_theme_mod('el_fax');
				$el_email = get_theme_mod('el_email');
				$el_address = get_theme_mod('el_address');
				
				
				//main content
				$html .= '<div class="widget-content">';
					$html .= '<div class="widget-wrap">';	
				
					//Display Phone
					if(is_array($contact_options) && in_array('phone', $contact_options)){
						$html .= '<div class="element el-row nested phone small-margin-bottom-medium">';
							$html .= '<div class="el-col-small-6 el-col-medium-3 el-col-large-2 image">';
								$html .= '<img src="' . get_stylesheet_directory_uri() . '/img/icon-phone.png"/>';
							$html .= '</div>';
							
							$html .= '<div class="el-col-small-6 el-col-medium-9 el-col-large-10 content">';
								$html .= '<h4 class="small-margin-bottom-none">' . __('24 Hour Phone', 'ycc') . '</h4>';
								$html .= '<a class="h4 black" href="tel:' . trim($el_phone) . '">' . $el_phone . '</a>';
								
							$html .= '</div>';	
						$html .= '</div>';
					}
					//Display Fax
					if(is_array($contact_options) && in_array('fax', $contact_options)){
						$html .= '<div class="element el-row nested fax small-margin-bottom-medium">';
							$html .= '<div class="el-col-small-6 el-col-medium-3 el-col-large-2 image">';
								$html .= '<img src="' . get_stylesheet_directory_uri() . '/img/icon-fax.png"/>';
							$html .= '</div>';
							
							$html .= '<div class="el-col-small-6 el-col-medium-9 el-col-large-10 content">';
								$html .= '<h4 class="small-margin-bottom-none">' . __('24 Hour Fax', 'ycc') . '</h4>';
								$html .= '<div class="h4 black">' . $el_fax . '</div>';
								
							$html .= '</div>';	
						$html .= '</div>';
					}
					//Email
					if(is_array($contact_options) && in_array('email', $contact_options)){
						$html .= '<div class="element el-row nested email small-margin-bottom-medium">';
							$html .= '<div class="el-col-small-6 el-col-medium-3 el-col-large-2 image">';
								$html .= '<img src="' . get_stylesheet_directory_uri() . '/img/icon-mail.png"/>';
							$html .= '</div>';
							
							$html .= '<div class="el-col-small-6 el-col-medium-9 el-col-large-10 content">';
								$html .= '<h4 class="small-margin-bottom-none">' . __('Email', 'ycc') . '</h4>';
								$html .= '<a class="black" href="mailto:' . $el_email . '">' . $el_email . '</a>';
								
							$html .= '</div>';	
						$html .= '</div>';
					}
					//Address
					if(is_array($contact_options) && in_array('address', $contact_options)){
						$html .= '<div class="element el-row nested address small-margin-bottom-medium">';
							$html .= '<div class="el-col-small-6 el-col-medium-3 el-col-large-2 image">';
								$html .= '<img src="' . get_stylesheet_directory_uri() . '/img/icon-location.png"/>';
							$html .= '</div>';
							
							$html .= '<div class="el-col-small-6 el-col-medium-9 el-col-large-10 content">';
								$html .= '<h4 class="small-margin-bottom-none">' . __('Address', 'ycc') . '</h4>';
								$html .= '<div>' . $el_address . '</div>';
								
							$html .= '</div>';	
						$html .= '</div>';
					}
					//Opening Hours
					if(is_array($contact_options) && in_array('hours', $contact_options)){
						$html .= '<div class="element el-row nested hours small-margin-bottom-medium">';
							$html .= '<div class="el-col-small-6 el-col-medium-3 el-col-large-2 image">';
								$html .= '<img src="' . get_stylesheet_directory_uri() . '/img/icon-clock.png"/>';
							$html .= '</div>';
							
							$html .= '<div class="el-col-small-6 el-col-medium-9 el-col-large-10 content">';
								$html .= '<h4 class="small-margin-bottom-none">' . __('Trading Hours', 'ycc') . '</h4>';
								
								$el_trading_hours_monday_friday = get_theme_mod('el_trading_hours_monday_friday');
								$el_trading_hours_saturday = get_theme_mod('el_trading_hours_saturday');
								$el_trading_hours_holidays = get_theme_mod('el_trading_hours_holidays');
								
								if(!empty($el_trading_hours_monday_friday)){
									$html .= '<div>';
									$html .= __('Monay - Friday: ', 'ycc') . $el_trading_hours_monday_friday;
									$html .= '</div>';
								}
								if(!empty($el_trading_hours_saturday)){
									$html .= '<div>';
									$html .= __('Saturday: ', 'ycc') . $el_trading_hours_saturday;
									$html .= '</div>';
								}
								if(!empty($el_trading_hours_holidays)){
									$html .= '<div>';
									$html .= __('Public Holidays / Sunday: ', 'ycc') . $el_trading_hours_holidays;
									$html .= '</div>';
								}

							$html .= '</div>';	
						$html .= '</div>';
					}
					
				
					$html .= '</div>';
				$html .= '</div>';
				
			$html .= '</div>';
			
		$html .= $args['after_widget'];
		
		
		echo $html;
		
		
	}
	
	/**
	 * Form output on admin
	 */
	public function form($instance){
			
		$title = isset($instance['title']) ? $instance['title'] : '';
		$contact_options = isset($instance['contact_options']) ? $instance['contact_options'] : '';
		
		if(!empty($contact_options)){
			$contact_options = json_decode($contact_options);
		}
		
		
		$html = '';
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		
		//Select which elemnents to displauyw
		$html .= '<p>';
			$html .= '<label>' . __('Which contact elements do you want displayed?', 'ycc') .'</label><br/>';
			
			//Various contact options
			
			//PHONE
			$html .= '<p>';
				if(is_array($contact_options) && in_array('phone',$contact_options)){				
					$html .= '<input checked type="checkbox" id="' . $this->get_field_id('contact_options') .'-phone" name="' . $this->get_field_name('contact_options[]') . '" value="phone"/>';
				}else{
					$html .= '<input type="checkbox" id="' . $this->get_field_id('contact_options') .'-phone" name="' . $this->get_field_name('contact_options[]') . '" value="phone"/>';
				}
				$html .= '<label for="' . $this->get_field_id('contact_options') . '-phone">Show Phone</label>';	
			$html .= '</p>';
				
			//FAX
			$html .= '<p>';
				if(is_array($contact_options) && in_array('fax',$contact_options)){				
					$html .= '<input checked type="checkbox" id="' . $this->get_field_id('contact_options') .'-fax" name="' . $this->get_field_name('contact_options[]') . '" value="fax"/>';
				}else{
					$html .= '<input type="checkbox" id="' . $this->get_field_id('contact_options') .'-fax" name="' . $this->get_field_name('contact_options[]') . '" value="fax"/>';
				}
				$html .= '<label for="' . $this->get_field_id('contact_options') . '-fax">Show Fax</label>';		
			$html .= '</p>';
			
			//Email
			$html .= '<p>';
				if(is_array($contact_options) && in_array('email',$contact_options)){				
					$html .= '<input checked type="checkbox" id="' . $this->get_field_id('contact_options') .'-email" name="' . $this->get_field_name('contact_options[]') . '" value="email"/>';
				}else{
					$html .= '<input type="checkbox" id="' . $this->get_field_id('contact_options') .'-email" name="' . $this->get_field_name('contact_options[]') . '" value="email"/>';
				}
				$html .= '<label for="' . $this->get_field_id('contact_options') . '-email">Show Email</label>';		
			$html .= '</p>';
				
			//Address
			$html .= '<p>';
				if(is_array($contact_options) && in_array('address',$contact_options)){				
					$html .= '<input checked type="checkbox" id="' . $this->get_field_id('contact_options') .'-address" name="' . $this->get_field_name('contact_options[]') . '" value="address"/>';
				}else{
					$html .= '<input type="checkbox" id="' . $this->get_field_id('contact_options') .'-address" name="' . $this->get_field_name('contact_options[]') . '" value="address"/>';
				}
				$html .= '<label for="' . $this->get_field_id('contact_options') . '-address">Show Address</label>';		
			$html .= '</p>';
			
			//Opening Hours
			$html .= '<p>';
				if(is_array($contact_options) && in_array('hours',$contact_options)){				
					$html .= '<input checked type="checkbox" id="' . $this->get_field_id('contact_options') .'-hours" name="' . $this->get_field_name('contact_options[]') . '" value="hours"/>';
				}else{
					$html .= '<input type="checkbox" id="' . $this->get_field_id('contact_options') .'-hours" name="' . $this->get_field_name('contact_options[]') . '" value="hours"/>';
				}
				$html .= '<label for="' . $this->get_field_id('contact_options') . '-hours">Show Opening Hours</label>';		
			$html .= '</p>';
			
		$html .= '</p>';
		
		
		echo $html;
	}
	
	/**
	 * Save callback
	 */
	public function update($new_instance, $old_instance){
		
		$instance = array();
		
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		
		if(isset($new_instance['contact_options'])){
			$values = json_encode($new_instance['contact_options']);
		}else{
			$values = '';
		}
		$instance['contact_options'] = $values;
	
		
		return $instance;
		
	}
	
 }
 
 
 
 
 
if ( ! function_exists( 'ycc_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ycc_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ycc, use a find and replace
	 * to change 'ycc' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ycc', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'right-menu' 	=> esc_html__( 'Right Main Menu', 'ycc' ),
		'left-menu' 	=> esc_html__('Left Main Menu', 'ycc'),
		'mobile-menu' 	=> esc_html__('Mobile Menu', 'ycc')
	) );
	
	

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ycc_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'ycc_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ycc_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ycc_content_width', 640 );
}
add_action( 'after_setup_theme', 'ycc_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function ycc_scripts() {
	wp_enqueue_style( 'ycc-style', get_stylesheet_uri() );

	wp_enqueue_script( 'ycc-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'ycc-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ycc_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';