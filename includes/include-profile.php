<style>
.tre-profile-banner .tre-profile-image img {
    width: 100%;
	min-width: 350px;
    height: auto;
    border: 1px solid #bebfc1;
}

</style>
<section class="tre-profile-banner">
	<div class="wrap">
    	<div class="tre-table-layout tre-profile-full-card">
        	<div>
            	<div class="tre-profile-image"><img src="<?php echo WCRESTPLUGINURL;?>images/spacer3-4.gif" style="background-image:url(<?php echo $image;?>);background-position:center;background-size:cover;background-repeat:no-repeat"></div>
                <div class="tre-profile-contact">
                	<div class="tre-table-layout">
                    	<div>
                        	<div class="tre-profile-titles">
                            	<h1><?php echo $name;?></h1>
                                <h4><?php echo $position; ?></h4>
                            </div>
                            <div class="tre-profile-logo">
                            	<img src="<?php echo get_stylesheet_directory_uri();?>/resources/sothebys-logo-white.png" />
                            </div>
                        </div>
                    </div>
                    <div class="tre-table-layout tre-profile-contact-details">
                    	<div>
                        <?php if ( ! empty( $phone ) ):?>
                        	<ul class="tre-table-cell tre-profile-contact-phone">
                            	<li class="tre-icon-before"><i class="fa fa-phone" aria-hidden="true"></i><?php echo $phone;?></li>
                                <!--<li class="tre-icon-before"><i class="fa fa-phone" aria-hidden="true"></i>+1 312.751.0300</li> -->
                            </ul><?php endif;?>
                            <div class="tre-profile-contact-location tre-icon-before">
                           <i class="fa fa-map-marker" aria-hidden="true"></i> 425 W North Avenue<br />
Chicago, Illinois 60610 United States
                            </div>
                        </div>
                    </div>
                    <a href="mailto:<?php echo $email; ?>" class="tre-button-light">Contact Agent</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php if ( ! empty( $content ) ):?><section class="tre-profile-about">
	<div class="tre-profile-about-row tre-profile-about-into">
    	<div class="wrap">
            <div class="tre-profile-about-content">
            	<h2>About Agent</h2>
               	<?php echo $content;?>
            </div>
        </div>
    </div>
 </section><?php endif;?>
 <section class="tre-profile-tabs">
	<div class="wrap">
        <div class="tre-tabs">
        	<nav><a href="#" class="tre-active">Current Listings</a><a href="#">Recently Sold</a></nav>
        	<ul class="tre-tabs-wrapper">
            	<li class="tre-active">
                    <div class="tre-gallery listing-gallery">
                    
                        <ul class="tre-gallery-results">
                            <?php for ( $i = 0; $i < 3; $i++ ) {
                                  
                                  echo '<li class="tre-gallery-card">';
                                  
                                 // include locate_template( 'inc/inc-tre-gallery-listing-card.php' );
                                  
                                  echo '</li>';
                                  
                              } // end for ?>
                        </ul>
                    
                    </div>
                
                </li>
                <li>
                <div class="tre-gallery listing-gallery">
                    
                        <ul class="tre-gallery-results">
                            <?php for ( $i = 0; $i < 2; $i++ ) {
                                  
                                  echo '<li class="tre-gallery-card">';
                                  
                                  //include locate_template( 'inc/inc-tre-gallery-listing-card.php' );
                                  
                                  echo '</li>';
                                  
                              } // end for ?>
                        </ul>
                    
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>