<div class="crest-people-field">
    <label>Active Properties Shortcode</label>
    <input type="text" name="_shortcode_active_override"  value="<?php echo $settings[ '_shortcode_active_override' ];?>" />
</div>
<div class="crest-people-field">
    <label>Closed Properties Shortcode</label>
    <input type="text" name="_shortcode_closed_override"  value="<?php echo $settings[ '_shortcode_closed_override' ];?>" />
</div>
<div class="crest-people-field">
    <label>Youtube Video URL</label>
    <input type="text" name="_youtube_video_url"  value="<?php echo $settings[ '_youtube_video_url' ];?>" />
</div>
<hr />
<h3>From CREST</h3>
<?php foreach( $crest_fields as $field => $label ):?>
<div class="crest-people-field">
    <label><?php echo $label;?></label>
    <input type="text" name="<?php echo $field;?>_manual"  value="" placeholder="<?php echo $settings[ $field ];?>" />
</div>
<?php endforeach;?>
<div class="crest-people-field">
    <label>Offices</label>
    <input type="text" name="_offices"  value="<?php echo $settings[ '_offices' ];?>" />
</div>