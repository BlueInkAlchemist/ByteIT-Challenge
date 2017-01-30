<?php
/*
Plugin Name: Special Shortcode Plugin (SSP)
Description: A demo plugin for the ByteIT Coding Challenge
Author: Josh Loomis
Version: 0.4
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
        private $_initialValue;
        private $_optionName;

        // Construct the class 
        function __construct()
        {
            $this->_name = 'Special Shortcode Plugin';
            $this->_value = array();
            $this->_optionName = 'special_shortcode_option';

            $this->loadSettings();
            $this->init();
            $this->handlePostback();
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
                    <form method="post" class="shortcode_plugin_form" action="">
                        <tr>
                            <th class="scope">
                              <label for="shortcode_content">Shortcode Content:</label>
                            </th>
                            <td>
                              <input type="text" size="50" name="shortcode_content" value="' .
                                 $this->get_setting('shortcode_content') . '"/>
                            </td>
                        </tr>
                        <p class="submit"><input type="submit" value="Save Changes" class="button" /></p>
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
            $o .= $this->_get_setting('shortcode_content');
     
            // end p
            $o .= '</p>';
        
            // return output
            return $o;

        }

        // Setting handler
        function get_setting($setting) {
            return $this->_value['ssp_cotent'][0][$setting];
        }


        // Load the settings
         function loadSettings() {
            $dbValue = get_option($this->_optionName);
            if (strlen($dbValue) > 0) {
                $this->_value = json_decode($dbValue,true);

                if (empty($this->_value['ssp_content'][0]['content'])) {
                    $this->_value['ssp_content'][0]['content'] = '';
                }

                $this->_initialValue = $this->_value;
            } else {
                $deprecated = ' ';
                $autoload = 'yes';
                $value = '{"ssp_content":[{"content":""}]}';
                $result = add_option( $this->_optionName, $value, $deprecated, $autoload );
                $this->loadSettings();
            }
        }

        // Post and save settings
        function handlePostback() {
            if (isset($_POST['isPostback'])) {
                $v = array();
                $v['ssp_content'][] = array('content' => $_POST['shortcode_content']);
                $this->_value = $v;
                $this->save();
            }
        }

        function save() {
            if (($this->_initialValue != $this->_value)) {
                update_option($this->_optionName, json_encode($this->_value));
                echo '<div class="updated"><p><strong>settings saved</strong></p></div>';
            }
        }

    }
}    
 
?>    