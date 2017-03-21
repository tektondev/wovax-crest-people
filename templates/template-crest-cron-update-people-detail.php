<?php

class Template_CREST_Update_People_Detail {
	
	
	public function __construct(){
		
		$this->update_people();
		
	} // end __construct
	
	
	private function update_people(){
		
		require_once WCRESTPLUGINPATH . 'classes/class-crest-people-manager.php';
		$people_manager = new CREST_People_Manager();
		$results = $people_manager->update_people_detail();
		
		//echo json_encode( $results );
		
	} // end update_people
	
	
} // end Template_CREST_Update_People

$crest_update_people = new Template_CREST_Update_People_Detail();