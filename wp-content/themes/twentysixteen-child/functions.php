<?php
  add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
  function my_theme_enqueue_styles() {
     
     $parent_style = 'parent-style'; // This is 'twentysixteen-style' for the Twenty Sixteen theme.
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version') // Cache-busting goodness.
    );
   }

   add_action( 'init', 'create_fb_project_post_type' ); // Adding 'Project' post type to theme
    function create_fb_project_post_type() {
    register_post_type('projects',
            array(
                'labels' => array(
                    'name' => __( 'Project\'s' ),
                    'singular_name' => __( 'Project' )
                ),
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('post_tag')
            )
        );
    }   // Remember to flush & regenerate permalinks!
?>