<?php
/*
Plugin Name: Special Shortcode Plugin
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 0.1
*/

//        echo "<h1>Hello world!</h1>";

add_action('admin_menu', 'test_plugin_setup_menu');
 
function test_plugin_setup_menu(){
        add_menu_page( 'Shortcode Options', 'SSP Options', 'manage_options', 'test-plugin', 'test_init' );
}
 
function test_init(){
        echo "<h1>Special Shortcode Plugin</h1>";
        echo '<div class="wrap">';
        echo '<p>Here is where the form would go if I actually had options.</p>';
        echo '</div>';
}

//	add_options_page( 'SSP', 'SSP Menu', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );


?>