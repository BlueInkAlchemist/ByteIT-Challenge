<?php
/*
Plugin Name: Special Shortcode Plugin (SSP)
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 1.0.0
*/
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
        return '<p>'. $options['ssp_text_field_0'] . '</p>';
    }
    add_shortcode( 'i_am_special', 'ssp_shortcode' );

?>