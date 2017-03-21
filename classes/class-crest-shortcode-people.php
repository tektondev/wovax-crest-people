<?php

class CREST_Shortcode_People {
	
	
	public function init(){
		
		add_action( 'init', array( $this , 'register' ) );
		
	} // end init
	
	
	public function register(){
		
		add_shortcode( 'crest_people_gallery' , array( $this , 'the_gallery' ) );
		
	} // end register
	
	
	public function the_gallery( $atts, $content, $tag ){
		
		ob_start();
		
			include WCRESTPLUGINPATH . 'css/gallery.css';
		
		$css = ob_get_clean();
		
		$presets = $this->get_presets( $atts );
		
		$query = $this->get_query( $presets );
		
		$content_html = $this->get_form_controls( $presets, $query );

		$content_html .= $this->get_form_results( $presets, $query );
		
		$html = '<style>' . $css . '</style>' . $this->get_form_wrap( $content_html, $presets, $query );
		
		return $html;
		
	} // end the_gallery
	
	
	protected function get_presets( $atts ){
		
		$defaults = array(
			'paged' => 1,
			'posts_per_page' => 12,
			'display_as' => 'gallery',
			'show_photo' => 1,
			'show_name' => 1,
			'show_title' => 1,
			'show_email' => 1,
			'show_website' => 1,
			'link_to_profile' => 1,
			'role' => 'sales-associate',
			'keyword' => '',
			'orderby' => 'title',
		);
		
		if ( isset( $_GET['cpage'] ) ) $atts['paged'] = sanitize_text_field( $_GET['cpage'] );
		
		if ( isset( $_GET['skeyword'] ) ) $atts['keyword'] = sanitize_text_field( $_GET['skeyword'] );
		
		$presets = shortcode_atts( $defaults , $atts );
		
		return $presets;
		
	} // end get_presets
	
	
	protected function get_query( $presets ) {
		
		$args = array(
			'post_type' => 'people',
			'status'	=> 'publish',
			'posts_per_page' => 12,
			'orderby' => 'title',
			'order' => 'ASC',
		);
		
		if ( ! empty( $presets['posts_per_page'] ) ) $args['posts_per_page'] = $presets['posts_per_page'];
		
		if ( ! empty( $presets['paged'] ) ) $args['paged'] = $presets['paged']; 
		
		if ( ! empty( $presets['role'] ) ) {
			
			$roles_tax = array( 'relation' => 'AND',);
			
			$roles = explode( ',', $presets['role'] );
			
			foreach( $roles as $role ){
				
				$roles_tax[] = array(
					'taxonomy' => 'people_position',
					'field'    => 'slug',
					'terms'    =>  $role,
				);
				
			} // end foreach
			
			$args['tax_query'] = $roles_tax;
			
		} // end if
		
		if ( $presets['keyword'] ){
			
			$the_query = $this->get_search_query( $presets, $args );
				
		} else {
		
			$the_query = new WP_Query( $args );
		
		} // end if
		
		
		return $the_query;
		
	} // end get_query
	
	
	protected function get_search_query( $presets, $args ){
		
		$post_ids = array();
		
		$s_query_args = $args;
		$meta_query_args = $args;
		$meta_query = array();
		
		$meta_fields = array(
		//'_primary_web_url',
		//'_office_staff_id',
		'_primary_email',
		//'_office_location',
		'_primary_phone',
		'_position',
		//'_languages',
		//'_rfg_office_staff_id',
		'_office_name',
		'_display_name',
		'_familiar_name',
		);
		
		foreach( $meta_fields as $meta_field ){
			
			$meta_query[] = array(
				'key'     => $meta_field,
				'value'   => $presets['keyword'],
				'compare' => 'LIKE',
			);
			
		} // end foreach
		
		$meta_query_args['meta_query'] = $meta_query;
		
		//var_dump( $meta_query_args );
		
		$m_query = new WP_Query( $meta_query_args );
		
		if ( $m_query->have_posts() ){
			
			while( $m_query->have_posts() ){
				
				$m_query->the_post();
				
				if( ! in_array( $m_query->post->ID, $post_ids ) ){
					
					$post_ids[] = $m_query->post->ID;
					
				} // end if
				
			} // end while
			
			wp_reset_postdata();
			
		} // end if
		
		//var_dump( $post_ids );
		
		//return $m_query;
		
		$s_query_args['s'] = $presets['keyword'];
		
		$s_query = new WP_Query( $s_query_args );
		
		if ( $s_query->have_posts() ){
			
			while( $s_query->have_posts() ){
				
				$s_query->the_post();
				
				if( ! in_array( $s_query->post->ID, $post_ids ) ){
					
					$post_ids[] = $s_query->post->ID;
					
				} // end if
				
			} // end while
			
			wp_reset_postdata();
			
		} // end if
		
		$args['post__in'] = $post_ids;
		
		$f_query = new WP_Query( $args );
		
		return $f_query;
		
	} // end  get_search_query
		
	
	protected function get_form_controls( $presets, $query ){
		$page = $presets['paged'];
		$total_results = $query->found_posts;
		$total_pages = $query->max_num_pages;
		$next_page = ( ( $page + 1 ) < $total_results ) ? ( $page + 1 ) : 'na';
		$prev_page = ( ( $page - 1 ) > 0 ) ? ( $page - 1 ) : 'na';
		$start_set = ( $page == 1 ) ? 1 : ( $page - 1 );
		$showing_start = ( $page == 1 ) ? 1 : ( $page - 1 ) * $presets['posts_per_page'];
		$showing_end = $showing_start + ( $presets['posts_per_page'] - 1 ) ;
		
		$html = apply_filters( 'crest_people_form_controls_pre', '', $presets, $query );
		
		if ( empty( $html ) ){
			
			ob_start();
			
			include WCRESTPLUGINPATH . 'includes/include-agents-form.php';
			
			include WCRESTPLUGINPATH . 'includes/include-gallery-shortcode-form-control.php';
			
			$html = ob_get_clean();
			
		} // end if
		
		$html = apply_filters( 'crest_people_form_controls', $html, $presets, $query );
		
		return $html;
		
	} // end get_form_controls
	
	
	protected function get_form_results( $presets, $query ){
		
		$html = apply_filters( 'crest_people_form_results_pre', '', $presets, $query );
		
		if ( empty( $html ) ){
			
			$results_html = array();
			
			if ( $query->have_posts() ){
				
				while ( $query->have_posts() ){
					
					$query->the_post();
					$image = get_post_meta( get_the_ID(),  '_primary_photo_url', true );
					if ( empty( $image ) ){
						$image = WCRESTPLUGINURL . 'images/personplaceholder.gif';
					} // end if
					$name = get_post_meta( get_the_ID(),  '_display_name', true );
					$position = get_post_meta( get_the_ID(),  '_position', true );
					$email = get_post_meta( get_the_ID(),  '_primary_email', true );
					$phone = get_post_meta( get_the_ID(),  '_primary_phone', true );
					$website = get_post_meta( get_the_ID(),  '_primary_web_url', true );
					$link = get_post_permalink();
					
					ob_start();
			
					include WCRESTPLUGINPATH . 'includes/include-gallery-shortcode-form-result.php';
					
					$result_html = ob_get_clean();
					
					$results_html[] = $result_html;
					
				} // end while
				
				wp_reset_postdata();
				
			} // end if
			
			ob_start();
			
			include WCRESTPLUGINPATH . 'includes/include-gallery-shortcode-form-results.php';
			
			$html = ob_get_clean();
			
		} // end if
		
		$html = apply_filters( 'crest_people_form_results', $html, $presets, $query );
		
		return $html;
		
	} // end get_form_controls
	
	
	protected function get_form_wrap( $content_html, $presets, $query ){
		
		ob_start();
			
		include WCRESTPLUGINPATH . 'includes/include-gallery-shortcode-form-wrapper.php';
		
		$html = ob_get_clean();
		
		return $html;
		
	} // end get_form_wrap
	
	
	protected function get_format_phone( $phone ){
	} // end _primary_phone
	
	
}