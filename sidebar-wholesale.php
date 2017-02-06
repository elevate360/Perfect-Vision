<?php
/**
 * Outputs the wholesale sidebar, used on the wholesale template
 */

if ( ! is_active_sidebar( 'sidebar-wholesale' ) ) {
	return;
}
?>

<aside id="content-sidebar" class="widget-area el-col-small-12 el-col-medium-4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-wholesale' ); ?>
</aside><!-- #secondary -->
