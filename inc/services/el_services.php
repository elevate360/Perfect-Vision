<?php
/**
 * Services content type, used to display a listing of services
 */
 	
class el_services extends el_content_type{
	
	private static $instance = null;
	
	public $post_type_args = array(
		'post_type_name'		=> 'services',
		'post_type_single_name'	=> 'Service',
		'post_type_plural_name'	=> 'Services',
		'labels'				=> array(
			'menu_name'				=> 'Services'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-nametag',
			'hierarchical'      	=> true,
			'supports'          	=> array('title','editor','thumbnail','excerpt', 'page-attributes'), 
			'rewrite'				=> array(
				'slug'					=> 'services'
			)
		)
	);


	/**
	 * Constructor to create the content type
	 * 
	 * Utilises the 'el_content_type' master class to create this class
	 */
	public function __construct(){
		parent::__construct($this->post_type_args, null, null, null, null);
		
		//register product widgets
		add_action('widgets_init', array($this, 'register_widgets'));
	}

	/**
	 * Registers widgets for use 
	 */
	public function register_widgets(){
		//register_widget('el_product_top_listing');
	}
	
	/**
	 * gets singleton instance of this class
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	

	
	
	/**
	 * Display function to get a row listing of content type
	 */
	public static function display_service_listing_list(){
		
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'row'
		);
		
		$html .= $instance->get_servicess_listing($args);
		
		echo $html;
	}
	
	/**
	 * Display function to get a grid structure for our services
	 */
	public static function display_service_listing_grid(){
			
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'grid'
		);
		
		$html .= $instance->get_servicess_listing($args);
		
		echo $html;
	}
	
	/**
	 * Gets a summary listing of our products, displayed with a photo, title, excerpt and readmore link
	 * 
	 * Conditionally adjusted as it's called from several places
	 */
	public function get_servicess_listing($arguments = array()){
		
		
		$html = '';
		
		//base post args
		$post_args = array(
			'post_type'		=> $this->post_type_args['post_type_name'],
			'post_status'	=> 'publish',
			'posts_per_page'=> -1,
			'orderby'		=> 'post_date',
			'order'			=> 'ASC'
		);
		
		//if we want to get a single product listing
		if(isset($arguments['post_id'])){
			$post_args['include'] = $arguments['post_id'];
		}
	
		//if we passed in a parent ID, get all children belonging to this parent (or optionally get top
		//products if we passed in 0
		if(isset($arguments['parent_post_id'])){
			$post_args['post_parent'] = $arguments['parent_post_id'];
		}
		
		$posts = get_posts($post_args);
		if($posts){
			
			//determine our layout style
			$classes = '';
			if(isset($arguments['style_type'])){		
				if($arguments['style_type'] == 'grid'){
					$classes .= 'small-row-of-one small-row-of-three grid';
				}else if($arguments['style_type'] == 'row'){
					$classes .= 'small-row-of-one row list'; 
				}
			}

			$html .= '<div class="service-listing el-row inner small-margin-top-bottom-medium ' . $classes .'">';
			
			foreach($posts as $post){
				
				$post_id = $post->ID;
				$post_title = $post->post_title; 
				$post_content = $post->post_content;
				$post_excerpt = $post->post_excerpt;
				$post_url = get_permalink($post_id);
				$post_thumbnail_id = get_post_thumbnail_id($post_id);
		
				$post_description = '';
				if(!empty($post_excerpt)){
					$post_description = $post_excerpt;
				}else{
					if(!empty($post_content)){
						$post_description = wp_trim_words($post_content, 25, '..');
					}
				}
				
				//Output based on what type of listing
				if($arguments['style_type'] == 'row'){
				
					$html .= '<div class="service el-col-small-12 collapse small-margin-top-bottom-medium small-padding-bottom-medium">';
						//image
						if(!empty($post_thumbnail_id)){
							$url = wp_get_attachment_image_src($post_thumbnail_id, 'medium', false)[0];
							$html .= '<div class="el-col-small-12 el-col-medium-3">';
								$html .= '<div class="image-wrap small-aspect-1-1">';
									$html .= '<a href="' . $post_url .'">';
										$html .= '<div class="image " style="background-image: url(' . $url . ');"></div>';
									$html .= '</a>';
								$html .= '</div>';	
							$html .= '</div>';
						}
						
						//main content
						if(!empty($post_description)){
							$html .= '<div class="el-col-small-12 el-col-medium-9">';
								if(!empty($post_title)){
									$html .= '<h3 class="title">' . $post_title . '</h3>';
								}	
								if(!empty($post_content)){
									$html .= '<div class="excerpt small-margin-bottom-small">' . $post_excerpt . '</div>';
								}
								$html .= '<a class="button primary-button" href="' . $post_url . '" title="' . $post_title . '">View More</a>';
							$html .= '</div>';
						}
					$html .= '</div>';
				}

				//Grid Style
				else if($arguments['style_type'] == 'grid'){
					$html .= '<div class="service el-col-small-12 el-col-medium-4 small-padding-top-bottom-small small-margin-bottom-small">';
						$html .= '<div class="inner">';
							if(!empty($post_thumbnail_id) || !empty($post_title)){
								$html .= '<div class="content small-align-center">';
									
										//image
										if(!empty($post_thumbnail_id)){
											$url = wp_get_attachment_image_src($post_thumbnail_id, 'medium', false)[0];
											$html .= '<a href="' . $post_url .'" title="' . $post_title .'">';
												$html .= '<div class="image-wrap">';
													$html .= '<img class="image" src="' .  $url . '"/>';
												$html .= '</div>';
											$html .= '</a>';
										}
										if(!empty($post_description)){
											$html .= '<div class="content small-margin-bottom-small">' . $post_description . '</div>';
										}
										$html .= '<a href="' . $post_url .'" title="' . $post_title .'">';
											$html .= '<div class="button secondary-button">Read More</div>';
										$html .= '</a>';
								$html .= '</div>';
							}
							//edit link for admins
							if(current_user_can('edit_posts')){
								$url = get_edit_post_link($post_id); 
								$html .= '<div class="el-row small-align-center small-margin-top-bottom-small">';
									$html .= '<a class="button primary-button small" href="' . $url .'" title="edit">Edit</a>';
								$html .= '</div>';
							}
							
						$html .= '</div>';
					$html .= '</div>';
				}
			}
			$html .= '</div>';
		}
		
		
		return $html;
	}
	
}

/**
 * Product Listing Top widget
 * 
 * Displayed the top level product items for inclusion on the single product page
 */
 
 class el_product_top_listing extends WP_Widget{
 	
	public function __construct(){
			
		$args = array(
			'description'	=> 'Displays a listing of all top level products, displayed on the single product page'
		);
		
		parent::__construct(
			'el_product_top_listing', esc_html__('Top Product Listing', 'ycc'), $args
		);
		
	}
	
	/**
	 * Gets a listing of the top products with links to each
	 * 
	 * used in our widget frontend output
	 */
	public function get_product_top_listing(){
		
		$html = '';
		
		$el_services = el_services::getInstance();
		
		$post_args = array(
			'post_type'		=> $el_services->post_type_args['post_type_name'],
			'posts_per_page'=> -1,
			'post_status'	=> 'publish',
			'orderby'		=> 'post_title',
			'order'			=> 'ASC',
			'post_parent'	=> 0 //only top level items
		);
		
		$posts = get_posts($post_args);
		if($posts){
			$html .= '<div class="product-listing-widget">';
				foreach($posts as $post){
					
					$product_name = $post->post_title;
					$product_url = get_permalink($post->ID);
						
					$html .= '<div class="product">';
						$html .= '<a class="small-padding-top-bottom-small" href="' . $product_url . '" title="' . $product_name . '">' . $product_name . '</a>';
					$html .= '</div>';
				}
			$html .= '</div>';
		}
		
		return $html;
		
		
	}
	 
	
	
	/**
	 * Visual output frontend
	 */
	public function widget($args, $instance){
		
		$html = '';
		
		$html .= $args['before_widget'];
		
			$html .= '<div class="widget-wrap">';
				
				//title if supplied
				if(isset($instance['title'])){
					$html .= $args['before_title'];
						$html .= $instance['title'];
					$html .= $args['after_title'];	
				}
				
				//main content
				$html .= '<div class="widget-content">';
					$html .= $this->get_product_top_listing();
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
		
		$html = '';
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		
		echo $html;
	}
	
	/**
	 * Save callback
	 */
	public function update($new_instance, $old_instance){
		
		$instance = array();
		
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		
		return $instance;
		
	}
	
 }
		

?>