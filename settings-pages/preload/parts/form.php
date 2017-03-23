<form id="crest-office-preload" method="post">
	<fieldset class="office-select">
    	<div class="crest-people-field crest-people-office">
        	<label>Select Office</label>
        	<select id="office_id" name="office_id">
				<?php foreach( $offices as $office_id => $name ):?>
                    <option value="<?php echo $office_id; ?>"><?php echo $name;?></option>
                <?php endforeach;?>
        	</select>
        </div>
        <div class="crest-people-field">
            <input type="submit" name="_preload_office" value="Preload" />
        </div>
        <ul id="people-loaded">
        </ul>
    </fieldset>
</form>
<script>
var crest_preload = {
	
	init:function(){
		
		crest_preload.events();
		
	}, // end init
	
	events:function(){
		
		jQuery('#crest-office-preload').on(
			'submit',
			function( e ){
				e.preventDefault();
				crest_preload.ajax.get_people( jQuery( this ), crest_preload.load_person );
			}
		) // end #crest-office-preload submit
		
	}, // end events
	
	ajax:{
		
		get_people:function( form, callback ){
			
			var url = '<?php echo get_site_url();?>?crest-ajax-action=query-office';
			
			var data = form.serialize();
			
			jQuery.get(
				url,
				data,
				function( response ){
					callback( response );	
				},
				'json'
			) // end get
			
		}, // end get_people
		
		get_person:function( i, people_ids, callback ){
			
			var url = '<?php echo get_site_url();?>?crest-ajax-action=update-person';
			
			var office_id_val = jQuery( '#office_id' ).val();
			
			var data = { person_id:people_ids[i], office_id: office_id_val };
			
			console.log( data );
			
			jQuery.get(
				url,
				data,
				function( response ){
					console.log( response );
					callback( response, i, people_ids );	
				},
				'json'
			) // end get
			
		}, // end get_person
		
	}, // end ajax
	
	load_person:function( people ){
		
		if ( people.status ){
			
			var people_ids = people.response;
			
			crest_preload.ajax.get_person( 0, people_ids, crest_preload.callbacks.person_loaded );
			
		} // end if
		
	}, // end load people
	
	callbacks:{
		
		person_loaded:function( response, i, people_ids ){
			
			jQuery('#people-loaded').prepend('<li>' + ( i + 1 ) + '. ' + people_ids[i] + ' ' + response['msg'] + '</li>' );
			
			console.log( response );
			
			i++; 
			
			if ( i < people_ids.length ) {
				
			//if ( i < 10 ) {  
				
				setTimeout(function () {       
         			crest_preload.ajax.get_person( i, people_ids, crest_preload.callbacks.person_loaded );
				}, 500 )
				             
      		} else {
				
				jQuery('#people-loaded').prepend('<li>All Done!</li>' );
				
			}// end if 
			
		}
		
	}, // end callbacks
	
}
crest_preload.init();
</script>