<?php

class CREST_Person {
		
	protected $post_id;
	protected $id;
	
	protected $fields = array(
		'crest_id',
		'first_name',
		'last_name',
		'display_name',
		'familiar_name',
		'office_id',
		'office_name',
		'primary_photo_url',
		'rfg_office_staff_id',
		'position',
		'status',
		'languages',
		'brandcode',
		'office_location',
		'primary_phone',
		'primary_email',
		'primary_web_url',
		'office_staff_id',
	);
	
	protected $field_values = array();
	
	
	public function get_post_id() { return $this->post_id; }
	public function get_id() { return $this->id; }
	public function get_fields() { return $this->fields; }
	public function get_field_value( $field ) { return $this->return_field( $field ); }
	public function get_field_values() { return $this->field_values; }
	
	
	public function set_post_id( $value ) { $this->post_id = $value;  }
	public function set_id( $value ) {  $this->id = $value; }
	public function set_field_value( $key , $value ){ $this->field_values[ $key ] = $value; }
	
	
	public function init(){
		
		add_action( 'init', array( $this , 'register_types' ) );
		add_action( 'edit_form_after_title' , array( $this , 'the_editor' ) );
		add_filter( 'the_content', array( $this , 'get_profile' ) );
		
	} // end init
	
	
	public function set_from_crest( $person ){
		$crest_id = ( isset( $person->PersonId ) ) ? $person->PersonId : '';
		$first_name = ( isset( $person->PersonNames->PersonName->FirstName ) ) ? $person->PersonNames->PersonName->FirstName : '';
		$last_name = ( isset( $person->PersonNames->PersonName->LastName ) ) ? $person->PersonNames->PersonName->LastName : '';
		$display_name = ( isset( $person->PersonNames->PersonName->DisplayName ) ) ? $person->PersonNames->PersonName->DisplayName : '';
		$familiar_name = ( isset( $person->PersonNames->PersonName->FamiliarName ) ) ? $person->PersonNames->PersonName->FamiliarName : '';
		$office_id = ( isset( $person->OfficeId ) ) ? $person->OfficeId : '';
		$office_name = ( isset( $person->OfficeName->OfficeName->Name ) ) ? $person->OfficeName->OfficeName->Name : '';
		$primary_photo_url = ( isset( $person->PrimaryPhotoUrl ) ) ? $person->PrimaryPhotoUrl : '';
		$rfg_office_staff_id = ( isset( $person->RFGOfficeStaffID ) ) ? $person->RFGOfficeStaffID : '';
		$position = ( isset( $person->Position ) ) ? $person->Position : '';
		$status = ( isset( $person->Status ) ) ? $person->Status : '';
		$languages = array();
		
		if ( isset( $person->LanguagesSpoken->Language ) ) { 
		
			$languages_objs = $person->LanguagesSpoken->Language;
			
			if ( is_array( $languages_objs ) ){
				
				foreach( $languages_objs as $key => $lang_obj ){
					
					$languages[] = $lang_obj->Name;
					
				} // end foreach
				
			} else {
				
				$languages[] = $languages_objs->Name;
				
			} // end if
		
		} // end if
		
		$brandcode = ( isset( $person->BrandCode ) ) ? $person->BrandCode : '';
		$office_location = ( isset( $person->OfficeLocation ) ) ? $person->OfficeLocation : '';
		$primary_phone = ( isset( $person->PrimaryPhoneNumber->Number ) ) ? $person->PrimaryPhoneNumber->Number : '';
		$primary_email = ( isset( $person->PrimaryEmail->EmailAddress ) ) ? $person->PrimaryEmail->EmailAddress : '';
		$primary_web_url = ( isset( $person->PrimaryWebUrl ) ) ? $person->PrimaryWebUrl : '';
		$office_staff_id = ( isset( $person->OfficeStaffId ) ) ? $person->OfficeStaffId : '';
		
		$this->set_id( $crest_id );
		$this->set_field_value( 'crest_id', $crest_id );
		$this->set_field_value( 'first_name', $first_name );
		$this->set_field_value( 'last_name', $last_name );
		$this->set_field_value( 'display_name', $display_name );
		$this->set_field_value( 'familiar_name', $familiar_name );
		$this->set_field_value( 'office_id', $office_id );
		$this->set_field_value( 'office_name', $office_name );
		$this->set_field_value( 'primary_photo_url', $primary_photo_url );
		$this->set_field_value( 'rfg_office_staff_id', $rfg_office_staff_id );
		$this->set_field_value( 'position', $position );
		$this->set_field_value( 'status', $status );
		$this->set_field_value( 'languages', $languages );
		$this->set_field_value( 'brandcode', $brandcode );
		$this->set_field_value( 'office_location', $office_location );
		$this->set_field_value( 'primary_phone', $primary_phone );
		$this->set_field_value( 'primary_email', $primary_email );
		$this->set_field_value( 'primary_web_url', $primary_web_url );
		$this->set_field_value( 'office_staff_id', $office_staff_id );
		
	} // end set_by_crest
	
	
	public function set_from_wp_post( $post ){
		
		$this->set_post_id( $post->ID );
		
		$fields = $this->get_fields();
		
		foreach( $fields as $field ){
			
			$this->set_field_value( $field , get_post_meta( $post->ID, '_' . $field, true ) );
			
		} // end foreach
		
		$this->set_id( $this->get_field_value( 'crest_id' ) );
	
	} // end 
	
	
	public function create_person(){
		
		$meta = array();
		
		$fields = $this->get_fields();
		
		$field_values = $this->get_field_values();
		
		foreach( $field_values as $key => $value ){
			
			if ( in_array( $key , $fields ) ){
				
				$meta[ '_' . $key ] = $value;
				
			} // end if
			
		} // end foreach
		
		
		
		$post_array = array(
			'post_title' 	=> $this->get_field_value( 'last_name' ) . ', ' . $this->get_field_value( 'first_name' ),
			'post_status' 	=> 'publish',
			'post_type' 	=> 'people',
			'post_name' 	=> $this->get_field_value( 'first_name' ) . '-' . $this->get_field_value( 'last_name' ),
			'meta_input' 	=> $meta,
		);
		
		$pid = wp_insert_post( $post_array );
		
		$positions = explode( ',', $this->get_field_value( 'position' ) );
		
		if ( is_array( $positions ) ){
			
			foreach( $positions as $position ){
				
				$term = get_term_by( 'name', $position, 'people_position', ARRAY_A );
				
				if ( ! $term ) {
			
					$term = wp_insert_term( $position, 'people_position' );
					
				} // end if
				
				wp_set_object_terms( $pid, $term['term_id'], 'people_position' );
				
			} // end foreach
			
		} // end if	
		
		return array(
			'first_name' 	=> $this->get_field_value( 'first_name' ),
			'last_name' 	=> $this->get_field_value( 'last_name' ),
			'post_id' 		=> $pid,
		);	
		
	} // end create_person
	
	
	public function update_person( $crest_person ){
		
		$updated = array();
		
		$field_values = $this->get_field_values();
		
		foreach( $field_values as $key => $value ){
			
			if ( $value != $crest_person->get_field_value( $key ) ){
				
				update_post_meta( $this->get_post_id() , $key, $value );
				
				$updated[] = $key;
				
			} // end if
			
		} // end foreach
		
		return array(
			'first_name' 	=> $this->get_field_value( 'first_name' ),
			'last_name' 	=> $this->get_field_value( 'last_name' ),
			'post_id' 		=> $this->get_post_id(),
			'updated' 		=> $updated,
		);	
		
	} // end update_person
	
	
	public function do_update_person(){
		
		if ( is_singular('people') ){
			
			global $post;
			
			$this->set_from_wp_post( $post );
			
			$this->update_details();
			
			$post->post_content = $this->get_field_value( 'description' );
			
		} // end if
		
	} // end do_update_person
	
	
	public function update_details(){
		
		require_once 'class-crest-request-people.php';
		$crest_request = new CREST_Request_People();
		
		$crest_person_details = $crest_request->get_person_detail( $this->get_id() );
		
		if ( isset( $crest_person_details->Persons->Person->PersonDetail->ProfileDescriptions->ProfileDescription->Description  ) ){
			
			$description = $crest_person_details->Persons->Person->PersonDetail->ProfileDescriptions->ProfileDescription->Description;
			
			if ( $description != 'NULL' ){
				
				$person_post = array(
					'ID' 			=> $this->get_post_id(),
					'post_content' 	=> $description,
				);
				
				wp_update_post( $person_post );
				
				$this->set_field_value( 'description', $description );
				
			} // end if
			
		} // end if
		
		//var_dump( $crest_person_details );
		
	} // end update_details
	
	
	public function return_field( $field ){
		
		$field_values = $this->get_field_values();
		
		if ( array_key_exists( $field , $field_values ) && $field_values[ $field ] != 'NULL' ){
			
			return $field_values[ $field ];
			
		} else {
			
			return '';
			
		} // end if
		
	} // end return_field
	
	
	public function register_types(){
		
		$args = array(
      		'public' 	=> true,
      		'label'  	=> 'People',
			'rewrite'	=> array( 'slug' => 'our-team' ),
    	);
		
    	register_post_type( 'people', $args );
		
		register_taxonomy(
			'people_position',
			'people',
			array(
				'label' => 'Positions',
				'rewrite' => array( 'slug' => 'position' ),
				'hierarchical' => true,
			)
		);
		
	} // end register_post_type
	
	
	public function the_editor( $post ){
		
		if ( $post->post_type == 'people' ){
		
			var_dump( get_post_meta( $post->ID ) );
		
		} // end if
		
	} // end the_editor
	
	
	public function get_profile( $content ){
		
		if ( is_singular( 'people' ) ){
			
			global $post;
			
			$image = get_post_meta( get_the_ID(),  '_primary_photo_url', true );
			$name = get_post_meta( get_the_ID(),  '_display_name', true );
			$position = get_post_meta( get_the_ID(),  '_position', true );
			$email = get_post_meta( get_the_ID(),  '_primary_email', true );
			$phone = get_post_meta( get_the_ID(),  '_primary_phone', true );
			$website = get_post_meta( get_the_ID(),  '_primary_web_url', true );
			$link = get_post_permalink();
			
			ob_start();
			
			include WCRESTPLUGINPATH . 'includes/include-profile.php';
			
			$content_html = ob_get_clean();
			
			$content = $content_html;
			
		} // end if
		
		return $content;
		
	}
	
	
} // end CREST_Person