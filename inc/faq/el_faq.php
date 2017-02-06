<?php
/**
 * FAQ content type, used to power the various FAQ sections on the site
 */
 
 class el_faq extends el_content_type{
 	
	private static $instance = null;
	
	public $post_type_args = array(
		'post_type_name'		=> 'el_faq',
		'post_type_single_name'	=> 'Faq',
		'post_type_plural_name'	=> 'Faqs',
		'labels'				=> array(
			'menu_name'				=> 'Faqs'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-testimonial',
			'hierarchical'      	=> true,
			'supports'          	=> array('title','editor','thumbnail'), 
			'rewrite'				=> array(
				'slug'					=> 'faq'
			)
		)
	);
	
	public $taxonomy_args = array(
		array(
			'taxonomy_name'			=> 'el_faq_category',
			'taxonomy_single_name'	=> 'Faq Category',
			'taxonomy_plural_name'	=> 'Faq Categories',
			'labels'				=> array(
				'menu_name'				=> 'Categories'
			),
			'args'					=> array(
				'hierarchical'			=> true,
				'rewrite'				=> array(
					'slug'					=> 'faq-category'
				)
			)
		)
	);
	
	
	/**
	 * Constructor / initiator
	 */
	public function __construct(){
		
		parent::__construct($this->post_type_args, null, null, $this->taxonomy_args, null);
		
		add_action('widgets_init', array($this, 'register_widgets'));
	}

	/**
	 * Registers widgets for use 
	 */
	public function register_widgets(){
		register_widget('el_faq_widget');
	}
	
	/**
	 * Get all FAQ and their corresponding answers in a simple list
	 */
	public function get_faq_html($arguments = array()){
		
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
			$html .= '<div class="faq-listing el-row">';
			foreach($posts as $post){
				$post_id = $post->ID;
				$post_title = $post->post_title; 
				$post_content = $post->post_content;
				$post_url = get_permalink($post_id);
				$post_thumbnail_id = get_post_thumbnail_id($post_id);
				
				//build output
				$html .= '<div class="faq el-col-small-12 small-margin-bottom-medium">';
					$html .= '<h3 class="question"> Q: ' . $post_title . '</h3>';
					if(!empty($post_content)){
						$html .= '<div class="answer small-padding-top-bottom-small el-col-small-12">' . $post_content . '</div>';
					}
					
				$html .= '</div>';
			}
			$html .= '</div>';
			
		}
		
		return $html;
	}
	
	/**
	 * Display all FAQ elements in a simple list layout
	 */
	public static function el_display_all_faq(){
		
		$html = '';
		
		$instance = self::getInstance();
		
		$html .= $instance->get_faq_html();
		
		echo $html;
	}
	
	/**
	 * Gets Singleton instance of class
	 */
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	
 }


//FAQ widget
class el_faq_widget extends WP_widget{
	
	/**
	 * Constrcutor 
	 */
	public function __construct(){
		
		$args = array(
			'description'	=> 'Displays a listing of our FAQ elements, useful for page / footer sidebars'
		);
		
		parent::__construct(
			'el_faq_widget', esc_html__('FAQ Listing', 'ycc'), $args
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
				$faq_ids = isset($instance['faq_ids']) ? $instance['faq_ids'] : '';
				
				//title if supplied
				if(isset($instance['title'])){
					$html .= $args['before_title'];
						$html .= $title;
					$html .= $args['after_title'];	
				}
				
				//main content
				$html .= '<div class="widget-content">';
				
				if(!empty($faq_ids)){
					$faq_ids = json_decode($faq_ids);
					foreach($faq_ids as $faq_id){
						//get the markup for a single faq
						$html .= $eq_faq->get_faq_html(array('post_id' => $faq_id));
					}
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
			
		$title = isset($instance['title']) ? $instance['title'] : '';
		$faq_ids = isset($instance['faq_ids']) ? $instance['faq_ids'] : '';
		
		//var_dump($instance);
		
		$html = '';
		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		
		//Select which faq elements to show
		$html .= '<p>';
			$html .= '<label>' . __('Which FAQ elements do you want?', 'ycc') .'</label>';
			
			//get FAQ instance 
			$eq_faq = el_faq::getInstance();
			
			$post_args = array(
				'post_type'		=> $eq_faq->post_type_args['post_type_name'],
				'posts_per_page'=> -1,
				'post_status'	=> 'publish',
				'orderby'		=> 'post_title',
				'order'			=> 'ASC',
			);
			$posts = get_posts($post_args);
			if($posts){
				//decode list of items if we have it
				if(!empty($faq_ids)){
					$faq_ids = json_decode($faq_ids);
				}
				foreach($posts as $post){
					$title = $post->post_title;
					$id = $post->ID;
					
					$html .= '<p>';
						
							//array decoded
							if(is_array($faq_ids)){
								//check if we have this checked already
								if(in_array($id, $faq_ids)){
									$html .= '<input checked type="checkbox" id="' . $this->get_field_id('faq_ids') .'-' . $id  . '" name="' . $this->get_field_name('faq_ids[]') . '" value="' . $id .'"/>';
								}else{
									$html .= '<input type="checkbox" id="' . $this->get_field_id('faq_ids') .'-' . $id  . '" name="' . $this->get_field_name('faq_ids[]') . '" value="' . $id .'"/>';
								}
							}else{
								$html .= '<input type="checkbox" id="' . $this->get_field_id('faq_ids') .'-' . $id  . '" name="' . $this->get_field_name('faq_ids[]') . '" value="' . $id .'"/>';
							}

						$html .= '<label for="' . $this->get_field_id('faq_ids') . '-' . $id  . '">' . $title . '</label>';
					$html .= '</p>';
					
				}
			}else{
				$html .= '<b>Sorry, there are no FAQ to display yet</b>';
			}
			
			
		$html .= '</p>';
		
		
		echo $html;
	}
	
	/**
	 * Save callback
	 */
	public function update($new_instance, $old_instance){
		
		$instance = array();
		
		$instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
		
		if(isset($new_instance['faq_ids'])){
			$values = json_encode($new_instance['faq_ids']);
		}else{
			$values = '';
		}
		$instance['faq_ids'] = $values;
	
		
		return $instance;
		
	}
	
}


?>