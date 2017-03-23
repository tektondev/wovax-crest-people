<?php

class People_Post_Type_WCREST extends Post_Type_WCREST {
	
	public $post_type = 'people';
	public $post_type_args = array(
      'public' => true,
	  'label'  => 'People',
	  'supports' => array( 'title','editor','excerpt','thumbnail','custom-fields' ),
	  'rewrite'            => array( 'slug' => 'our-team' ),
	);
	public $fields = array(
		'OfficeStaffId' 				=> 'text',
		'_person_id' 					=> 'text',
		'_crest_id' 					=> 'text',
		'_rfg_office_staff_id' 			=> 'text',
		'_status' 						=> 'text',
		'_office_id' 					=> 'text',
		'_position_type' 				=> 'text',
		'_position' 					=> 'text',
		'_active_since' 				=> 'text',
		'_show' 						=> 'text',
		'_first_name' 					=> 'text',
		'_middle_name' 					=> 'text',
		'_last_name' 					=> 'text',
		'_display_name' 				=> 'text',
		'_familiar_name' 				=> 'text',
		'_description' 					=> 'text',
		'_primary_email' 				=> 'text',
		'_primary_phone' 				=> 'text',
		'_office_location' 				=> 'text',
		'_phone_additional' 			=> 'text',
		'_primary_photo_url'			=> 'text',
		'_primary_image_title' 			=> 'text',
		'_youtube_video_url' 			=> 'text',
		'_youtube_video_cover_img' 		=> 'text',
		'_shortcode_active_override' 	=> 'text',
		'_shortcode_closed_override' 	=> 'text',
		'_offices' 						=> 'text', 
	);
	public $save_fields = array(
		'_shortcode_active_override' 	=> 'text',
		'_shortcode_closed_override' 	=> 'text',
	);
	
	protected function the_editor( $post, $settings ){
		
		$crest_fields = array(
			'OfficeStaffId' 			=> 'Office Staff ID',
			'_person_id' 				=> 'Person ID',
			'_crest_id' 				=> 'CREST ID',
			'_rfg_office_staff_id' 		=> 'RFG Office Staff ID',
			'_status' 					=> 'Status',
			'_office_id' 				=> 'Office ID',
			'_position_type' 			=> 'Position Type',
			'_position' 				=> 'Position',
			'_active_since' 			=> 'Active Since',
			'_show' 					=> 'Show on Web',
			'_first_name' 				=> 'First Name',
			'_middle_name' 				=> 'Middle Name',
			'_last_name' 				=> 'Last Name',
			'_display_name' 			=> 'Display Name',
			'_familiar_name' 			=> 'Familiar Name',
			'_description' 				=> 'Description',
			'_primary_email' 			=> 'Email',
			'_primary_phone' 			=> 'Phone',
			'_office_location' 			=> 'Office Location',
			'_phone_additional' 		=> 'Phone Additional',
			'_primary_photo_url'		=> 'Image URL',
			'_primary_image_title' 		=> 'Image Title',
		);
		
		include 'parts/editor.php';
		
	} // end the_editor
	
	
	public function the_content_filter( $content ){
		
		if ( is_singular( 'people' ) ){
			
			global $post;
			
			$image = get_post_meta( get_the_ID(),  '_primary_photo_url', true );
			$name = get_post_meta( get_the_ID(),  '_display_name', true );
			$position = get_post_meta( get_the_ID(),  '_position', true );
			$email = get_post_meta( get_the_ID(),  '_primary_email', true );
			$phone = get_post_meta( get_the_ID(),  '_primary_phone', true );
			$video = get_post_meta( get_the_ID(),  '_youtube_video_url', true );
			
			$video_html = ( ! empty( $video ) ) ? wp_oembed_get( $video ) : '';
			
			$video_html = str_replace( '?feature=oembed', '?feature=oembed&autoplay=1&&rel=0', $video_html );  
			
			if ( ! $phone ){ 
				
				$phone = get_post_meta( get_the_ID(),  '_phone_additional', true );
				
			} // end if  
			
			if ( ! $image ){
				
				$image = WCRESTPLUGINURL . 'post-types/people/images/personplaceholder.gif';
				
			}
			
			if ( ! empty( $phone ) ){
				
				$phone_array = str_split( $phone , 3 );
				
				$phone = $phone_array[0];
				
				if ( isset( $phone_array[1] ) ) $phone .= '.' .  $phone_array[1];
				if ( isset( $phone_array[2] ) ) $phone .= '.' .  $phone_array[2];
				if ( isset( $phone_array[3] ) ) $phone .= $phone_array[3];
			
			} // end if
			
			$website = get_post_meta( get_the_ID(),  '_primary_web_url', true );
			$link = get_post_permalink();
			
			$scode_active = get_post_meta( get_the_ID(),  '_shortcode_active_override', true );
			
			if ( empty( $scode_active ) ){
				
				$scode_active = get_theme_mod('crest_profile_shortcode_1', '');
				
			} // end if
			
			$scode_closed = get_post_meta( get_the_ID(),  '_shortcode_closed_override', true );
			
			if ( empty( $scode_closed ) ){
				
				$scode_closed = get_theme_mod('crest_profile_shortcode_2', '');
				
			} // end if
			
			$shortcode_1 = $this->replace_values( $scode_active );
			$shortcode_2 = $this->replace_values( $scode_closed );
			
			ob_start();
			
			include 'parts/include-profile.php';
			
			$content_html = ob_get_clean();
			
			$content = $content_html;
			
		} // end if
		
		return $content;
		
	}
	
	
	protected function replace_values( $shortcode ){ 
		
		$replace = array(
			'%crest_id%' => get_post_meta( get_the_ID(),  '_crest_id', true ),
			'%primary_email%' => get_post_meta( get_the_ID(),  '_primary_email', true ),
			'%rfg_office_staff_id%' => get_post_meta( get_the_ID(),  '_rfg_office_staff_id', true ),
		);
		
		foreach( $replace as $key => $value ){
			
			$shortcode = str_replace( $key, $value, $shortcode );
			
		} // end foreach
		
		return $shortcode;
		
	}
	
	
}