<?php
/*
Plugin Name: Special Shortcode Plugin
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 0.2
*/

class SpecialPluginPage
{

        // Holds options from plugin page 
        private $options;

        // Construct the class 
        public function __construct()
        {
                add_action('admin_menu', array($this, 'add_plugin_page'));
                add_action('admin_init', array($this, 'page_init'));
        }

        // Add the options page 
        public function add_plugin_page()
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

        // Callback for our new options page 
        public function create_admin_page()
        {
                // Set class property
                $this->options = get_option( 'my_option_name' );
                ?>
                <div class="wrap">
                <h1>Special Shortcode Plugin</h1>
                <form method="post" action="options.php">
                <?php
                        // This prints out all hidden setting fields
                        settings_fields( 'my_option_group' );
                        do_settings_sections( 'test-plugin' );
                        submit_button();
                ?>
                </form>
                </div>
                <?php
        }
                

     // Register and add settings
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Shortcode Options', // Title
            array( $this, 'print_section_info' ), // Callback
            'test-plugin' // Page
        );  

        add_settings_field(
            'content', 
            'Shortcode Content', 
            array( $this, 'content_callback' ), 
            'test-plugin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['content'] ) )
            $new_input['content'] = sanitize_text_field( $input['content'] );

        return $new_input;
    }

    // Print the Section text
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }


    // Get the settings option array and print one of its values
    public function content_callback()
    {
        printf(
            '<input type="text" id="content" name="my_option_name[content]" value="%s" />',
            isset( $this->options['content'] ) ? esc_attr( $this->options['content']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new SpecialPluginPage();
?>    