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
	
	public $people;
	public $crest;
	public $crest_settings_page;
	public $crest_preload_page;
	public $taxonomy_positions;
	
	
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
		
		
		require_once 'classes/class-crest-wcrest.php';
		$this->crest = new Crest_WCREST();
		
		$this->add_post_types();
		
		$this->add_settings_pages();
		
		$this->add_taxonomies();
		
		add_filter( 'template_include', array( $this, 'template_include'), 99 );
		
	} // end init
	
	
	private function add_post_types(){
		
		require_once 'post-types/post-type-wcrest.php';
		require_once 'post-types/people/people-post-type-wcrest.php';
		
		$this->people = new People_Post_Type_WCREST();
		$this->people->init();
		
	} // end add_post_types
	
	
	private function add_settings_pages(){
		
		require_once 'settings-pages/settings-page-wcrest.php';
		require_once 'settings-pages/settings/settings-page-settings-wcrest.php';
		require_once 'settings-pages/preload/settings-page-preload-wcrest.php';
		
		$this->crest_settings_page = new Settings_Page_Settings_WCREST();
		$this->crest_preload_page = new Settings_Page_Preload_WCREST();
		
		$this->crest_settings_page->init();
		$this->crest_preload_page->init();
		
	}
	
	
	private function add_taxonomies(){
		
		require_once 'taxonomies/positions_taxonomy_wcrest.php';
		
		$this->taxonomy_positions = new Positions_Taxonomy_WCREST();
		
		$this->taxonomy_positions->init();
		
	} // end add_taxonomies
	
	
	public function template_include( $template ){
		
		if ( ! empty( $_GET['crest-ajax-action'] ) ){
			
			$template =  WCRESTPLUGINPATH . 'templates/ajax-template-wcrest.php';
			
		} // end if
		
		return $template;
		
	} // end template_include
	
} // end WCREST_People

$wcrest = WCREST_People::get_instance();