<?php

class CREST_People_Manager {
	
	protected $people_factory;
	
	
	public function __construct(){
		
		require_once 'class-crest-people-factory.php';
		$this->people_factory = new CREST_People_Factory();
		
	} // end __construct
	
	
	public function update_people(){
		
		$actions = array(
			'created' => array(),
			'updated' => array(),
			'removed' => array(),
		);
		
		if ( $crest_people = $this->people_factory->get_people_from_crest( true ) ){
		
			$wp_people = $this->people_factory->get_people_from_wp( true );
			
			foreach( $crest_people  as $pid => $crest_person ){
				
				if ( array_key_exists( $pid, $wp_people ) ){
					
					$wp_person = $wp_people[ $pid ];
					
					$updates = $wp_person->update_person( $crest_person );
					
					$actions['updated'][ $pid ] = $updates;
					
					unset( $wp_people[ $pid ] );
					
				} else {
					
					$created = $crest_person->create_person();
					
					$actions['created'][ $pid ] = $created;
					
				}// end if
				
			} // end foreach*/
			
			foreach( $wp_people as $pid => $wp_person ){
				
				$actions['created'][] = $pid;
				
			} // end foreach
		
		} // end if
		
		return $actions;
		
	} // end update_people
	
	
	public function update_people_detail(){
		
		set_time_limit ( 3000 );
		
		$actions = array(
			'updated' => array(),
		);
		
		$wp_people = $this->people_factory->get_people_from_wp( true );
		
		$i = 0;
		
		foreach( $wp_people as $wp_person ){
			
			if ( $i > 50 ) break;
			
			$i++;
			
			$wp_person->update_details();
			
			echo $wp_person->get_field_value( 'display_name');
			
			echo '<hr />';
			
			sleep( 1 );
			
		} // end foreach
		
		return $actions;
		
	} // end update_people_detail
	
} // end CREST_People_Manager