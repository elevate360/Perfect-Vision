<?php
 
 /**
 * Simple image widget class
 * 
 * Used to display a simple image in a widget area. 
 */
 
 class el_image_widget extends WP_Widget{
 	
	public function __construct(){
			
		$args = array(
			'description'	=> 'Widget for showing a single image in your widget area'
		);
		
		parent::__construct(
			'el_image_widget', esc_html__('Simple Image Widget', 'perfectvision'), $args
		);
		
	}

	
	/**
	 * Visual output frontend
	 */
	public function widget($args, $instance){
		
		$title = isset($instance['title']) ? $instance['title'] : '';
		$resource_id = isset($instance['resource_id']) ? $instance['resource_id'] : ''; 
		
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
					//Link to a resource
					if(!empty($resource_id)){
						$resource_url = wp_get_attachment_image_src($resource_id, 'medium', false)[0];
						
						//$srcset = 
						$html .= '<img src="' . $resource_url . '"/>';
						
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
		$resource_id = isset($instance['resource_id']) ? $instance['resource_id'] : '';
		
		
		$html = '';

		$html .= '<p>';
			$html .= '<label for="' . $this->get_field_id('title') . '">' . __('Title', 'ycc') .'</label>';
			$html .= '<input class="widefat" type="text" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" value="' . $title .'"/>';
		$html .= '</p>';
		
		//Resources Selection
		$field_name = $this->get_field_name('resource_id');
		$field_id = $this->get_field_id('resource_id');
		
		$html .= '<p>';
			$html .= '<div class="image-upload-container">';
				$html .= '<label for="' . $field_id . '">' . __('Select the image this widget will display <br/>','ycc') . '</label>';
				$html .= '<input type="button" value="Select Image" class="widget-upload image-upload-button" data-multiple-upload="false" data-file-type="image" data-field-name="' . $field_name .'"/>';
				$html .= '<div class="image-container cf">';
				
				if(!empty($resource_id)){
					$image_url = wp_get_attachment_image_src($resource_id, 'thumbnail', false)[0];
					
					$html .= '<div class="image">';
					$html .=	'<input type="hidden" id="' . $field_id . '" name="' . $field_name . '" value="' .  $resource_id . '"/>';
					$html .=	'<div class="image-preview" style="background-image:url(' . $image_url . ');"></div>';
					$html .=	'<div class="image-controls cf">';
					$html .=		'<div class="control remove_image">Remove Image<i class="fa fa-minus"></i></div>';	
					$html .=	'</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
					
			$html .= '</div>';
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
		return $instance;
		
	}
	
 }
