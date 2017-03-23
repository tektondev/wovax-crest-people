<?php

class AJAX_Template_WCREST {
	
	
	public function __construct(){
		
		if ( ! empty( $_GET['crest-ajax-action'] ) ){
			
			switch( $_GET['crest-ajax-action'] ){
				
				case 'query-office':
					$this->query_office();
					break;
				case 'update-person':
					$this->load_person();
					break;
			} // end switch
			
		} else {
			
			$json = array( 'status' => false, 'msg' => 'No Action Found' );
			
			die( json_encode( $json ) );
			
		} // end if
		
	} // end __construct
	
	
	private function query_office(){
		
		if ( isset( $_GET['office_id'] ) ){
			
			$office_id = sanitize_text_field( $_GET['office_id'] );
			
			$crest = new Crest_WCREST();
			
			$response = $crest->get_people_ids_by_office( $office_id );
			
			require_once WCRESTPLUGINPATH . 'classes/class-crest-wcrest.php';
			
			echo json_encode( $response );
			
			die();
			
		} else {
			
			$json = array( 'status' => false, 'msg' => 'No Office ID' );
			
			die( json_encode( $json ) );
			
		} // end if
		
	} // end query_office
	
	
	private function load_person(){
		
		if ( isset( $_GET['person_id'] ) ){
			
			$person_id = sanitize_text_field( $_GET['person_id'] );
			
			$office_id = ( isset( $_GET['person_id'] )  )? sanitize_text_field( $_GET['person_id'] ) : '';
			
			require_once WCRESTPLUGINPATH . 'classes/class-crest-wcrest.php';
			
			$crest = new Crest_WCREST();
			
			$response = $crest->get_person_by_id( $person_id );
			
			if ( ! empty( $response['status'] ) ){
				
				require_once  WCRESTPLUGINPATH . 'classes/class-person-wcrest.php';
				
				$person = new Person_WCREST();
				
				if ( $person->set_person_from_crest( $response['response'] ) ){
					
					$person_response = $person->create_person();
					
					if ( $person_response['response'] && ! empty( $office_id ) ){
						
						$person->append_office( $person_response['response'], $office_id );
						
					} // end if
					
					echo json_encode( $person_response );
					
					die();
					
				} else {
					
					$json = array( 'status' => false, 'msg' => 'Could not set person' );
			
					die( json_encode( $json ) );
					
				}// end if;
				
			} else {
				
				$json = array( 'status' => false, 'msg' => 'Invalid Response' );
			
				die( json_encode( $json ) );
				
			}
			
			//echo json_encode( $response );
			
			die();
			
		} else {
			
			$json = array( 'status' => false, 'msg' => 'No Person ID' );
			
			die( json_encode( $json ) );
			
		} // end if
		
	}
	
} // end AJAX_Template_WCREST

$wcrest_template = new AJAX_Template_WCREST();