<?php
/**
 * Conditions content type
 */
 	
class el_conditions extends el_content_type{
	
	private static $instance = null;
	
	public $post_type_args = array(
		'post_type_name'		=> 'conditions',
		'post_type_single_name'	=> 'Condition',
		'post_type_plural_name'	=> 'Conditions',
		'labels'				=> array(
			'menu_name'				=> 'Conditions'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-visibility',
			'hierarchical'      	=> true,
			'supports'          	=> array('title','thumbnail','excerpt', 'page-attributes'), 
			'rewrite'				=> array(
				'slug'					=> 'conditions'
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
	 * Display function to get the top parent products. Used on our products master page to
	 * display the top level important items in a listing format
	 */
	public static function display_condition_listing_list(){
		
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'row'
		);
		
		$html .= $instance->get_conditions_listing($args);
		
		echo $html;
	}
	
	/**
	 * Display function to get a grid structure for our conditions
	 */
	public static function display_condition_listing_grid(){
			
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'grid'
		);
		
		$html .= $instance->get_conditions_listing($args);
		
		echo $html;
	}
	
	/**
	 * Gets a summary listing of our products, displayed with a photo, title, excerpt and readmore link
	 * 
	 * Conditionally adjusted as it's called from several places
	 */
	public function get_conditions_listing($arguments = array()){
		
		
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

			$html .= '<div class="condition-listing el-row inner small-margin-top-bottom-medium ' . $classes .'">';
			
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
				
					$html .= '<div class="condition el-col-small-12 collapse small-margin-top-bottom-medium small-padding-bottom-medium">';
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
					$html .= '<div class="condition el-col-small-12 el-col-medium-4 small-padding-top-bottom-small small-margin-bottom-small">';
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
										$html .= '<a href="' . $post_url .'">';
											$html .= '<div class="button secondary-button">Read More</div>';
										$html .= '</a>';
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

		

?>