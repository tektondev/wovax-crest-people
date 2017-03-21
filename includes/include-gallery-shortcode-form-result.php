<li class="tre-gallery-card person-card">
	<a href="<?php echo $link;?>">
    	<img class="tre-image" src="<?php echo WCRESTPLUGINURL;?>images/spacer3-4.gif" style="background-image:url(<?php echo $image;?>);background-position:center;background-size:cover;background-repeat:no-repeat">
    </a>
    <ul class="tre-gallery-card-info person-info person-contact">
        <li class="tre-gallery-card-titles">
            <h3><a href="<?php echo $link;?>"><?php echo $name;?></a></h3>
            <h4><a href="<?php echo $link;?>"><?php echo $position;?></a></h4>
        </li>
        <li class="tre-gallery-card-details person-contact">
            <ul>
                <?php if( $email ):?><li class="tre-icon tre-email"><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></li><?php endif;?>
                <li class="tre-icon tre-phone"><?php if( $phone ):?><i class="fa fa-phone" aria-hidden="true"></i> <?php echo $phone;?><?php endif;?>&nbsp;</li>
                <?php if( $website ):?><li class="tre-icon tre-website"><i class="fa fa-globe" aria-hidden="true"></i> <a href="#"><?php echo $website;?></a></li><?php endif;?>
            </ul> 
        </li>
    </ul>
</li>