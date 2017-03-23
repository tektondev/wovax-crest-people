<?php

class Person_WCREST {
	
	public $fields = array();
	public $settings = array();
	
	
	public function set_person_from_crest( $crest_xml ){
		
		$this->settings['OfficeStaffId'] = $this->get_value_from_xml_unique( 'OfficeStaffId', $crest_xml );
		$this->settings['_person_id'] = $this->get_value_from_xml_unique( 'PersonID', $crest_xml );
		$this->settings['_crest_id'] = $this->get_value_from_xml_unique( 'PersonID', $crest_xml );
		$this->settings['_rfg_office_staff_id'] = $this->get_value_from_xml_unique( 'RFGOfficeStaffID', $crest_xml );
		$this->settings['_status'] = $this->get_value_from_xml_unique( 'Status', $crest_xml );
		$this->settings['_office_id'] = $this->get_value_from_xml_unique( 'OfficeId', $crest_xml );
		$this->settings['_position_type'] = $this->get_value_from_xml_unique( 'PositionType', $crest_xml );
		$this->settings['_position'] = $this->get_value_from_xml_unique( 'PositionName', $crest_xml );
		$this->settings['_active_since'] = $this->get_value_from_xml_unique( 'ActiveSince', $crest_xml );
		$this->settings['_show'] = $this->get_value_from_xml_unique( 'IsShowOnInternet', $crest_xml );
		$this->settings['_first_name'] = $this->get_value_from_xml_unique( 'FirstName', $crest_xml );
		$this->settings['_middle_name'] = $this->get_value_from_xml_unique( 'MiddleName', $crest_xml );
		$this->settings['_last_name'] = $this->get_value_from_xml_unique( 'LastName', $crest_xml );
		$this->settings['_display_name'] = $this->get_value_from_xml_unique( 'DisplayName', $crest_xml );
		$this->settings['_familiar_name'] = $this->get_value_from_xml_unique( 'FamiliarName', $crest_xml );
		$this->settings['_description'] = $this->get_value_from_xml_nested( 'ProfileDescriptions', 'Description', $crest_xml );
		$this->settings['_primary_email'] = $this->get_value_from_xml_nested( 'DefaultEmail', 'EmailAddress', $crest_xml );
		$this->settings['_primary_phone'] = $this->get_value_from_xml_unique( 'DefaultPhoneNumber', $crest_xml );
		$this->settings['_office_location'] = $this->get_value_from_xml_unique( 'DefaultAddress', $crest_xml );
		$this->settings['_phone_additional'] = $this->get_value_from_xml_nested( 'AdditionalPhoneNumbers','Number', $crest_xml );
		$this->settings['_primary_photo_url'] = $this->get_value_from_xml_nested( 'MediaItems','URL', $crest_xml );
		$this->settings['_primary_image_title'] = $this->get_value_from_xml_nested( 'MediaItems','Title', $crest_xml );
		
		return true;
		
	} // end set_person_from_crest
	
	
	public function create_person(){
		
		$exists = $this->check_person_exists( $this->settings['_person_id'] );
		
		if ( ! $exists  ) {
			
			$content = htmlspecialchars_decode ( $this->settings['_description'] );
			
			$post = array(
				'post_content' => $content,
				'post_title' => $this->settings['_last_name'] . ', ' . $this->settings['_first_name'],
				'post_status' => 'publish',
				'post_type' => 'people',
				'meta_input' => $this->settings,
			);
			
			$post_id = wp_insert_post( $post );
			
			$positions = explode( ',', $this->settings['_position'] );
		
			if ( is_array( $positions ) ){
				
				foreach( $positions as $position ){
					
					$term = get_term_by( 'name', $position, 'people_position', ARRAY_A );
					
					if ( ! $term ) {
				
						$term = wp_insert_term( $position, 'people_position' );
						
					} // end if
					
					wp_set_object_terms( $post_id, $term['term_id'], 'people_position' );
					
				} // end foreach
				
			} // end if	
			
			return array(
				'status' 	=> true,
				'msg' 		=> 'Person created',
				'response' => $post_id,
			);
			
		} else {
			
			return array(
				'status' 	=> false,
				'msg' 		=> 'Person already exists',
				'response' => $exists,
			);
			
		} // end if
		
	} // end create_person
	
	
	public function append_office( $post_id, $office_id ){
		
		$offices = get_post_meta( $post_id, '_offices', true );
		
		$offices = explode( ',', $offices );
		
		if ( ! in_array( $office_id, $offices ) ){
			
			$offices[] = $office_id;
		
			$offices = implode( ',', $offices );
		
			update_post_meta( $post_id, '_offices', $offices );
			
		} // end if
		
	} // end append_office
	
	
	public function check_person_exists( $crest_id ){
		
		$args = array(
			'post_type' => 'people',
			'posts_per_page' => 1,
			'status' => 'any',
			'meta_key' => '_person_id',
			'meta_value' => $crest_id,
		);
		
		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			
			$the_query->the_post();
			
			wp_reset_postdata();
			
			return $the_query->post->ID;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end check_person_exists
	
	
	private function get_value_from_xml_unique( $key, $xml ){ //'OfficeStaffId'
		
		$regex = '/<.*?:' . $key . '>(.*?)<\/.*?:' . $key . '>/';
		
		preg_match( $regex, $xml, $matches, PREG_OFFSET_CAPTURE);
		
		if ( is_array( $matches ) && ! empty( $matches[1][0] ) ){
			
			return $matches[1][0];
			
		} else {
			
			return '';
			
		}
		
	} // end get_value_from_xml_unique
	
	
	private function get_value_from_xml_nested( $parent_key, $key, $xml ){ //'OfficeStaffId'
		
		$regex = '/<.*?' . $parent_key . '.*?:' . $key . '>(.*?)<.*?:' . $key . '>/s';
		
		preg_match( $regex, $xml, $matches, PREG_OFFSET_CAPTURE);
		
		if ( is_array( $matches ) && ! empty( $matches[1][0] ) ){
			
			return $matches[1][0];
			
		} else {
			
			return '';
			
		}
		
	} // end get_value_from_xml_unique
	
}