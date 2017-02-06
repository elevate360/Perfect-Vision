<?php
/**
 * FAQ content type, used to power the various FAQ sections on the site
 */
 
 class el_specials extends el_content_type{
 	
	private static $instance = null;
	
	public $post_type_args = array(
		'post_type_name'		=> 'el_specials',
		'post_type_single_name'	=> 'Special Catalogue',
		'post_type_plural_name'	=> 'Special Catalogues',
		'labels'				=> array(
			'menu_name'				=> 'Special Catalogues'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-book',
			'hierarchical'      	=> true,
			'supports'          	=> array('title','editor','thumbnail'), 
			'rewrite'				=> array(
				'slug'					=> 'special-catlogue'
			)
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'specials_metabox',
			'title'			=> 'Additional Required Information',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Please complete the following fields for your post'
			)
		)
	);

	//meta field elements
	private $meta_field_args = array(
		
		array(
			'id'			=> 'catalogue_url',
			'title'			=> 'Catalogue URL',
			'description'	=> 'Enter the full URL to the resource you want the user to download (This can be found inside the media library for your uploaded item)',
			'type'			=> 'text',
			'meta_box_id'	=> 'specials_metabox'
		)
		

	);
	
	
	
	/**
	 * Constructor / initiator
	 */
	public function __construct(){
		
		parent::__construct($this->post_type_args, $this->meta_box_args, $this->meta_field_args, null , null);
		
		
	}
	
	/**
	 * Gets the HTML for the ctalogue listing to be displayed
	 */
	public function get_catalogue_listing(){
		$html = '';
		
		
		//base post args
		$post_args = array(
			'post_type'		=> $this->post_type_args['post_type_name'],
			'post_status'	=> 'publish',
			'posts_per_page'=> -1,
			'orderby'		=> 'post_date',
			'order'			=> 'ASC'
		);
		
		//if we want to get a single element
		if(isset($arguments['post_id'])){
			$post_args['include'] = $arguments['post_id'];
		}

		$posts = get_posts($post_args);
		if($posts){
			$html .= '<div class="catalogue-listing el-row">';
			foreach($posts as $post){
				$post_id = $post->ID;
				$post_title = $post->post_title; 
				$post_content = $post->post_content;
				$post_url = get_permalink($post_id);
				$post_thumbnail_id = get_post_thumbnail_id($post_id);
				
				$catalogue_url = get_post_meta($post_id, 'catalogue_url', true);
				
				//build output
				$html .= '<div class="catalogue small-padding-bottom-small el-col-small-12 small-margin-bottom-medium">';
					
					//image
					if(!empty($post_thumbnail_id)){
						$post_thumbnail_image = wp_get_attachment_image($post_thumbnail_id, 'medium', false);
						$html .= '<div class="image el-col-small-12 el-col-medium-4 small-padding-bottom-small">';
							$html .= $post_thumbnail_image;
						$html .= '</div>';
					}
					
					//content container
					$container_class = !empty($post_thumbnail_id) ? 'el-col-small-12 el-col-medium-8' : 'el-col-small-12';
					$html .= '<div class="' . $container_class . '">';
						if(!empty($post_content)){
							$html .= '<h3 class="title">' . $post_title . '</h3>';
							$html .= '<div class="content">' . apply_filters('the_content', $post_content) . '</div>';
						}
						if(!empty($catalogue_url)){
							$html .= '<div class="el-col-small-12 collapse download">';
								$html .= '<a class="el-col-small-12 small-padding-top-bottom-small h5" download href="' . $catalogue_url . '" title="' . $post_title  .'"><span class="icon"></span><span class="text">' . __('Download File', 'ycc') . '</span></a>';
							$html .= '</div>';
						}
					$html .= '</div>';
					
					
				$html .= '</div>';
			}
			$html .= '</div>';
			
		}
		
		
		return $html;
	}
	
	/**
	 * Displays the listing of catalogues, used on the 'specials' page
	 */
	public static function el_display_catalogue_listing(){
			
		$instance = self::getInstance();
		
		$html = '';
		
		$html .= $instance->get_catalogue_listing();

		echo $html;
	}
	
	
	/**
	 * Gets / sets single instance of class
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self;
		}
		return self::$instance;
	}

	
 }


?>