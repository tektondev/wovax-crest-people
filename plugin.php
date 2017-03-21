<?php
/*
Plugin Name: Wovax CREST People
Plugin URI: https://www.wovax.com/
Description: Sync people with CREST feed.
Version: 0.0.1
Author: Wovax, Danial Bleile.
Author URI: https://www.wovax.com/
*/

class WCREST_People {
	
	// @var string Version
	public static $version = '0.0.1';
	
	public static $instance;
	
	
	public static function get_instance(){
		
		if ( null == self::$instance ) {
			 
            self::$instance = new self;
			self::$instance->init();
			
        } // end if
 
        return self::$instance;
		
	} // end get_instance
	
	
	private function init(){
		
		define( 'WCRESTPLUGINURL' , plugin_dir_url(__FILE__) );
		define( 'WCRESTPLUGINPATH' , plugin_dir_path(__FILE__) );
		
		require_once 'classes/class-crest-person.php';
		require_once 'classes/class-crest-shortcode-people.php';
		
		$person = new CREST_Person();
		$person->init();
		
		$shortcodes = new CREST_Shortcode_People();
		$shortcodes->init();
		
		add_filter( 'template_include', array( $this, 'filter_template_include' ), 9999 );
		add_action( 'wp' , array( $person, 'do_update_person' ) );
		
	} // end init
	
	
	public function filter_template_include( $template ){
		
		if ( isset( $_GET['crest_cron'] ) ){
			
			switch( $_GET['crest_cron'] ) {
				
				case 'update-people':
					$template = WCRESTPLUGINPATH . 'templates/template-crest-cron-update-people.php';
					break;
				case 'update-people-detail':
					$template = WCRESTPLUGINPATH . 'templates/template-crest-cron-update-people-detail.php';
					break;
				
			} // end switch
			
		} // end if
		
		return $template;
		
	} // end filter_template_include
	
} // end WCREST_People

$wcrest = WCREST_People::get_instance();