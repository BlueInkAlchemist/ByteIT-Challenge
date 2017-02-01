<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
if ( ! is_active_sidebar( 'internal-1' )  ) {
	return;
}

// If we get this far, we have widgets. Let's do this.
?>
<aside id="internal-widgets" class="internal-widgets" role="complementary">
	<?php if ( is_active_sidebar( 'internal-1' ) ) : ?>
		<div class="widget-area">
			<?php dynamic_sidebar( 'internal-1' ); ?>
		</div><!-- .widget-area -->
	<?php endif; ?>

</aside><!-- .internal-widgets -->
