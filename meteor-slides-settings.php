<?php
		
		// Populate the sections and settings of the options page
		
		function meteorslides_section_text() {
	
			echo "<p>Set up your slideshow using the options below.</p>";

		}
	
		function slideshow_quantity() {

			$options = get_option('meteorslides_options');

			echo "<input id='slideshow_quantity' name='meteorslides_options[slideshow_quantity]' size='20' type='text' value='{$options['slideshow_quantity']}' />";

		}
	
		function slide_height() {

			$options = get_option('meteorslides_options');

			echo "<input id='slide_height' name='meteorslides_options[slide_height]' size='20' type='text' value='{$options['slide_height']}' /> px";

		}
		
		function slide_width() {

			$options = get_option('meteorslides_options');

			echo "<input id='slide_width' name='meteorslides_options[slide_width]' size='20' type='text' value='{$options['slide_width']}' /> px";

		}
	
		function  transition_style() {
	
			$options = get_option('meteorslides_options');
		
			$items = array("blindX", "blindY", "blindZ", "cover", "curtainX", "curtainY", "fade", "fadeZoom", "growX", "growY", "none", "scrollUp", "scrollDown", "scrollLeft", "scrollRight", "scrollHorz", "scrollVert", "slideX", "slideY", "shuffle", "turnUp", "turnDown", "turnLeft", "turnRight", "uncover", "wipe", "zoom");
		
			echo "<select id='transition_style' name='meteorslides_options[transition_style]' style='width:142px;'>";
		
			foreach($items as $item) {
		
				$selected = ($options['transition_style']==$item) ? 'selected="selected"' : '';
		
				echo "<option value='$item' $selected>$item</option>";
	
			}
		
			echo "</select>";
		
		}
		
		function transition_speed() {

			$options = get_option('meteorslides_options');

			echo "<input id='transition_speed' name='meteorslides_options[transition_speed]' size='20' type='text' value='{$options['transition_speed']}' /> seconds";

		}
		
		function slide_duration() {

			$options = get_option('meteorslides_options');

			echo "<input id='slide_duration' name='meteorslides_options[slide_duration]' size='20' type='text' value='{$options['slide_duration']}' /> seconds";

		}

?>

<div class="wrap">
	
	<div id="icon-edit" class="icon32"><br /></div>
	
	<h2>Meteor Slides Settings</h2>

	<form action="options.php" method="post">

		<?php
				
		settings_fields('meteorslides_options');
				
		do_settings_sections('meteorslides');

		?>
		
		<p class="submit">

			<input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		
		</p>
				
	</form>
	
	<h3>Install Slideshow</h3>
	
	<p>Use <code><&#63;php if(function_exists('meteor_slideshow')) { meteor_slideshow(); } &#63;></code> to add this slideshow to your theme, or use <code>[meteor_slideshow]</code> to add it to your Post or Page content.
	
	<p><em>Please <a title="Post a question or problem in the forums" href="http://wordpress.org/tags/meteor-slides?forum_id=10#postform">post any questions or problems</a> in the WordPress.org support forums.</em></p>

</div><!-- .wrap -->