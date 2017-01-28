<?php 

/* 
  Single Projects Page Template for CPT 
  (Note copious amounts of capital letters above)
*/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the single post content template.
			// get_template_part( 'template-parts/content', 'single' );

      // Imported from content-single.php 
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </header><!-- .entry-header -->

        <?php twentysixteen_excerpt(); ?>

        <?php twentysixteen_post_thumbnail(); ?>

        <?php 
        // This looks like a good spot for our custom fields... 
          $my_id = get_the_ID();
        // Here's the meta array for trouble-shootin' purposes

        //  echo '<pre>';
        //  print_r(array_values(get_post_meta( $my_id )));
        //   echo '</pre>';

        // Here's the simple way. 
          // the_meta(); 

        // And here's the cool way. 
        
          $my_client = get_post_meta( $my_id, '_mcf_client', true);
          $my_deadline = get_post_meta( $my_id, '_mcf_deadline', true);
          $my_estimate = get_post_meta( $my_id, '_mcf_estimate', true);
        
        //  if( ! empty( $my_client ) ) {
            echo '<h3>Client: ' . $my_client . '</h3>';
            echo '<p>Estimate: ' . $my_estimate . '</p>';
            echo '<p>Deadline: ' . $my_deadline . '</p>';
         // } 
        
        ?>

        <div class="entry-content">
          <?php
            the_content();

            wp_link_pages( array(
              'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
              'after'       => '</div>',
              'link_before' => '<span>',
              'link_after'  => '</span>',
              'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
              'separator'   => '<span class="screen-reader-text">, </span>',
            ) );

            if ( '' !== get_the_author_meta( 'description' ) ) {
              get_template_part( 'template-parts/biography' );
            }
          ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer">
          <?php twentysixteen_entry_meta(); ?>
          <?php
            edit_post_link(
              sprintf(
                /* translators: %s: Name of current post */
                __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                get_the_title()
              ),
              '<span class="edit-link">',
              '</span>'
            );
          ?>
        </footer><!-- .entry-footer -->
      </article><!-- #post-## -->

      <?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
				) );
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>

<!-- The above was pretty much copy-pasta'd from the parent theme's single.php, I regret nothing -->
<?php get_footer(); ?>