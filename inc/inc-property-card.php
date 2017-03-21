<a href="#" class="tre-modal"  data-modalid="tre-property-<?php echo $i;?>" ><img class="tre-image" style="background-image:url(<?php echo $property->get_featured_image();?>)" src="<?php echo get_stylesheet_directory_uri();?>/images/property-spacer.gif" /></a>
<ul class="tre-gallery-card-info">
    <li class="tre-gallery-card-titles tre-gallery-card-titles-small tre-gallery-side-left">
        <div class="tre-col-two tre-listings-cost">
        	<span class="tre-emphasis"><?php echo $property->get_price();?></span>
        </div>
        <h5 class="tre-col-one"><?php echo $property->get_title();?><br/>
		<?php echo $property->get_city();?>, <?php echo $property->get_state();?> <?php echo $property->get_zip();?></h5>
    </li>
    <li class="tre-gallery-card-details">
        <ul class="tre-gallery-side-left">
            <li class="tre-col-two"><a href="#" class="tre-light-link tre-icon-after tre-modal" data-modalid="tre-property-<?php echo $i;?>">Quick View <i class="fa fa-caret-right" aria-hidden="true"></i></a></li>
            <li class="tre-col-one"><?php echo $property->get_beds();?> Beds <?php if( $property->get_bath_full() || $property->get_bath_half() ) { echo '  |  ';}?><?php echo $property->get_bath_text();?></li>
        </ul> 
    </li>
</ul>
<div id="tre-property-<?php echo $i;?>" class="tre-modal-content">
	<?php include locate_template( 'inc/inc-tre-modal-listing.php' ); ?> 
</div>
<a href="#" class="tre-full-link tre-modal" data-modalid="tre-property-<?php echo $i;?>"></a>