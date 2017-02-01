<?php
/*
Plugin Name: Special Shortcode Plugin (SSP)
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 1.1.0
*/

function load_jquery() {
    wp_enqueue_script( 'jquery' );
}

function load_toggle() {
		wp_enqueue_script( 'toggle_special' );
}

add_action( 'wp_enqueue_script', 'load_jquery' );
add_action( 'wp_enqueue_script', 'load_toggle' );
add_action( 'admin_menu', 'ssp_add_admin_menu' );
add_action( 'admin_init', 'ssp_settings_init' );


function ssp_add_admin_menu(  ) { 

	add_options_page( 'Special Shortcode', 'Special Shortcode', 'manage_options', 'special_shortcode', 'ssp_options_page' );

}


function ssp_settings_init(  ) { 

	register_setting( 'pluginPage', 'ssp_settings' );

	add_settings_section(
		'ssp_pluginPage_section', 
		__( '', 'wordpress' ), 
		'ssp_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'ssp_text_field_0', 
		__( 'Shortcode content:', 'wordpress' ), 
		'ssp_text_field_0_render', 
		'pluginPage', 
		'ssp_pluginPage_section' 
	);


}


function ssp_text_field_0_render(  ) { 

	$options = get_option( 'ssp_settings' );
	?>
	<input type='text' name='ssp_settings[ssp_text_field_0]' value='<?php echo $options['ssp_text_field_0']; ?>'>
	<?php

}


function ssp_settings_section_callback(  ) { 

	echo __( '', 'wordpress' );

}


function ssp_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>Special Shortcode</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php
    }

function ssp_shortcode() {
		$options = get_option( 'ssp_settings' );
		return '<p id="special_zone">'. $options['ssp_text_field_0'] . '</p>';
}
add_shortcode( 'i_am_special', 'ssp_shortcode' );

function ssp_widget_display($args) {
	$title = apply_filters( 'widget_title', $instance[ 'title' ] );
  $blog_title = get_bloginfo( 'name' );
  $tagline = get_bloginfo( 'description' );
	echo $args['before_widget'];
	echo $args['before_title'] . 'SSP Unique Widget' .  $args['after_title'];
	echo "SSP Widget Test";
	?>
	<p>&nbsp;</p>
  <p><strong>Site Name:</strong> <?php echo $blog_title ?></p>
  <p><strong>Tagline:</strong> <?php echo $tagline ?></p>
	<!-- begin checkbox form & function -->
	<p>
	<form id="toggle_form">
		<label for="shortcode_toggle">Show Shortcode Content</label>
   <input type="checkbox" name="special_toggle" id="shortcode_toggle" value="check1">
	</form>
  </p>
	<!-- end checkbox form & function -->
  <?php
	echo $args['after_widget'];
}



wp_register_sidebar_widget(
		'ssp_widget_1',        // unique widget id
		'SSP Widget',          // widget name
		'ssp_widget_display',  // callback function
		array(                  // options
				'description' => 'SSP control widget'
		)
);

?>