<?php

class CREST_People_Factory {
	
	
	public function __construct(){
		
		require_once 'class-crest-person.php';
		
	}  // end __construct
	
	
	public function get_person(){
		
		return new CREST_Person();
		
	} // end get_person
	
	
	public function get_people_from_wp( $crest_only = false ){
		
		$people = array();
		
		$args = array(
			'post_type' 		=> 'people',
			'posts_per_page' 	=> -1,
			'status' 			=> 'publish',
		);
		
		if ( $crest_only ){
			
			$args['meta_query'] = array(
                  array(
                     'key' => '_crest_id',
                     'compare' => 'EXISTS'
                  ),
   			);
			
		} // end if
		
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
				
				$query->the_post();
				
				$person = $this->get_person();
				
				$person->set_from_wp_post( $query->post );
				
				$people[ $person->get_id() ] = $person;
				
			} // end while
			
			wp_reset_postdata();
			
		} // end if
		
		return $people;
		
	} // end get_people_from_wp
	
	
	public function get_people_from_crest() {
		
		$people = array();
		
		require_once 'class-crest-request-people.php';
		$crest_request = new CREST_Request_People();
		$crest_people = $crest_request->get_people();
		
		foreach( $crest_people as $crest_person ){
			
			$person = $this->get_person();
			
			$person->set_from_crest( $crest_person );
			
			$people[ $person->get_id() ] = $person;
			
		} // end foreach 
		
		return $people;
		
	} // end get_people_from_crest
	
} // end CREST_People_Factory