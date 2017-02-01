<?php
  
  // Init Custom Style
  function my_theme_enqueue_styles() {
     $parent_style = 'parent-style'; // This is 'twentysixteen-style' for the Twenty Sixteen theme.
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version') // Cache-busting goodness.
    );
   }
   add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

   function my_theme_enqueue_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script('custom-script', $parent_style, get_template_directory_uri() . '/js/toggle_special.js', array('jquery'));

   }
   // load js in footer 
    add_action('wp_footer', 'my_theme_enqueue_scripts');

   // Adding 'Project' CPT to theme
    function create_fb_project_post_type() {
    register_post_type('projects',
            array(
                'labels' => array(
                    'name' => __( 'Projects' ),
                    'singular_name' => __( 'Project' )
                ),
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('post_tag','category'),
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'custom-fields', 'excerpt' )
            )
        );
    }
    add_action( 'init', 'create_fb_project_post_type' );    

   // Show 'Project' CPT in loop 
    function add_custom_post_type_to_query( $query ) {
        if ( $query->is_home() && $query->is_main_query() ) {
            $query->set( 'post_type', array('post', 'projects') );
        }
    }
    add_action( 'pre_get_posts', 'add_custom_post_type_to_query' );

    // Show 'Project' CPTs in main archive - functionality TBD
    /*
    function namespace_add_custom_types( $query ) {
        if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
            $query->set( 'post_type', array(
                'post', 'nav_menu_item', 'projects'
            ));
            return $query;
        }
    }
    add_filter( 'pre_get_posts', 'namespace_add_custom_types' );
    */

    // Custom Field Functionality - Based on author of "Developer's Custom Fields" Plugin - https://sltaylor.co.uk/blog/control-your-own-wordpress-custom-fields/

    if ( !class_exists('myCustomFields') ) {
 
    class myCustomFields {
        /**
        * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
        */
        var $prefix = '_mcf_';
        /**
        * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
        */
        var $postTypes = array( "page", "post", "projects" );
        /**
        * @var  array  $customFields  Defines the custom fields available
        */
        var $customFields = array(
            array(
                "name"          => "block-of-text",
                "title"         => "A block of text",
                "description"   => "",
                "type"          => "textarea",
                "scope"         =>   array( "page" ),
                "capability"    => "edit_pages"
            ),
            array(
                "name"          => "short-text",
                "title"         => "A short bit of text",
                "description"   => "",
                "type"          =>   "text",
                "scope"         =>   array( "post" ),
                "capability"    => "edit_posts"
            ),
            array(
                "name"          => "checkbox",
                "title"         => "Checkbox",
                "description"   => "",
                "type"          => "checkbox",
                "scope"         =>   array( "post", "page" ),
                "capability"    => "manage_options"
            ),
             array(
                "name"          => "deadline",
                "id"            => "deadline",
                "title"         => "Deadline",
                "description"   => "",
                "type"          => "text_date",
                "scope"         =>   array( "projects" ),
                "capability"    => "edit_posts"
            ),
            array(
                "name"          => "client",
                "title"         => "Client",
                "description"   => "",
                "type"          =>   "text",
                "scope"         =>   array( "projects" ),
                "capability"    => "edit_posts"
            ), 
            array(
                "name"          => "estimate",
                "title"         => "Estimate",
                "description"   => "",
                "type"          =>   "text",
                "scope"         =>   array( "projects" ),
                "capability"    => "edit_posts"
            )
        );
        /**
        * PHP 4 Compatible Constructor
        */
        function myCustomFields() { $this->__construct(); }
        /**
        * PHP 5 Constructor
        */
        function __construct() {
            add_action( 'admin_menu', array( $this, 'createCustomFields' ) );
            add_action( 'save_post', array( $this, 'saveCustomFields' ), 1, 2 );
            // Comment this line out if you want to keep default custom fields meta box
            add_action( 'do_meta_boxes', array( $this, 'removeDefaultCustomFields' ), 10, 3 );
        }
        /**
        * Remove the default Custom Fields meta box
        */
        function removeDefaultCustomFields( $type, $context, $post ) {
            foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
                foreach ( $this->postTypes as $postType ) {
                    remove_meta_box( 'postcustom', $postType, $context );
                }
            }
        }
        /**
        * Create the new Custom Fields meta box
        */
        function createCustomFields() {
            if ( function_exists( 'add_meta_box' ) ) {
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'my-custom-fields', 'Custom Fields', array( $this, 'displayCustomFields' ), $postType, 'normal', 'high' );
                }
            }
        }
        /**
        * Display the new Custom Fields meta box
        */
        function displayCustomFields() {
            global $post;
            ?>
            <div class="form-wrap">
                <?php
                wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
                foreach ( $this->customFields as $customField ) {
                    // Check scope
                    $scope = $customField[ 'scope' ];
                    $output = false;
                    foreach ( $scope as $scopeItem ) {
                        switch ( $scopeItem ) {
                            default: {
                                if ( $post->post_type == $scopeItem )
                                    $output = true;
                                break;
                            }
                        }
                        if ( $output ) break;
                    }
                    // Check capability
                    if ( !current_user_can( $customField['capability'], $post->ID ) )
                        $output = false;
                    // Output if allowed
                    if ( $output ) { ?>
                        <div class="form-field form-required">
                            <?php
                            switch ( $customField[ 'type' ] ) {
                                case "checkbox": {
                                    // Checkbox
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><b>' . $customField[ 'title' ] . '</b></label>&nbsp;&nbsp;';
                                    echo '<input type="checkbox" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '" value="yes"';
                                    if ( get_post_meta( $post->ID, $this->prefix . $customField['name'], true ) == "yes" )
                                        echo ' checked="checked"';
                                    echo '" style="width: auto;" />';
                                    break;
                                }
                                case "textarea":
                                case "wysiwyg": {
                                    // Text area
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                                    echo '<textarea name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" columns="30" rows="3">' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '</textarea>';
                                    // WYSIWYG
                                    if ( $customField[ 'type' ] == "wysiwyg" ) { ?>
                                        <script type="text/javascript">
                                            jQuery( document ).ready( function() {
                                                jQuery( "<?php echo $this->prefix . $customField[ 'name' ]; ?>" ).addClass( "mceEditor" );
                                                if ( typeof( tinyMCE ) == "object" &amp;&amp; typeof( tinyMCE.execCommand ) == "function" ) {
                                                    tinyMCE.execCommand( "mceAddControl", false, "<?php echo $this->prefix . $customField[ 'name' ]; ?>" );
                                                }
                                            });
                                        </script>
                                    <?php }
                                    break;
                                }
                                default: {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><b>' . $customField[ 'title' ] . '</b></label>';
                                    echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" />';
                                    break;
                                }
                            }
                            ?>
                            <?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
                        </div>
                    <?php
                    }
                } ?>
            </div>
            <?php
        }
        /**
        * Save the new Custom Fields values
        */
        function saveCustomFields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
                return;
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
            if ( ! in_array( $post->post_type, $this->postTypes ) )
                return;
            foreach ( $this->customFields as $customField ) {
                if ( current_user_can( $customField['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
                        $value = $_POST[ $this->prefix . $customField['name'] ];
                        // Auto-paragraphs for any WYSIWYG
                        if ( $customField['type'] == "wysiwyg" ) $value = wpautop( $value );
                        update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $value );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
                    }
                }
            }
        }
 
        } // End Class
    
    } // End if class exists statement
    
    // Instantiate the class
    if ( class_exists('myCustomFields') ) {
        $myCustomFields_var = new myCustomFields();
    }

    // Register widget areas for internal checkbox goodness.
    function register_widget_areas() {
        register_sidebar( array(
            'name'          => __( ' Internal Widget Area', 'twentysixteen-child' ),
            'id'            =>'internal-1',
            'description'   => __( 'Add widgets here to appear in internal pages.', 'twentysixteen-child' ),
            'before_widget' => '
    ', 'after_widget' => '
    ', 'before_title' => '
    <h2 class="widget-title">', 'after_title' => '</h2>
    ', ) ); } 
    
    add_action( 'widgets_init', 'register_widget_areas' ); ;

    // Add Datepicker Functionality - BONUS
    function enqueue_date_picker(){
        wp_enqueue_script(
            'field-date', 
            get_template_directory_uri() . '/field-date.js', 
            array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
            time(),
            true
        );  

        wp_enqueue_style( 'jquery-ui-datepicker' );
    }

    add_action('admin_enqueue_scripts', 'enqueue_date_picker');

    // Remember to flush & regenerate permalinks! 
    // And toggle comments to refresh those too!
    // Comments! Things! Excitement!
?>