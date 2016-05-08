<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/7/2016
 * Time: 7:46 PM
 */

function ctxphc_em_styles_meta_boxes(){
	add_meta_box('em-event-styles', 'Styles', 'ctxphc_em_styles_metabox',EM_POST_TYPE_EVENT, 'side','low');
}
add_action('add_meta_boxes', 'ctxphc_em_styles_meta_boxes');

function ctxphc_em_styles_metabox(){
	global $EM_Event;
	$ctxphc_em_styles = (is_array(get_option('ctxphc_em_styles'))) ? get_option('ctxphc_em_styles'):array();
	foreach( $ctxphc_em_styles as $style_id => $style ){
		?>
		<label>
			<input type="checkbox" name="event_styles[]" value="<?php echo $style_id; ?>" <?php if(in_array($style_id, $EM_Event->styles)) echo 'checked="checked"'; ?> />
			<?php echo $style ?>
		</label><br />
		<?php
	}
}