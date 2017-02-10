<?php
/**
 * Location content type
 */
 
 class el_locations extends el_content_type{
 	
	public static $instance = null;
	
	//post type args
	public $post_type_args = array(
		'post_type_name'		=> 'locations',
		'post_type_single_name'	=> 'Location',
		'post_type_plural_name'	=> 'Locations',
		'labels'				=> array(
			'menu_name'				=> 'Locations'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-location-alt',
			'hierarchical'      	=> true,
			'supports'          	=> array('title','thumbnail'), 
			'rewrite'				=> array(
				'slug'					=> 'locations'
			)
		)
	);
	
	//metabox args
	public $meta_box_args = array(
		array(
			'id'			=> 'location_information_metabox',
			'title'			=> 'Location Information',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Contains additional information about the location. This data is pulled into various sections and used across the site'
			)	
		)
	);
	
	//metafield arguments
	public $meta_field_args = array(
		array(
			'id'			=> 'location_phone_number',
			'title'			=> 'Phone Number',
			'description'	=> 'Phone number associated with this location',
			'type'			=> 'text',
			'meta_box_id'	=> 'location_information_metabox'
		),
		array(
			'id'			=> 'location_email_address',
			'title'			=> 'Email',
			'description'	=> 'Primary email associated with this location',
			'type'			=> 'text',
			'meta_box_id'	=> 'location_information_metabox'
		),
		array(
			'id'			=> 'location_address',
			'title'			=> 'Address',
			'description'	=> 'Street address for this location',
			'type'			=> 'text',
			'meta_box_id'	=> 'location_information_metabox'
		),
		array(
			'id'			=> 'location_summary',
			'title'			=> 'Summary Information',
			'description'	=> 'Any additional informatiomn about the location you want to be displayed',
			'type'			=> 'editor',
			'meta_box_id'	=> 'location_information_metabox'
		),
		array(
			'id'			=> 'location_address_map',
			'title'			=> 'Address Map',
			'description'	=> 'Enter the full Embed Code from Google Maps to create your map',
			'type'			=> 'editor',
			'meta_box_id'	=> 'location_information_metabox'
		)
	);
	
	
	/**
	 * Constructor to create the content type
	 * 
	 * Utilises the 'el_content_type' master class to create this class
	 */
	public function __construct(){
		parent::__construct($this->post_type_args, $this->meta_box_args, $this->meta_field_args, null, null);

	}
	
	/**
	 * Display function to list out content type
	 */
	public static function display_service_listing_list(){
		
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'row'
		);
		
		$html .= $instance->get_locations_listing($args);
		
		echo $html;
	}
	
	/**
	 * Display function to display content type in a grid
	 */
	public static function display_service_listing_grid(){
			
		$html = '';
		$instance = self::getInstance();
		
		$args = array(
			'parent_post_id'	=> 0,
			'style_type' 		=> 'grid'
		);
		
		$html .= $instance->get_locations_listing($args);
		
		echo $html;
	}
	
	
	/**
	 * Gets a summary listing of our content type
	 * 
	 * Conditionally adjusted as it's called from several places
	 */
	public function get_locations_listing($arguments = array()){
		
		$html = '';
		
		//base post args
		$post_args = array(
			'post_type'		=> $this->post_type_args['post_type_name'],
			'post_status'	=> 'publish',
			'posts_per_page'=> -1,
			'orderby'		=> 'post_date',
			'order'			=> 'ASC'
		);
		
		//if we want to get a to get a single product or list of products
		if(isset($arguments['post_id']) || isset($arguments['include'])){
			if(isset($arguments['post_id'])){
				$post_args['include'] = $arguments['post_id'];
			}
			if(isset($arguments['include'])){
				$post_args['include'] = $arguments['include'];
			}
		}
	
		//if we passed in a parent ID, get all children belonging to this parent (or optionally get top
		//eleemnts if we passed in 0
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

			$html .= '<div class="location-listing el-row inner small-margin-top-bottom-medium ' . $classes .'">';
			
			foreach($posts as $post){
				
				$post_id = $post->ID;
				$post_title = $post->post_title; 
				$post_url = get_permalink($post_id);
		
				//collect metadata
				$location_phone_number = get_post_meta($post_id, 'location_phone_number', true);
				$location_email_address = get_post_meta($post_id, 'location_email_address', true);
				$location_address = get_post_meta($post_id, 'location_address', true);
				$location_summary = get_post_meta($post_id, 'location_summary', true);
				$location_address_map = get_post_meta($post_id, 'location_address_map', true);
				
				
				//Output based on what type of listing
				if($arguments['style_type'] == 'row'){
				
					$html .= '<div class="location hero-card el-col-small-12 collapse small-margin-top-bottom-medium small-padding-bottom-medium">';

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
					$html .= '<div class="location el-col-small-12 el-col-medium-6 small-padding-top-bottom-small small-margin-bottom-small">';
						$html .= '<div class="inner">';
						
							$html .= '<section class="header small-align-center">';
								$html .= '<h2 class="title">' . $post_title . '</h2>';
							$html .= '</section>';
							
							//summary data
							if(!empty($location_summary)){
								$html .= '<section class="summary small-margin-bottom-medium">' . $location_summary  . '</section>';
							}
							
							//main contact data
							$html .= '<section class="contact-info small-margin-bottom-medium">';
								
								if(!empty($location_phone_number)){
									$html .= '<div class="section phone el-row small-padding-top-bottom-small">';
										$html .= '<div class="icon-wrap el-col-small-12 el-col-medium-2 small-align-center medium-align-left">';
											$html .= '<span class="icon"></span>';
										$html .= '</div>';
										$html .= '<div class="content-wrap el-col-small-12 el-col-medium-10 small-align-center medium-align-left">';
											$html .= '<a href="tel:' . trim($location_phone_number) . '" title="' . __('call us today', 'perfectvision') . '">' . $location_phone_number . '</a>';
										$html .= '</div>';
									$html .= '</div>';								
								}
								if(!empty($location_email_address)){
									$html .= '<div class="section email el-row small-padding-top-bottom-small">';
										$html .= '<div class="icon-wrap el-col-small-12 el-col-medium-2 small-align-center medium-align-left">';
											$html .= '<span class="icon"></span>';
										$html .= '</div>';
										$html .= '<div class="content-wrap el-col-small-12 el-col-medium-10 small-align-center medium-align-left">';
											$html .= '<a href="mailto:' . trim($location_email_address) . '" title="' . __('email us today', 'perfectvision') . '">' . $location_email_address . '</a>';
										$html .= '</div>';
									$html .= '</div>';							
								}
								if(!empty($location_address)){
									$html .= '<div class="section location el-row small-padding-top-bottom-small">';
										$html .= '<div class="icon-wrap el-col-small-12 el-col-medium-2 small-align-center medium-align-left">';
											$html .= '<span class="icon"></span>';
										$html .= '</div>';
										$html .= '<div class="content-wrap el-col-small-12 el-col-medium-10 small-align-center medium-align-left">';
											$html .= $location_address;
										$html .= '</div>';
									$html .= '</div>';							
								}
								
							$html .= '</section>';
							
							
							//contact mapo
							if(!empty($location_address_map)){
								$html .= '<section class="map">';
									$html .= $location_address_map;
								$html .= '</section>';
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
	
	
	/**
	 * gets singleton instance of this class
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	
 }
 