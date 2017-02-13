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
  
 class theme_base{
 	
	private static $instance = null;
	private $number_of_footer_widgets = 6;
	
	/**
	 * Main Constrcutor
	 */
	public function __construct(){
		
		$this->load_customizer_functionality();
		$this->load_el_content_type_class();
		$this->load_services_content_type();
		$this->load_faq_content_type();
		$this->load_conditions_content_type();
		$this->load_locations_content_type();
	
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles'));
		add_action('widgets_init', array($this, 'register_widget_areas')); //registeres widget zones / sidebars
		add_action('widgets_init', array($this, 'register_widgets')); //new widgets for use in theme
		add_action('init', array($this, 'remove_content_from_post_types')); //removes the content block from some post types

			
		//Custom action hooks (used in theme templates)
		add_action('el_display_footer_widgets', array($this, 'el_display_footer_widgets'));
		add_action('el_display_theme_logo', array($this, 'el_display_theme_logo'));
		add_action('el_display_commitment_program_cta', array($this, 'el_display_commitment_program_cta'), 10, 1); 
		add_action('el_display_appointment_cta', array($this, 'el_display_appointment_cta'), 10, 1);
		
		add_action('el_display_header_banner', array($this, 'el_display_header_banner'));
		add_action('el_display_post_card_listing', array($this, 'display_post_card_listing')); 
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
	 * Displays a listing of the latest blogs in card format for template in templates
	 */
	public function display_post_card_listing(){
		
		$html = '';
		
		$html .= '<div class="blog-card-listing el-row inner medium-row-of-three small-padding-top-bottom-medium medium-padding-top-bottom-large">';
			$html .= $this->get_post_card_listing();
		$html .= '</div>';
		echo $html;
	}
	
	/**
	 * Gets a listing of a 3x2 grid of post cards
	 * 
	 */
	public function get_post_card_listing(){
		
		$html = '';
		
		$post_args = array(
			'post_type'		=> 'post',
			'post_status'	=> 'publish',
			'posts_per_page'=> 6
		);
		$posts = get_posts($post_args);
		if($posts){
			foreach($posts as $post){
				$html .= $this->get_single_post_card_html($post->ID);
			}
		}
		
		return $html;
	}

	/**
	 * gets the single HTMLL markup for a single post in our card format
	 */
	public static function get_single_post_card_html($post_id){
		
		
		
		$instance = self::getInstance();
	
		$html = '';
	
		$post = get_post($post_id);
		if($post){
			$post_id = $post->ID;
			$post_title = $post->post_title; 
			$post_content = $post->post_content;
			$post_excerpt = $post->post_excerpt;
			$post_permalink = get_permalink($post_id);
			$post_url = get_permalink($post_id);
			$post_thumbnail_id = get_post_thumbnail_id($post_id);
			$post_date = get_the_date('M d, Y', $post_id);
			$post_author_id = $post->post_author;
			$post_author = get_user_by('ID', $post_author_id);
			
			//echo '<pre>';
			//var_dump($post_author);
			//echo '</pre>';
			
			
			$post_description = '';
			if(!empty($post_excerpt)){
				$post_description = $post_excerpt;
			}else{
				if(!empty($post_content)){
					$post_description = wp_trim_words($post_content, 25, '..');
				}
			}
			
			//Output
			$html .= '<article class="blog-card el-col-small-12 el-col-medium-6 el-col-large-4">';
				
				
				$html .= '<div class="hero-card outer-wrap small-margin-bottom-medium">';
					//Image section + title
					$html .= '<div class="header-wrap small-margin-bottom-medium">';
						$html .= '<a href="' . $post_url .'" title="' . __($post_title,'perfectvision')  . '">';
							//Image
							if(!empty($post_thumbnail_id)){
								$url = wp_get_attachment_image_src($post_thumbnail_id, 'medium', false)[0];
								$html .= '<div class="image" style="background-image:url(' . $url . ');"></div>';
								$html .= '<div class="image-overlay"></div>';
							}
							//title
							if(!empty($post_title)){
								$html .= '<h2 class="title">' . $post_title . '</h2>';
							}	
							
							//posted date
							$html .= '<div class="posted-on">';
								$html .= $post_date;
							$html .= '</div>';
							
						$html .= '</a>';
						
						
					$html .= '</div>';
					
					//main content
					$html .= '<div class="content-wrap small-margin-bottom-medium">';
					if(!empty($post_description)){
						$html .= '<div class="content">' . $post_description . '</div>';
					}
					$html .= '</div>';
					
					//additionals
					$html .= '<div class="footer-wrap el-row small-align-center">';
					
						//author link
						$html .= '<div class="author el-col-small-12 el-col-medium-6 collapse">';
							$html .= '<a class="small-padding-top-bottom-small" href="' . get_author_posts_url($post_author_id) . '">';
								$html .= '<span class="name">' . $post_author->display_name . '</span>';
							$html .= '</a>';
						$html .= '</div>';
						
						//post link
						$html .= '<div class="post-link el-col-small-12 el-col-medium-6 collapse">';
							$html .= '<a class="small-padding-top-bottom-small" href="' . $post_permalink . '">Read More</a>';
						$html .= '</div>';
						
					$html .= '</div>';
				$html .= '</div>';
				
				//edit link for admins
				if(current_user_can('edit_posts')){
					$url = get_edit_post_link($post_id); 
					$html .= '<div class="el-row small-align-center small-margin-top-bottom-small">';
						$html .= '<a class="button primary-button small" href="' . $url .'" title="edit">Edit</a>';
					$html .= '</div>';
				}
				
				
			$html .= '</article>';
		}
	
	
		return $html;
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
		
		$post_types = array('post','page','conditions','services');
		
		foreach($post_types as $post_type){
			remove_post_type_support($post_type, 'editor');
		}
		
		
		
	}
	
	//TODO: Hook this in to colorpicker
	/**
	 * Localises scripts, gives our JS scripts access to variables
	 * - Used to provide admin colourpicker with theme colours
	 */
	public function localize_scripts(){
		
		//Add our colours to our admin scripts (so they can be used in colour pickers)
		$theme_colours = array(
			'green_light' 	=> '#73E1C7',
			'green_dark' 	=> '#0D7B61',
			'green_regular'	=> '#40ae94',
			'orange_light'	=> '#FFCB66',
			'orange_dark'	=> '#CC6500',
			'orange_regular'=> '#ff9833',
			'gray_extra_light' => '#eeeeee',
			'gray_light'	=> 'f7f7f7',
			'gray_regular'	=> '#333333',
			'black'			=> '#000000',
			'white'			=> '#ffffff'
		);
		wp_localize_script('theme-admin-script', 'el_theme_colours', $theme_colours);
	}
	
	/**
	 * register generic widgets for the theme, loading the required modules 
	 * - Image widget (used for displaying a single image)
	 */
	public function register_widgets(){
		
		require(get_stylesheet_directory() . '/inc/widgets/el_image_widget.php');
		register_widget('el_image_widget');
	
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
	 * Loads the conditions content type
	 */
	public function load_conditions_content_type(){
		require get_template_directory() . '/inc/conditions/el_conditions.php';
		$el_conditions = $this->get_conditions_object();
		
	}
	
	/**
	 * Loads the locations content type
	 */
	public function load_locations_content_type(){
		require get_template_directory() . '/inc/locations/el_locations.php';
		$el_locations = $this->get_locations_object();
	}
	
	/**
	 * Loads the product content type for use
	 * 
	 * Loads the products file located in '/inc/services/el_services.php'
	 */
	public function load_services_content_type(){
		require get_template_directory() . '/inc/services/el_services.php';
		$el_services = $this->get_services_object();
		
	}
	
	/**
	 * Loads the customizer functionality for the theme
	 */
	public function load_customizer_functionality(){
		require get_template_directory() . '/inc/customizer/customizer.php';
	}
	
	
	/**
	 * Gets the 'el_services' object instance 
	 * 
	 * Used for when we need to get the services object for manipulation so we don't hard code 
	 * it's value in templates 
	 */
	public function get_services_object(){
			
		$result = false;
		
		if(class_exists('el_services')){
			$result = el_services::getInstance();
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
	 * Gets the 'el_conditions' object instance
	 * 
	 * Used to access specific functions inside that class
	 */
	public function get_conditions_object(){
		$result = false;
		if(class_exists('el_conditions')){
			$result = el_conditions::getInstance();
		}
		return $result;
	}
	
	/**
	 * Gets the 'el_locations' object instance
	 * 
	 * Accessed specific functions inside that object
	 */
	public function get_locations_object(){
		$result = false;
		if(class_exists('el_locations')){
			$result = el_locations::getInstance();
		}
		return $result;
	}

	/**
	 * Loads public facing CSS/JS
	 */
	public function enqueue_public_scripts_and_styles(){
		$dir = get_stylesheet_directory_uri();
		wp_enqueue_style('theme-fonts', '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
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
	 * shared across post types (so we have this one function to render them.
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
	 * 
	 * Output is determined by the type of section chosen. 
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
				
		
				
				//Card Hero Content Section
				//Displays a hero card in the middle of the section
				if($layout == 'card_hero_content_section'){
					
					$card_layout_style = get_sub_field('card_layout_style');
					$card_title = get_sub_field('card_title');
					$card_subtitle = get_sub_field('card_subtitle');
					$card_readmore = get_sub_field('card_readmore');
					$card_background_image = get_sub_field('card_background_image');
					$section_background_image = get_sub_field('section_background_image');
					$section_background_colour = get_sub_field('section_background_colour');
					
					$style = '';
					$style .= !empty($section_background_colour) ? 'background-color: ' . $section_background_colour . ';' : '';
					//output
					$html .= '<section style="' . $style . '" class="el-row content-section ' . $layout . ' small-padding-top-bottom-medium medium-padding-top-xx-large medium-padding-bottom-large">';
						
						//background image
						if(!empty($section_background_image)){
							$html .= '<div class="section-background-image top-right">';
								$url = wp_get_attachment_image_src($section_background_image, 'medium', false)[0]; 
								$html .= '<div class="image" style="background-image:url(' . $url .');"></div>';
							$html .= '</div>';
						}
						
						$html .= '<div class="el-row inner">';
						
							$html .= '<div class="hero-card hero-icon el-col-small-12 el-col-medium-8 el-col-medium-offset-2">';
								
								//icon
								$html .= '<div class="icon"></div>';
								
								
								//background image
								if(!empty($card_background_image)){
									$url = wp_get_attachment_image_src($card_background_image, 'medium', false)[0];
									$html .= '<div class="background-image">';
										$html .= '<div class="image" style="background-image:url(' . $url .');"></div>';
									$html .= '</div>';
								}
	
								//content
								$html .= '<div class="content-wrap collapse el-col-small-12 el-col-medium-8">';
									$html .= '<h2 class="title">' . $card_title . '</h2>';
									if(!empty($card_subtitle)){
										$html .= '<div class="subtitle">';
											$html .= $card_subtitle;
										$html .= '</div>';
									}
									if(!empty($card_readmore)){
										$html .= '<a href="' . get_permalink($card_readmore) . '" title="' . __('Read more', 'perfectvision') . '">';
											$html .= '<div class="button primary-button">' . __('View More', 'perfectvision') . '</div>';
										$html .= '</a>';
									}
								$html .= '</div>';
								
								
							$html .= '</div>';	
						$html .= '</div>';
					$html .= '</section>';
					
				}

				//Card hero grid
				//A single hero card with multiple sub-cards inside, displayed in a 2 column fashion 
				else if($layout == 'card_hero_nested_grid'){
										
					//get section background colour
					$section_background_colour = get_sub_field('section_background_colour');
		
					//We use a repeater field here for each of the cards
					if( have_rows('cards')){
						
						$style = '';
						$style .= (!empty($section_background_colour)) ? 'background-color: ' . $section_background_colour . ';' : '';
						
						$html .= '<section style="' . $style . '" class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-large content-section ' . $layout .'">';
							
							$html .= '<div class="el-row inner medium-row-of-two large-row-of-three ">';
								
								$html .= '<div class="hero-card el-row">';
								
									//output each card
									while ( have_rows('cards')){
										
										$row = the_row();
										
										$card_image = get_sub_field('card_image');
										$card_tile = get_sub_field('card_tile');
										$card_content = get_sub_field('card_content');
										$card_background_colour = get_sub_field('card_background_colour');
							
										
										//each column
										$html .= '<div class="card el-col-small-12 el-col-large-6 small-margin-bottom-medium">';
											
											$style = '';
											$style .= (!empty($card_background_colour)) ? 'background-color: ' . $card_background_colour . ';' : '';
											$html .= '<div  style="' . $style . '" class="card-iner el-row small-padding-top-bottom-small">';
												//main content
												$html .= '<div class="content-wrap el-col-small-12 el-col-medium-8 small-align-center medium-align-left">';
													if(!empty($card_tile)){
														$html .= '<h3 class="title capitalize white">' . $card_tile . '</h3>';
													}
													if(!empty($card_content)){
														$html .= '<div class="content">' . $card_content . '</div>';
													}
												$html .= '</div>';
												
												//image
												if(!empty($card_image)){
													$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-center medium-align-right">';
														$url = wp_get_attachment_image_src($card_image, 'medium', false)[0];
														$html .= '<img class="image" src="' . $url . '"/>';
													$html .= '</div>';
												}
											$html .= '</div>';
										$html .= '</div>';
										
										
										
									}
								$html .= '</div>';
							$html .= '</div>';
						$html .= '</div>';
						
					}
				}

				
				//Two column section
				//Displays a left and right content area with optional title, readmore, background image and colour
				else if($layout == 'two_column_section'){
					
					$title = get_sub_field('title');
					$content = get_sub_field('content');
					$read_more_url = get_sub_field('read_more_url');
					$section_background_colour = get_sub_field('section_background_colour');
					$section_background_image = get_sub_field('section_background_image');
								
					$style = '';
					$style .= !empty($section_background_colour) ? 'background-color: ' . $section_background_colour . ';' : '';
					$html .= '<section style="' . $style .'" class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-large  content-section ' . $layout .'">';
						
						//background image
						if(!empty($section_background_image)){
							$html .= '<div class="section-background-image top-right">';
								$url = wp_get_attachment_image_src($section_background_image, 'medium', false)[0]; 
								$html .= '<div class="image" style="background-image:url(' . $url .');"></div>';
							$html .= '</div>';
						}
						
						$html .= '<div class="el-row inner relative">';
						
							$html .= '<div class="content-wrap el-col-small-12">';
							
								if(!empty($title)){
									//adjust title based on which section it's used in	
									$html .= '<h2 class="title small-align-center">' . $title . '</h2>';
								}	
								if(!empty($content)){
									$html .= '<div class="content">';
										$html .= $content;
									$html .= '</div>';
								}

								if(!empty($read_more_url)){
									$html .= '<div class="small-align-center small-margin-top-medium">';
										$html .= '<a href="' . get_permalink($read_more_url) . '" title="' . __('Read more', 'perfectvision') . '">';
											$html .= '<div class="button primary-button">' . __('View More', 'perfectvision') . '</div>';
										$html .= '</a>';
									$html .= '</div>';
								}
								
							$html .= '</div>';

						$html .= '</div>';
						
					$html .= '</section>';
	
				}
				
				//Three column section
				//Used to create a three column grid of center aligned elements (title, subtitle, image)
				else if($layout == 'three_column_section'){
					
					$title = get_sub_field('title');
					$subtitle = get_sub_field('subtitle');
					$section_background_colour = get_sub_field('section_background_colour');
					
	
					//We use a repeater field here for each of the columns
					if( have_rows('columns')){
						
						$style = '';
						$style .= (!empty($section_background_colour)) ? 'background-color: ' . $section_background_colour .';' : '';
						$html .= '<section style="' . $style . '" class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-large content-section ' . $layout .'">';
							
							//display subtitle and title if we have it set
							if(!empty($title) || !empty($subtitle)){
								$html .= '<div class="el-row inner small-align-center small-margin-bottom-medium">';
								if(!empty($title)){
									$html .= '<h2 class="title">' . $title . '</h2>';
								}
								if(!empty($subtitle)){
									$html .= '<div class="content">' . $subtitle . '</div>';
								}
								$html .= '</div>';
							}
							$html .= '<div class="el-row inner medium-row-of-two large-row-of-three ">';
								while ( have_rows('columns')){
									
									$row = the_row();
									
									$image = get_sub_field('image');
									$title = get_sub_field('title');
									$content = get_sub_field('content');
						
									
									//each column
									$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-large-4 small-align-center small-margin-bottom-medium">';
										if(!empty($image)){
											$url = wp_get_attachment_image_src($image, 'medium', false)[0]; 
											$html .= '<img class="image small-margin-bottom-small" src="' . $url  .'"/>';
										}
										if(!empty($title)){
											$html .= '<h3 class="title capitalize">' . $title . '</h3>';
										}
										if(!empty($content)){
											$html .= '<div class="content">' . $content . '</div>';
										}
									$html .= '</div>';
									
								}
							$html .= '</div>';
						$html .= '</div>';
						
					}	
				}
								
				
				
				
				//full content section
				//Used to display a full WYSIWYG editor 
				else if($layout == 'full_content_section'){
					
					$content = get_sub_field('content');
				
					$html .= '<section class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-x-large  content-section ' . $layout .'">';
						$html .= '<div class="el-row inner">';
						if(!empty($content)){
							$html .= '<div class="el-col-small-12 small-padding-top-bottom-small content-section' . $layout . '">';
								$html .= $content;
							$html .= '</div>';
						}
						$html .= '</div>';
					$html .= '</section>';
					
				}
				///Inner content section
				//Displays a title and WYSIWYG editor, but constrains it to the inner container with an optional background colour
				else if($layout == 'inner_content_section'){
					
					$title = get_sub_field('title');
					$content = get_sub_field('content');
					$section_background_colour = get_sub_field('section_background_colour');
					
					
					$html .= '<section class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-x-large  content-section ' . $layout .'">';
						$html .= '<div class="el-row inner">';
						
							$style = '';
							$style .= (!empty($section_background_colour)) ? 'background-color: ' . $section_background_colour . ';' : '';
							$html .= '<div style="' . $style . '" class="content-wrap el-col-small-12 small-padding-top-bottom-small">';
								if(!empty($title)){
									$html .= '<h3 class="title">' . $title . '</h3>';
								}
								if(!empty($content)){
									$html .= '<div class="content">';
										$html .= $content;
									$html .= '</div>';
								}
							
							$html .= '</div>';
						
						$html .= '</div>';
					$html .= '</section>';
					
				}
							
				//Standard CTA Section
				//Used to create a center aligned CTA element with optional 2 readmore buttons
				else if($layout == 'standard_cta_section'){
					
					$title = get_sub_field('title');
					$content = get_sub_field('content');
					$use_large_text = get_sub_field('use_large_text');
					$button_primary_text = get_sub_field('button_primary_text');
					$button_primary_url = get_sub_field('button_primary_url');
					$button_secondary_text = get_sub_field('button_secondary_text');
					$button_secondary_url = get_sub_field('button_secondary_url');
					$section_background_colour = get_sub_field('section_background_colour');
					$section_background_image = get_sub_field('section_background_image');
					
					
					$style = '';
					$style .= !empty($section_background_colour) ? 'background-color: ' . $section_background_colour . '; ' : '';
					$html .= '<section style="' . $style . '" class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-x-large content-section ' . $layout . '">';
						
					
						//background image
						if(!empty($section_background_image)){
							$html .= '<div class="section-background-image top-left">';
								$url = wp_get_attachment_image_src($section_background_image, 'medium', false)[0]; 
								$html .= '<div class="image" style="background-image:url(' . $url .');"></div>';
							$html .= '</div>';
						}
						
						$html .= '<div class="el-row inner relative">';
							
							$html .= '<div class="content-wrap el-col-small-12">';
								
								if(!empty($title)){
									$html .= '<h2 class="title small-align-center">' . $title . '</h2>';
								}
								if(!empty($content)){
									$classes = '';
									$classes .= ($use_large_text == 'yes') ? 'large-text' : '';
									$html .= '<div class="content small-align-center ' . $classes .'">' . $content . '</div>';
								}


								//optional buttons
								if(!empty($button_primary_url) || !empty($button_secondary_url)){
										
									$html .= '<div class="button-group small-align-center">';	
										//primary button
										if(!empty($button_primary_url) && !empty($button_primary_text)){
											$html .= '<a href="' . get_permalink($button_primary_url) . '">';
												$html .= '<div class="button featured-button">' . $button_primary_text . '</div>';
											$html .= '</a>';
										}
										//secondary button
										if(!empty($button_secondary_url) && !empty($button_secondary_text)){					
											$html .= '<a href="' . get_permalink($button_secondary_url) . '">';
												$html .= '<div class="button secondary-button">' . $button_secondary_text . '</div>';
											$html .= '</a>';
										}
									$html .= '</div>';
								}
								
								

							
							$html .= '</div>';
							
			
							
							
						$html .= '</div>';
					$html .= '</section>';
					
					
				}
				//Video Section
				//Used to display an embedded video source such as youtube
				else if($layout == 'video_section'){
					
					$iframe_embed = get_sub_field('iframe_embed');
					
					$html .= '<section class="el-row small-padding-top-bottom-medium medium-padding-top-bottom-x-large  content-section ' . $layout .'">';
						$html .= '<div class="el-row inner">';
							$html .= '<div class="video-wrap el-col-small-12 small-aspect-16-9">';
							if(!empty($iframe_embed)){
								$html .= $iframe_embed;
							}
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</section>';
					
				}
				//Service listing
				//Shows a grid listing of our Services content type
				else if($layout == 'service_listing'){
					
					//use our el_services object to get the markup we need
					//inc/services/el_services.php
					$args = array('style_type' => 'grid');
					
					$el_services = $this->get_services_object();
					$html .= $el_services->get_servicess_listing($args);
					
				}
				//Condition Listing
				//Shows a grid listing of the Conditions content type
				else if($layout == 'condition_listing'){
					
					$args = array('style_type' => 'grid');
					$el_conditions = $this->get_conditions_object();
					
					$html .= $el_conditions->get_conditions_listing($args);
				}
				//Blog Listing
				//Shows a grid of upcoming blog articles
				else if($layout == 'blog_listing'){
					
					$html .= '<section class="blog-card-listing el-row inner medium-row-of-two large-row-of-three small-padding-top-bottom-medium medium-padding-top-bottom-large">';
						$html .= $this->get_post_card_listing();
					$html .= '</section>';
					
				}
				//Location Listing
				//shows a listing of locations
				else if($layout == 'locations_listing'){
					
					$args = array('style_type' => 'grid');
					
					//collect args from template
					$locations_to_display = get_sub_field('locations_to_display');
					if(!empty($locations_to_display)){
						$args['include'] = $locations_to_display;
					}
					
					
					
					$el_locations = $this->get_locations_object();
					$html .= $el_locations->get_locations_listing($args); 
					
				}
				
				//faq_listing
				else if($layout == 'faq_listing'){
					
					//use our el_faq object to get our markup
					//inc/faq/faq.php
					$el_faq = $this->get_faq_object();
					$html .= $el_faq->get_faq_html();
				}
				
				
				
				// //Full width image section
				else if($layout == 'full_width_image_section'){
						
					$image_id = get_sub_field('image'); 

					if(!empty($image_id)){
						$image = wp_get_attachment_image($image_id, 'large', false);
						
						$html .= '<section class="el-row inner small-margin-bottom-medium small-align-center content-section ' . $layout .' ">';
							$html .= '<div class="el-col-small-12">';
								$html .= $image;
							$html .= '</div>';
						$html .= '</section>';
					}
				}
				//Half width image section
				else if($layout == 'half_width_image_section'){
						
					$image_array = get_sub_field('image'); 
					
					if(!empty($image_array)){
						$image = wp_get_attachment_image($image_array['ID'], 'large', false);
						
						$html .= '<section class="el-row inner small-margin-bottom-medium small-align-center content-section ' . $layout . '">';
							$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-medium-offset-3">';
								$html .= $image;
							$html .= '</div>';
						$html .= '</section>';
					}
				}
				
				
				
			}	
		}
	
		return $html;
				
	} 
	
	
	/**
	 * Displays default header banner set in the theme customizer. 
	 * 
	 * Used to display a background image, title, content and set of CTA buttons; The homepage displays the banner differently as per style guide
	 */
	public static function el_display_header_banner($object){
		
		$instance = self::getInstance();
		$html = '';

		$el_header_banner_background_image = get_theme_mod('el_header_banner_background_image');
		$el_header_banner_featured_image = get_theme_mod('el_header_banner_featured_image');
		$el_header_banner_overlay_colour = get_theme_mod('el_header_banner_overlay_colour');
		$el_header_banner_text_colour = get_theme_mod('el_header_banner_text_colour');
		$el_header_banner_title = get_theme_mod('el_header_banner_title');
		$el_header_banner_subtitle = get_theme_mod('el_header_banner_subtitle');
		$el_header_banner_primary_button_text = get_theme_mod('el_header_banner_primary_button_text');
		$el_header_banner_primary_button_url = get_theme_mod('el_header_banner_primary_button_url');
		$el_header_banner_secondary_button_text = get_theme_mod('el_header_banner_secondary_button_text');
		$el_header_banner_secondary_button_url = get_theme_mod('el_header_banner_secondary_button_url');
		
		$style = '';
		$style .= (!empty($el_header_banner_background_image)) ? 'background-image: url(' . $el_header_banner_background_image . '); ' : '';
		
		$html .= '<section class="header-banner el-row">';

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
			
			$html .= '<div class="content-wrap" style="' . $style . '">';	
				
				$html .= '<div class="el-row inner small-padding-top-bottom-large medium-padding-top-bottom-xx-large">';
				
					//main content
					$html .= '<div class="content el-col-small-12 small-align-center el-col-large-8 el-col-large-offset-4 large-align-right">';
						
						//on homepage
						if(is_front_page()){
							//Title
							if(!empty($el_header_banner_title)){
								$html .= '<h1 class="hero-title small-margin-bottom-medium">';
								//manipulate the wording used in the title
								$words = explode(" ", $el_header_banner_title);
								foreach($words as $word){
									$html .= '<span>' . $word . '</span>';
								}
								$html .= '</h1>';
							}
							//subtitle
							if(!empty($el_header_banner_subtitle)){
								$html .= '<p class="hero-subtitle">' . $el_header_banner_subtitle . '</p>';
							}
							//groups of buttons
							if(!empty($el_header_banner_primary_button_url) || !empty($el_header_banner_secondary_button_url)){
								$html .= '<div class="button-group">';
								//primary button
								if(!empty($el_header_banner_primary_button_url)){
									$html .= '<a href="' . $el_header_banner_primary_button_url . '">';
										$html .= '<div class="button primary-button">' . $el_header_banner_primary_button_text  . '</div>';
									$html .= '</a>';
								}
								//secondary button
								if(!empty($el_header_banner_secondary_button_url)){
									$html .= '<a href="' . $el_header_banner_secondary_button_url . '">';
										$html .= '<div class="button secondary-button">' . $el_header_banner_secondary_button_text  . '</div>';
									$html .= '</a>';
								}
								$html .= '</div>';
							}
							
						}
						//everywhere else
						else{
							
							//Determine what type of object we're looking at
							if($object instanceof WP_Post){
								
								//if we're on a page
								if(get_post_type($object) == 'page'){
									$parent_post_id = wp_get_post_parent_id($object->ID); 
									if($parent_post_id){
										$parent_post = get_post($parent_post_id);
										$html .= '<div class="h2 small-align-center large-align-right">' . $parent_post->post_title . '</div>';
									}
								}else if(get_post_type($object) == 'services'){
									$html .= '<div class="h2 small-align-center large-align-right">Services</div>';
								}else if(get_post_type($object) == 'conditions'){
						
									$html .= '<div class="h2 small-align-center large-align-right">Conditions</div>';
								}
								//main title
								$post_title = $object->post_title;
								$html .= '<h1 class="title">' . $post_title . '</h1>'; 
							}
							//Term
							else if($object instanceof WP_Term){
								//var_dump($object);
								
								$taxonomy = get_taxonomy($object->taxonomy);
								$taxonomy_name = $taxonomy->label;
								$term_name = $object->name;
								
								$html .= '<div class="h2 small-align-center large-align-right">' . $taxonomy_name . '</div>';
								$html .= '<h1 class="title">' . $term_name . '</h1>'; 
								
								
							}else if($object instanceof WP_User){
								$title = $object->display_name;
								$html .= '<div class="h2 small-align-center large-align-right">' . __('Author','perfectvision') . '</div>';
								$html .= '<h1 class="title">' . $title . '</h1>'; 
							}	
						}
					$html .= '</div>';

				$html .= '</div>';
			$html .= '</div>';
		$html .= '</section>';
	
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
	 * Displays the commitment program CTA
	 * 
	 * Displays the commitment program CTA as defined in the theme customizer. Used across all pages of the site
	 */
	public static function el_display_commitment_program_cta(){
		
					
		$instance = self::getInstance();
		$html = '';

		//Display customizer values
		$el_commitment_program_cta_enabled = get_theme_mod('el_commitment_program_cta_enabled');
		$el_commitment_program_cta_background_image = get_theme_mod('el_commitment_program_cta_background_image');
		$el_commitment_program_cta_background_colour = get_theme_mod('el_commitment_program_cta_background_colour');
		$el_commitment_program_cta_title = get_theme_mod('el_commitment_program_cta_title');
		$el_commitment_program_cta_content = get_theme_mod('el_commitment_program_cta_content');
		$el_commitment_program_cta_button_text = get_theme_mod('el_commitment_program_cta_button_text');
		$el_commitment_program_cta_button_url = get_theme_mod('el_commitment_program_cta_button_url');
		
		if($el_commitment_program_cta_enabled){
			
			$html .= '<section class="commitment-program-cta el-row">';
				
				//background colour
				if(!empty($el_commitment_program_cta_background_colour)){
					$style = '';
					$style .= (!empty($el_commitment_program_cta_background_colour)) ? 'background-color: ' . $el_commitment_program_cta_background_colour . '; ' : '';
					$html .= '<div class="background-color" style="' . $style . '"></div>';
				}
				
				//background image
				if(!empty($el_commitment_program_cta_background_image)){
					$style = '';
					$style .= (!empty($el_commitment_program_cta_background_image)) ? 'background-image: url(' . $el_commitment_program_cta_background_image . '); ' : '';
					$html .= '<div class="background-image" style="' . $style . '"></div>';
				}

				$html .= '<div class="content-wrap el-row inner-small small-padding-top-bottom-medium medium-padding-top-bottom-large">';
					if(!empty($el_commitment_program_cta_title)){
						$html .= '<h2 class="title el-col-small-12 small-align-center">' . $el_commitment_program_cta_title . '</h2>'; 
					}
					if(!empty($el_commitment_program_cta_content)){
						$html .= '<p class="content el-col-small-12 small-align-center">' . $el_commitment_program_cta_content . '</p>';
					}
					if(!empty($el_commitment_program_cta_button_text) && !empty($el_commitment_program_cta_button_url)){
						$html .= '<div class="small-align-center">';
							$html .= '<a class="button primary-button" href="' . $el_commitment_program_cta_button_url . '">' . $el_commitment_program_cta_button_text . '</a>';
						$html .= '</div>';
					}
					
					
				$html .= '</div>';
			$html .= '</section>';
		}
		
		echo $html;
		
		
	}

	/**
	 * Displays the appointment banner as outlined in the theme customizer, used above the footer
	 */
	public static function el_display_appointment_cta(){

		
		
		$instance = self::getInstance();
		$html = '';

		//Display customizer values
		$el_appointment_cta_enabled = get_theme_mod('el_appointment_cta_enabled');
		$el_appointment_cta_background_image = get_theme_mod('el_appointment_cta_background_image');
		$el_appointment_cta_background_colour = get_theme_mod('el_appointment_cta_background_colour');
		$el_appointment_cta_title = get_theme_mod('el_appointment_cta_title');
		$el_appointment_cta_content = get_theme_mod('el_appointment_cta_content');
		$el_appointment_cta_button_text = get_theme_mod('el_appointment_cta_button_text');
		$el_appointment_cta_button_url = get_theme_mod('el_appointment_cta_button_url');
		
		
		if($el_appointment_cta_enabled){
			
			$html .= '<section class="appointment-cta el-row">';
				
				//background colour
				if(!empty($el_appointment_cta_background_colour)){
					$style = '';
					$style .= (!empty($el_appointment_cta_background_colour)) ? 'background-color: ' . $el_appointment_cta_background_colour . '; ' : '';
					$html .= '<div class="background-color" style="' . $style . '"></div>';
				}
				
				//background image
				if(!empty($el_appointment_cta_background_image)){
					$style = '';
					$style .= (!empty($el_appointment_cta_background_image)) ? 'background-image: url(' . $el_appointment_cta_background_image . '); ' : '';
					$html .= '<div class="background-image" style="' . $style . '"></div>';
				}

				$html .= '<div class="content-wrap el-row inner-small small-padding-top-bottom-medium medium-padding-top-bottom-large">';
					if(!empty($el_appointment_cta_title)){
						$html .= '<h2 class="title el-col-small-12 small-align-center">' . $el_appointment_cta_title . '</h2>'; 
					}
					if(!empty($el_appointment_cta_content)){
						$html .= '<p class="content el-col-small-12 small-align-center">' . $el_appointment_cta_content . '</p>';
					}

					if(!empty($el_appointment_cta_button_text) && !empty($el_appointment_cta_button_url)){
						$html .= '<div class="small-align-center">';
							$html .= '<a class="button featured-button" href="' . $el_appointment_cta_button_url . '">' . $el_appointment_cta_button_text . '</a>';
						$html .= '</div>';
					}
					
					
				$html .= '</div>';
			$html .= '</section>';
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
			
			
			$html .= '<div class="widget-area el-col-small-12 el-col-medium-4 el-col-large-2">';
			
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
 $theme_base = theme_base::getInstance();
 
 




 
 
 
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

	
	register_nav_menus( array(
		'main-menu' 	=> esc_html__( 'Main Menu', 'perfectvision' ),
		'mobile-menu' 	=> esc_html__('Mobile Menu', 'perfectvision')
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
