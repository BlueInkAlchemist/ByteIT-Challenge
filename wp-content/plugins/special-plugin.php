<?php
/*
Plugin Name: Special Shortcode Plugin (SSP)
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 0.4.5
*/

add_action('init', 'launch_new_special_plugin');

function launch_new_special_plugin() {
    $sp = new SpecialShortcodePlugin();

}

if (! class_exists('SpecialShortcodePlugin')) {
    class SpecialShortcodePlugin
    {

        // Define variables
        private $_name;
        private $_value;        
        private $_options;
        private $_optionName;

        // Construct the class 
        function __construct()
        {
            $this->_name = 'Special Shortcode Plugin';
            $this->_value = array();
            $this->_optionName = 'special_shortcode_option';

            $this->init();
        }

        // Initialize actions
        function init()
        {
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            $this->add_shortcodes();
        }

        // Create shortcode 
        function add_shortcodes()
        {
            add_shortcode( 'i_am_special', array($this, 'the_special_shortcode'));            
        }

        // Add the options page 
        function add_plugin_page()
        {
            // Located under 'Settings'
            add_options_page(
                    'Shortcode Options',
                    'SSP Options',
                    'manage_options',
                    'test-plugin',
                    array ( $this, 'create_admin_page')
            );

        }

        // Populate the options page 
        function create_admin_page()
        {
            
            echo '
                <div class="wrap">
                    <h1>Special Shortcode Plugin</h1>
                    <form method="post" class="shortcode_plugin_form" action="options.php">';
                        // This prints out all hidden setting fields
                        settings_fields( 'this_option_group' );
                        do_settings_sections( 'shortcode-setting-admin' );
                        submit_button(); 
             echo  '
                    </form>
                </div>
                ';
        }
   
        // Feed the shortcode 
        function the_special_shortcode($content = null)
        {

            // start output
            $o = '';
        
            // start p  
            $o .= '<p>';   
          
            // test output - it might go here 
            $o .= 'Text: ';
            $o .= get_option('ssp_content');
     
            // end p
            $o .= '</p>';
        
            // return output
            return $o;

        }

        // Let's handle the settings. 
        // Initialize admin page 
        function page_init()
        {
            // Register the settings 
             register_setting(
                'this_option_group', // Option group
                'this_option_name', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

            // So far, we have only one section... 
            add_settings_section(
                'setting_section_id', // ID
                'Shortcode Options', // Title
                array( $this, 'print_section_info' ), // Callback
                'shortcode-setting-admin' // Page
            );   

            // ... and one field. But we can add more later!
            add_settings_field(
                'ssp_content', // ID
                'Shortcode Content', // Title
                array( $this, 'title_callback' ), // Callback
                'shortcode-setting-admin', // Page
                'setting_section_id' // Section
            );   
        }

        /**
        * Sanitize each setting field as needed
        *
        * @param array $input Contains all settings fields as array keys
        *
        * (again, we only have one so far, but who knows what the future holds)
        */
        function sanitize( $input )
        {
            $new_input = array();
    
            if( isset( $input['ssp_content'] ) )
                $new_input['ssp_content'] = sanitize_text_field( $input['ssp_content'] );
    
            return $new_input;
        }

        // Print the section test 
        function print_section_info()
        {
           // print 'Shortcode content:';
        }

        /** 
        * Use a different callback function for each option array and its values 
        */
        function title_callback()
        {
            printf(
                '<input type="text" id="title" name="this_option_name[ssp_content]" value="%s" />',
                isset( $this->_options['ssp_content'] ) ? esc_attr( $this->_options['ssp_content']) : ''
            );
        }

    }
}    
 
?>    